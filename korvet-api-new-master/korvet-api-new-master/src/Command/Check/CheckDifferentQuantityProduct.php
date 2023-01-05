<?php

namespace App\Command\Check;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Service\TelegramBotService;

class CheckDifferentQuantityProduct extends Command
{
    const URL = 'https://api.telegram.org/%s/sendMessage';

    protected static $defaultName = 'app:check-different-quantity-product';

    /** @var HttpClientInterface */
    private HttpClientInterface $_client;

    /** @var EntityManagerInterface */
    private EntityManagerInterface $_entityManager;

    /** @var TelegramBotService */
    private TelegramBotService $telegram;

    /**
     * OffMobileCashboxModeUser constructor.
     * @param HttpClientInterface $client
     * @param EntityManagerInterface $entityManager
     * @param TelegramBotService $telegram
     */
    public function __construct(HttpClientInterface $client, EntityManagerInterface $entityManager, TelegramBotService $telegram)
    {
        $this->_client = $client;
        $this->_entityManager = $entityManager;
        $this->telegram = $telegram;
        parent::__construct(self::$defaultName);
    }

    protected function configure()
    {
        $this->setDescription('Проверяем некорректные значения при приеме, на складе, в истории');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $cache = new FilesystemAdapter();
        $value = $cache->getItem('last_errors_qua_app_hist_resp');

        $dataAppAndHist = $this->_getDifferentQuantityAppointmentAndHistory();
        $dataAppAndRecept = $this->_getDifferentQuantityAppointmentAndReceipt();
        $dataStockAndProd = $this->_getDifferentQuantityStockAndProduct();

        $text = 'Ошибки в количествах товаров';

        $cdapa = count($dataAppAndHist);
        $cdaar = count($dataAppAndRecept);
        $dsap = count($dataStockAndProd);


        if (!$value->isHit()) {
            $value->set($cdapa . $cdaar . $dsap);
            $cache->save($value);
        } else {
            if ($value->get() === $cdapa . $cdaar . $dsap) {
                return Command::SUCCESS;
            } else {
                $value->set($cdapa . $cdaar . $dsap);
                $cache->save($value);
            }
        }

        if ($cdapa > 0) {
            $text .= "\nПрием и история: " . $cdapa;
        }

        if ($cdaar > 0) {
            $text .= "\nПрием и чеки: " . $cdaar;
        }

        if ($dsap > 0) {
            $text .= "\nСклад и история: " . $dsap;
        }

        $this->_send($text);
        return Command::SUCCESS;
    }

    private function _getDifferentQuantityStockAndProduct(): array
    {
        return $this->_sql('SELECT rs.name, p.name, doc.product_id, stock.quantity, stock.id,  doc.balance_diff 
                FROM (SELECT stock_id, product_id, SUM(quantity) as balance_diff
                                    FROM document_history AS dh
                                    WHERE dh.deleted = false
                                    GROUP BY product_id, stock_id) as doc
                INNER JOIN product.product_stock  stock ON stock.product_id = doc.product_id and stock.stock_id = doc.stock_id
                INNER JOIN reference.reference_stock rs ON rs.id = doc.stock_id
                INNER JOIN product.product p ON p.id = doc.product_id
                WHERE round(stock.quantity::NUMERIC,5)   <>  round(doc.balance_diff ::NUMERIC,5)   
                        and rs.deleted = false  -- фильтр только по используемым складам
                ORDER BY  rs.name, p.name
                ');
    }

    /**
     * @return array
     */
    private function _getDifferentQuantityAppointmentAndHistory(): array
    {
        return $this->_sql('SELECT 
                  dh.document_uuid, ap.id, dh.stock_id, dh.product_id, abs(dh.cnt) as hist , api.cnt as appoint, ap.date_end
                FROM 
                  (SELECT product_id, stock_id, document_uuid, SUM( quantity ) as cnt
                        FROM public.document_history WHERE deleted = false GROUP BY product_id, stock_id, document_uuid) dh
                INNER JOIN appointment.appointments ap ON ap.uuid = dh.document_uuid
                INNER JOIN 
                 ( SELECT   appointment_id,  product_id,  stock_id,  sum(quantity) as cnt
                    FROM   appointment.appointment_product_item       
                    GROUP BY  appointment_id, product_id, stock_id  ) as api
                     ON api.appointment_id = ap.id and dh.product_id = api.product_id
                     and api.stock_id = dh.stock_id
                WHERE abs(dh.cnt) <> api.cnt');
    }

    /**
     * @return array
     * @throws \Doctrine\DBAL\Exception
     */
    private function _getDifferentQuantityAppointmentAndReceipt(): array
    {
        return $this->_sql('SELECT * FROM
                                      (SELECT cr.id, ri.product_id, sum(ri.amount) as amount, sum(ri.quantity) as cnt
                                        FROM cash.cash_receipt cr
                                        INNER JOIN public.receipt_item ri ON cr.id = ri.cash_receipt_id
                                        GROUP BY cr.id, ri.product_id
                                      ) as cash
                                    INNER JOIN
                                      (SELECT ap.id, ap.cash_receipt_id, api.product_id, sum(api.amount) as amount, sum(api.quantity) as cnt
                                        FROM appointment.appointments ap
                                        INNER JOIN appointment.appointment_product_item api ON ap.id = api.appointment_id
                                        GROUP BY ap.id, ap.cash_receipt_id, api.product_id
                                      ) as appoint 
                                    ON cash.id = appoint.cash_receipt_id and cash.product_id = appoint.product_id
                                    WHERE cash.amount <> appoint.amount or cash.cnt <> appoint.cnt');
    }

    /**
     * @param string $sql
     * @return array
     * @throws \Doctrine\DBAL\Exception
     */
    private function _sql(string $sql): array
    {
        $conn = $this->_entityManager->getConnection();

        $stmt = $conn->prepare($sql);

        $fetch = $stmt->executeQuery();

        return $fetch->fetchAllAssociative();
    }

    /**
     * @param string $text
     * @return void
     */
    private function _send(string $text): void
    {
        $this->telegram->send_message($text);
    }
}

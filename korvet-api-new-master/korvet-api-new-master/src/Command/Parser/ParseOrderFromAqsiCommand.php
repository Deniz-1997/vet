<?php

namespace App\Command\Parser;

use App\Controller\ApiAqsiController;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use App\Entity\Cash\CashReceipt;

class ParseOrderFromAqsiCommand extends Command
{
    protected static $defaultName = 'app:parse-order-from-aqsi';

    /** @var EntityManagerInterface */
    private EntityManagerInterface $_entityManager;

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $_client;

    /**
     * ParseOrderFromAqsiCommand constructor.
     * @param HttpClientInterface $client
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(HttpClientInterface $client, EntityManagerInterface $entityManager)
    {
        $this->_client = $client;
        $this->_entityManager = $entityManager;
        parent::__construct(self::$defaultName);
    }

    protected function configure()
    {
        $this->setDescription('Parse order from AQSI');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $aqsi = new ApiAqsiController($this->_client, $this->_entityManager);

        $conn = $this->_entityManager->getConnection();

        $sql = 'SELECT * FROM cash.cash_receipt p WHERE p.uuid_receipt_mobile IS NOT NULL and p.fiscal_state = :state';
        $stmt = $conn->prepare($sql);
        $fetch = $stmt->executeQuery([
            'state' => 'PRINTING'
        ]);

        $result = $fetch->fetchAllAssociative();

        foreach ($result as $item) {
            $data['filtered.Search'] = $item['id'];
            $resp = $aqsi->request('/v2/Orders', 'GET', $data);

            if ($resp['status']) {
                if (count($resp['data']['rows']) > 0) {
                    $row = $resp['data']['rows'][0];
                    if ($row['status'] === "Оплачен") {
                        $conn = $this->_entityManager->getConnection();

                        $conn->executeUpdate('UPDATE cash.cash_receipt SET fiscal_state = :state WHERE id = :id', [
                            'id' => $item['id'],
                            'state' => 'DONE'
                        ]);

                    }
                }
            }
        }
        return Command::SUCCESS;
    }
}

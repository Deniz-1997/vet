<?php

namespace App\Service\Export;

use App\Entity\Appointment\Appointment;
use App\Entity\FtpHistory;
use App\Entity\ImportExportFile;
use App\Enum\FtpHistoryTypeEnum;
use App\Repository\Appointment\AppointmentRepository;
use App\Repository\Reference\ProductRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Packages\DBAL\Types\PaymentObjectEnum;
use App\Repository\Shop\ShopOrderRepository;

class ExportStockService extends Export
{
    const EXPORT_FILE_NAME_PART = 'korvet_stock';
    const EXPORT_FILE_FOLDER = '/public/uploaded';

    /** @var ContainerInterface */
    private ContainerInterface $container;

    /** @var ProductRepository */
    private ProductRepository $productRepository;

    /** @var AppointmentRepository */
    private AppointmentRepository $appointmentRepository;

    /** @var ShopOrderRepository */
    private ShopOrderRepository $shopOrderRepository;

    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    /**
     * ExportStockService constructor.
     * @param ContainerInterface $container
     * @param ProductRepository $productRepository
     * @param AppointmentRepository $appointmentRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ContainerInterface $container, ProductRepository $productRepository, 
                                AppointmentRepository $appointmentRepository, EntityManagerInterface $entityManager,
                                ShopOrderRepository $shopOrderRepository)
    {
        $this->container = $container;
        $this->productRepository = $productRepository;
        $this->appointmentRepository = $appointmentRepository;
        $this->entityManager = $entityManager;
        $this->shopOrderRepository = $shopOrderRepository;
    }

    /**
     * @param string $dateFrom
     * @param string $dateTo
     * @return mixed
     * @throws Exception
     */
    public function exportStock(string $dateFrom, string $dateTo): array
    {
        $dateFrom = new DateTime($dateFrom);
        $dateTo = new DateTime($dateTo);

        $appointments = $this->appointmentRepository->findBetweenDates($dateFrom, $dateTo);
        $shopOrders = $this->shopOrderRepository->findBetweenDates($dateFrom, $dateTo);
        $exportData = $this->prepareData(array_merge($shopOrders, $appointments));
        $ftpHistories = [];

        $lastFileExport = $this->entityManager->getRepository(FtpHistory::class)->count([
            'operationType' => FtpHistoryTypeEnum::getItem(FtpHistoryTypeEnum::EXPORT)
        ]);

        foreach ($exportData as $index => $data) {
            $fileName = $this->getFileName(new DateTime($index), $lastFileExport);
            $filePath = $this->container->get('kernel')->getProjectDir() . self::EXPORT_FILE_FOLDER;
            $file = $filePath . '/' . $fileName;
            $this->prepareCsvFile($data, $filePath, $fileName);
            $uploadStatus = $this->uploadCsvToFtp($file);
            $ftpHistory = $this->prepareFtpHistory($file, $uploadStatus, $data);

            $exportFile = new ImportExportFile();
            $exportFile->setName($fileName);
            $exportFile->setSourcePath(strlen(file_get_contents($file)), $fileName);
            $ftpHistory->setUploadImportExportFile($exportFile);

            $this->entityManager->persist($exportFile);
            $this->entityManager->persist($exportFile->getUploadedFile());
            $this->entityManager->persist($ftpHistory);
            $ftpHistories[] = $ftpHistory;
        }

        $this->entityManager->flush();

        return $ftpHistories;
    }

    /**
     * @param string $type
     * @param DateTime $date
     * @return array
     * @throws Exception
     */
    public function exportStockByType(string $type, DateTime $date): array
    {
        $dateFrom = ($type === 'around') ? $date->modify('-1 day')->format('Y-m-d 08:00:00') :
            $date->modify('-1 day')->format('Y-m-d 00:00:00');


        $dateTo = ($type === 'around') ? $date->modify('+1 day')->format('Y-m-d 07:59:59') :
            $date->format('Y-m-d 23:59:59');

        echo $dateFrom.' '.$dateTo.PHP_EOL;

        $appointments = ($type === 'around') ? $this->appointmentRepository->findBetweenEndDates(new DateTime($dateFrom), new DateTime($dateTo)) : 
            $this->appointmentRepository->findBetweenDates(new DateTime($dateFrom), new DateTime($dateTo));
        $shopOrders = $this->shopOrderRepository->findBetweenDates(new DateTime($dateFrom), new DateTime($dateTo));

        $exportData = $this->prepareData(array_merge($shopOrders, $appointments), $type);

        $ftpHistories = [];

        $lastFileExport = $this->entityManager->getRepository(FtpHistory::class)->count([
            'operationType' => FtpHistoryTypeEnum::getItem(FtpHistoryTypeEnum::EXPORT)
        ]);

        echo count($exportData).PHP_EOL;

        foreach ($exportData as $index => $data) {
            $fileName = $this->getFileName(new DateTime($index), $lastFileExport);
            $filePath = $this->container->get('kernel')->getProjectDir() . self::EXPORT_FILE_FOLDER;
            $file = $filePath . '/' . $fileName;
            $this->prepareCsvFile($data, $filePath, $fileName);
            $uploadStatus = $this->uploadCsvToFtp($file);
            $ftpHistory = $this->prepareFtpHistory($file, $uploadStatus, $data);

            $exportFile = new ImportExportFile();
            $exportFile->setName($fileName);
            $exportFile->setSourcePath(strlen(file_get_contents($file)), $fileName);
            $ftpHistory->setUploadImportExportFile($exportFile);

            $this->entityManager->persist($exportFile);
            $this->entityManager->persist($exportFile->getUploadedFile());
            $this->entityManager->persist($ftpHistory);
            $ftpHistories[] = $ftpHistory;

            $lastFileExport++;
        }

        $this->entityManager->flush();

        return $ftpHistories;

    }

    /**
     * csv-file's headers
     * @return array
     */
    private static function headersCsvFile(): array
    {
        return [
            '1C ID',
            'Код',
            'Товар',
            'ID Товара',
            'Единица измерения',
            'Количество',
            'Цена',
            'Ставка НДС',
            'Тип номенклатуры',
            'Склад',
            'Склад ID'
        ];
    }

    /**
     * @param array $items
     * @param string $type
     * @return array
     */
    public function prepareData(array $items, string $type = ""): array
    {
        $stocks = [];

        $data = [];

        /** @var $item Appointment */
        foreach ($items as $item) {
            if($item->getDeleted() || $item->getCashReceipt() == null) continue;

            foreach ($item->getProductItems() as $productItem) {

                $product = $productItem->getProduct();

                if ($product->getPaymentObject()->code !== PaymentObjectEnum::COMMODITY) continue;

                if(!empty($type)){
                    # если тип круглосуточных клиник и текущая клиника не работает круглосуточно, то пропускаем
                    if($type === 'around' && !$item->getUnit()->getIsAroundClock()){
                        continue;
                    }

                    # если тип обычные клиеники и текущая клиника кругл. пропускаем
                    if($type === 'default' && $item->getUnit()->getIsAroundClock()){
                        continue;
                    }
                }

                // prepare headers
                if (empty($data[$item->getDate()->format('Y-m-d')])) {
                    $data[$item->getDate()->format('Y-m-d')] = [self::headersCsvFile()];
                }

                if(!isset($stocks[$productItem->getStock()->getExternalId()])){
                    $stocks[$productItem->getStock()->getExternalId()] = [];
                }

                if(!isset($stocks[$productItem->getStock()->getExternalId()][$product->getExternalId()])){
                    $stocks[$productItem->getStock()->getExternalId()][$product->getExternalId()] = [
                        'app_id' => $item->getId(), // Код
                        'product_code' => $product->getProductCode()->getType() ? $product->getProductCode()->getType()->code : null, // Код
                        'name' => $product->getName(), // Товар
                        'external_id' => $product->getExternalId(), // ID Товара
                        'measure' => $productItem->getMeasure(), //Единица измерения
                        'quantity' => $productItem->getQuantity(), // Количество
                        'price' => $productItem->getPrice(), // Цена
                        'vat' => $product->getVatRate()->code ?? null, // Ставка НДС
                        'item_type' => $product->getItemType(), // Тип номенклатуры;
                        'stock_name' => $productItem->getStock()->getName(), // Склад
                        'date' => $item->getDate()->format('Y-m-d'),
                    ];
                } else {
                    $stocks[$productItem->getStock()->getExternalId()][$product->getExternalId()]['quantity'] = $stocks[$productItem->getStock()->getExternalId()][$product->getExternalId()]['quantity'] + $productItem->getQuantity();
                }
            }
        }

        foreach ($stocks as $stock_id => $items) {
            foreach ($items as $item) {

                $data[$item['date']][] = [
                    $item['app_id'],  // 1C ID
                    $item['product_code'], // Код
                    $item['name'], // Товар
                    $item['external_id'], // ID Товара
                    $item['measure'], //Единица измерения
                    $item['quantity'], // Количество
                    $item['price'], // Цена
                    $item['vat'], // Ставка НДС
                    $item['item_type'], // Тип номенклатуры;
                    $item['stock_name'], // Склад
                    $stock_id, // Склад ID
                ];
            }
        }

        return $data;
    }

    /**
     * @param DateTime $dateFrom
     * @param int $count
     * @return string
     */
    public function getFileName(DateTime $dateFrom, int $count): string
    {
        return sprintf(
            '%s_%s_%s.csv',
            self::EXPORT_FILE_NAME_PART,
            $dateFrom->format('d.m.Y'),
            str_pad($count, 3, 0, STR_PAD_LEFT)
        );
    }
}

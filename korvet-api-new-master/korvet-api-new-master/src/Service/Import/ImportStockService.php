<?php

namespace App\Service\Import;

use App\Model\Env;
use App\Packages\DBAL\Types\PaymentObjectEnum;
use App\Packages\DBAL\Types\ProductCodeTypeEnum;
use App\Packages\DBAL\Types\VatRateEnum;
use App\Entity\Embeddable\ProductCode;
use App\Entity\FtpHistory;
use App\Entity\ImportExportFile;
use App\Entity\ProductStock;
use App\Entity\Reference\Product;
use App\Entity\Reference\Stock;
use App\Entity\Reference\Unit;
use App\Enum\DocumentOperationTypeEnum;
use App\Enum\DocumentStateEnum;
use App\Enum\FtpHistoryTypeEnum;
use App\Repository\FtpHistoryRepository;
use App\Repository\Reference\ProductRepository;
use App\Service\Document\DocumentService;
use DateTime;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Generator;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use App\Entity\Reference\MeasurementUnits;
use App\Entity\Reference\Countries;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Class ImportStockService
 */
class ImportStockService
{
    /** @var Product */
    private $_productEntity;

    /** @var ProductRepository */
    private $productRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var FtpHistoryRepository */
    private $ftpHistoryRepository;

    /** @var LoggerInterface */
    private $logger;

//    /** @var FtpHistory */
//    private $currentFtpHistory;

    /** @var DocumentService */
    private $documentService;

    /** @var string */
    private $uploadPath;

    /** @var string */
    private $stubPath;

    private $ftpServer;
    private $ftpUser;
    private $ftpPass;

    private $report = [];
    private $historyResult = [];

    private $vats = [
        '0%' => VatRateEnum::VAT_0,
        '10%' => VatRateEnum::VAT_10,
        '18%' => VatRateEnum::VAT_18,
        '20%' => VatRateEnum::VAT_20,
        '110%' => VatRateEnum::VAT_110,
        '118%' => VatRateEnum::VAT_118,
        '120%' => VatRateEnum::VAT_120,
    ];

    /**
     * ImportStockService constructor.
     * @param ProductRepository $productRepository
     * @param EntityManagerInterface $entityManager
     * @param FtpHistoryRepository $ftpHistoryRepository
     * @param KernelInterface $appKernel
     * @param DocumentService $documentService
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager,
        FtpHistoryRepository $ftpHistoryRepository,
        KernelInterface $appKernel,
        DocumentService $documentService,
        LoggerInterface $logger = null
    )
    {
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
        $this->ftpServer = Env::getenv('FTP_1C_SERVER');
        $this->ftpPass = Env::getenv('FTP_1C_PASSWORD');
        $this->ftpUser = Env::getenv('FTP_1C_USERNAME');
        $this->logger = $logger;

        $this->ftpHistoryRepository = $ftpHistoryRepository;
        $this->uploadPath = $appKernel->getProjectDir() . '/public/uploaded/';
        $this->stubPath = $appKernel->getProjectDir() . '/src/Packages/Stub/LoadServices/';
        $this->documentService = $documentService;
    }

    /**
     * @return array
     */
    protected function loadFilesFromFtp()
    {

        $conn_id = ftp_connect($this->ftpServer) or die("Couldn't connect to " . $this->ftpServer . " \n");
        $login_result = ftp_login($conn_id, $this->ftpUser, $this->ftpPass)
        or die("You do not have access to this ftp server!\n");

        ftp_pasv($conn_id, true);

        $contents = ftp_nlist($conn_id, '.');
        ftp_close($conn_id);
        if (!is_array($contents)) {
            //TODO: show errors
            return [];
        }

        return $contents;
    }

    /**
     * @return array
     */
    protected function loadStub()
    {
        $files = [];
        if (is_dir($this->stubPath)) {
            $files = array_diff(scandir($this->stubPath), array('.', '..'));
        }

        return array_values($files);
    }

    /**
     * @param string $match
     * @param FtpHistoryTypeEnum $operationType
     * @param bool $useStub
     * @return array|Generator
     * @throws Exception
     */
    protected function getData(string $match, FtpHistoryTypeEnum $operationType, bool $useStub = false)
    {
        $files = $useStub ? $this->loadStub() : $this->loadFilesFromFtp();
        foreach ($files as $filePath) {
            if (0 === preg_match($match, $filePath)) {
                continue;
            }
            $ftpHistory = $this->ftpHistoryRepository->findOneBy(['fileName' => $filePath]);

            if ($useStub || !$ftpHistory) {
                $ftpHistory = new FtpHistory();
                $ftpHistory->setFileName($filePath);
                $ftpHistory->setOperationType($operationType);

                $this->entityManager->persist($ftpHistory);
            } else {
                continue;
            }

            $this->report = [
                'countAddedProducts' => 0,
                'countChangedProducts' => 0,
                'countDeletedProducts' => 0,
                'countAddedStocks' => 0,
                'totalCount' => 0,
                'errorCount' => 0,
                'productData' => [],
                'productDataReceipt' => [],
                'productDataСonsumption' => [],
                'stockData' => [],
                'errors' => [],
            ];

//            $this->currentFtpHistory = $ftpHistory;

            if ($useStub) {
                $fileSource = $this->stubPath . $filePath;
            } else {
                $fileSource = 'ftp://' . $this->ftpUser . ':' . $this->ftpPass . '@' . $this->ftpServer . '/' . $filePath;
            }

            //сохраняем файл локально
            $importFile = new ImportExportFile();
            $importFile->setName($filePath);
            $uFileName = uniqid('import_') . '.csv';
            $fileBody = file_get_contents($fileSource);
            $importFile->setSourcePath(strlen($fileBody), $uFileName);
            file_put_contents($this->uploadPath . $uFileName, $fileBody);
            $this->entityManager->persist($importFile->getUploadedFile());
            $this->entityManager->persist($importFile);
            $ftpHistory->setUploadImportExportFile($importFile);

            if (($handle = fopen($fileSource, 'r')) !== false) {
                //пропускаем заголовок
                fgetcsv($handle, 1000, ';');
                while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                    yield $data;
                }
            }

            if ($this->report['countAddedProducts'] > 0
                || $this->report['countChangedProducts'] > 0
                || $this->report['countDeletedProducts'] > 0
            ) {
                $ftpHistory->setDate(new DateTime());
                $ftpHistory->setImported(true);
            }

            if ($this->report['countAddedProducts'] > 0
                || $this->report['countChangedProducts'] > 0
                || $this->report['countDeletedProducts'] > 0
                || count($this->report['errors']) > 0
            ) {
                $ftpHistory->setReport($this->report);
                $this->historyResult[] = $ftpHistory;
            }

            $this->entityManager->flush();
        }

        return [];
    }

    /**
     * @param bool $useStub
     * @return array
     * @throws DBALException
     * @throws Exception
     */
    public function importStock(bool $useStub = false): array
    {
        $values = $this->getData(
            '/^\s*1c_.*?stock_\d/iu',
            FtpHistoryTypeEnum::getItem(FtpHistoryTypeEnum::IMPORT_STOCK),
            $useStub
        );

        foreach ($values as $value) {
            if (count($value) !== 15) {
                continue;
            }

            list (
                $action,
                $externalId,
                $createdAt,
                $article,
                $code,
                $name,
                $fullName,
                $externalId,
                $stockName,
                $stockId,
                $itemType,
                $vat,
                $measure,
                $quantity,
                $price
                ) = $value;

            $array = [
                'vat' => !empty($this->vats[$vat]) ? $this->vats[$vat] : VatRateEnum::NONE,
                'product_code' => $code,
                'name' => $name,
                'price' => $price,
                'article' => $article,
                'fullName' => $fullName,
                'itemType' => $itemType,
                'measure' => $measure,
                'quantity' => floatval($quantity),
            ];

            if ($action == 'Приход') {
                /** @var Stock $stock */
                $stock = $this->entityManager->getRepository(Stock::class)->findOneBy([
                    'externalId' => $stockId
                ]);

                if (!$stock) {
                    $stock = new Stock();
                    $stock->setExternalId($stockId);
                    $stock->setName($stockName);
                    $this->entityManager->persist($stock);
                    $this->report['countAddedStocks']++;
                    $this->report['stockData'][] = [
                        'externalId' => $stockId,
                        'name' => $stockName
                    ];
                    $this->report['totalCount']++;
                }

                // $updated = true;

                if (!$this->_productEntity = $this->productRepository->findOneBy(['externalId' => $externalId])) {
                    $this->_productEntity = new Product();
                    $this->_productEntity->setExternalId($externalId);
                    $this->entityManager->persist($this->_productEntity);
                    $this->report['countAddedProducts']++;
                    $this->report['productDataReceipt'][] = [
                        'isNew' => true,
                        'externalId' => $externalId,
                        'name' => $name,
                        'quantity' => floatval($quantity),
                        'stock' => ['externalId' => $stockId, 'name' => $stockName]
                    ];
                    $this->report['totalCount']++;
                    //$updated = false;

                } else {
                    $this->report['countAddedProducts']++;
                    $this->report['productDataReceipt'][] = [
                        'isNew' => false,
                        'id' => $this->_productEntity->getId(),
                        'externalId' => $this->_productEntity->getExternalId(),
                        'name' => $this->_productEntity->getName(),
                        'quantity' => floatval($quantity),
                        'stock' => ['externalId' => $stockId, 'name' => $stockName]
                    ];
                    $this->report['totalCount']++;

                }

                $array['paymentObject'] = PaymentObjectEnum::getItem(PaymentObjectEnum::COMMODITY);

                if (!$productStock = $this->entityManager->getRepository(ProductStock::class)->findOneBy([
                    'product' => $this->_productEntity->getId(), 'stock' => $stock->getId()
                ])) {
                    $productStock = new ProductStock();
                    $this->entityManager->persist($productStock);
                }

                $this->setProduct($array);

                $this->entityManager->persist($this->_productEntity);

                # Logger for KORVET-40
                $this->logger->error(sprintf('ImportStockService 350. ProductStockID: %d', $productStock->getId()));

                $productStock->setProduct($this->_productEntity);
                $productStock->setStock($stock);
                $productStock->setQuantity(floatval($quantity) + $productStock->getQuantity());
                $this->entityManager->flush();

                $docType = null;
                try {

                    /**
                     *  В рамках задачи https://portal.web-slon.ru/company/personal/user/570/tasks/task/view/12244/
                     *  При обновлениее товара, кол-во должно добавлятся к тому что уже есть на складе. Поэтому
                     *  инвентаризация заменили на поступление.
                     */
                    $docType = DocumentOperationTypeEnum::getItem(DocumentOperationTypeEnum::IMPORT);
                    $this->documentService
                        ->getDocument($docType, $stock)
                        ->addProduct($this->_productEntity, floatval($quantity))
                        ->setState(DocumentStateEnum::getItem(DocumentStateEnum::REGISTERED));

//                    if ($updated)  {
//                        $docType = DocumentOperationTypeEnum::getItem(DocumentOperationTypeEnum::INVENTORY);
//                        $this->documentService
//                            ->getDocument($docType, $stock)
//                            ->addProduct($this->_productEntity, floatval($quantity))
//                            ->setState(DocumentStateEnum::getItem(DocumentStateEnum::REGISTERED));
//
//                    } else {
//                    }


                } catch (Exception $exception) {
                    $doc = $this->documentService
                        ->getDocument($docType, $stock)
                        ->addError($exception->getMessage());
                    $doc->setState(clone DocumentStateEnum::getItem(DocumentStateEnum::ERROR));
                    $this->documentService->removeDocument($doc);
                }
            } elseif ('Расход' === $action) {
                /** @var Stock $stock */
                if (!$stock = $this->entityManager->getRepository(Stock::class)->findOneBy(['externalId' => $stockId])) {
                    $this->report['errors'][] = [
                        'type' => 'Склад не найден',
                        'info' => 'Склад: "' . $stockName . '" не найден.',
                        'product' => ['externalId' => $externalId, 'name' => $name],
                        'stock' => ['stockExternalId' => $stockId, 'name' => $stockName]
                    ];
                    $this->report['errorCount']++;
                    continue;
                }

                if (!$this->_productEntity = $this->productRepository->findOneBy(['externalId' => $externalId])) {
                    $this->report['errors'][] = [
                        'type' => 'Товар не найден',
                        'info' => 'Товар "' . $name . '" не найден',
                        'product' => ['externalId' => $externalId, 'name' => $name]
                    ];
                    $this->report['errorCount']++;
                    continue;
                }

                /**@var ProductStock $productStock */
                if (!$productStock = $this->entityManager->getRepository(ProductStock::class)->findOneBy(
                    ['product' => $this->_productEntity->getId(), 'stock' => $stock->getId()])
                ) {
                    $this->report['errors'][] = [
                        'type' => 'Товар на складе не найден',
                        'info' => 'Товар "' . $this->_productEntity->getName() . '" не найден на складе:' . $stock->getName(),
                        'product' => ['id' => $this->_productEntity->getId(), 'name' => $this->_productEntity->getName()],
                        'stock' => ['id' => $stock->getId(), 'name' => $stock->getName()]
                    ];
                    $this->report['errorCount']++;
                    continue;
                }

                if ($productStock->getQuantity() < $quantity) {
                    $this->report['errors'][] = [
                        'type' => 'Запрашиваемое количество больше чем на складе',
                        'info' => 'На складе "' . $stock->getName()
                            . '" всего: ' . $productStock->getQuantity()
                            . ', необходимо:' . $quantity . ' для списания номенклатуры: ' . $this->_productEntity->getName(),
                        'product' => ['id' => $this->_productEntity->getId(), 'name' => $this->_productEntity->getName()],
                        'stock' => ['id' => $stock->getId(), 'name' => $stock->getName()]
                    ];
                    $this->report['errorCount']++;
                    continue;
                }

                # Logger for KORVET-40
                $this->logger->error(sprintf('ImportStockService 439. ProductStockID: %d', $productStock->getId()));

                $productStock->setQuantity($productStock->getQuantity() - floatval($quantity));

                try {
                    $this->documentService
                        ->getDocument(DocumentOperationTypeEnum::getItem(DocumentOperationTypeEnum::IMPORT), $stock)
                        ->addProduct($this->_productEntity, floatval($quantity) * -1)
                        ->setState(DocumentStateEnum::getItem(DocumentStateEnum::REGISTERED));
                } catch (Exception $exception) {
                    $this->documentService
                        ->getDocument(DocumentOperationTypeEnum::getItem(DocumentOperationTypeEnum::IMPORT), $stock)
                        ->addError($exception->getMessage())
                        ->setState(DocumentStateEnum::getItem(DocumentStateEnum::ERROR));
                }


                $this->report['countDeletedProducts']++;
                $this->report['productDataСonsumption'][] = [
                    'id' => $this->_productEntity->getId(),
                    'externalId' => $this->_productEntity->getExternalId(),
                    'name' => $this->_productEntity->getName(),
                    'quantity' => floatval($quantity),
                    'stock' => ['externalId' => $stockId, 'name' => $stockName]
                ];
                $this->report['totalCount']++;
            } elseif ($action === 'УстановкаЦен') {

                if (!$this->_productEntity = $this->productRepository->findOneBy(['externalId' => $externalId])) {
                    $this->_productEntity = new Product();
                    $this->_productEntity->setExternalId($externalId);
                    $this->entityManager->persist($this->_productEntity);
                    $this->report['countChangedProducts']++;
                    $this->report['productData'][] = [
                        'isNew' => true,
                        'externalId' => $externalId,
                        'name' => $name,
                    ];
                    $this->report['totalCount']++;
                } else {
                    $this->report['countChangedProducts']++;
                    $this->report['productData'][] = [
                        'isNew' => false,
                        'id' => $this->_productEntity->getId(),
                        'externalId' => $this->_productEntity->getExternalId(),
                        'name' => $this->_productEntity->getName()
                    ];
                    $this->report['totalCount']++;
                }

                $array['paymentObject'] = PaymentObjectEnum::getItem(PaymentObjectEnum::COMMODITY);
                $array['vat'] = VatRateEnum::VAT_20;


                $this->setProduct($array);
                $this->entityManager->persist($this->_productEntity);
                $this->entityManager->flush();
            }
        }

        return $this->historyResult;
    }

    /**
     * @param bool $useStub
     * @return array
     * @throws DBALException
     * @throws Exception
     */
    public function importService(bool $useStub = false): array
    {
        $values = $this->getData(
            '/^\s*1c_.*?service/iu',
            FtpHistoryTypeEnum::getItem(FtpHistoryTypeEnum::IMPORT_SERVICE),
            $useStub
        );

        foreach ($values as $value) {
            if (count($value) !== 8) {
                continue;
            }

            list ($action, $externalId, $code, $article, $name, $fullName, $vat, $price) = $value;

            $array = [
                'vat' => !empty($this->vats[$vat]) ? $this->vats[$vat] : VatRateEnum::NONE,
                'product_code' => $code,
                'name' => $name,
                'price' => $price,
                'article' => $article,
                'fullName' => $fullName,
            ];

            if (in_array($action, ['Приход', 'Добавить'])) {
                if (!$this->_productEntity = $this->productRepository->findOneBy(['externalId' => $externalId])) {
                    $this->_productEntity = new Product();
                    $this->_productEntity->setExternalId($externalId);
                    $this->report['countAddedProducts']++;
                    $this->report['productDataReceipt'][] = [
                        'isNew' => true,
                        'externalId' => $this->_productEntity->getExternalId(),
                        'name' => $name
                    ];
                    $this->report['totalCount']++;
                    $array['paymentObject'] = PaymentObjectEnum::getItem(PaymentObjectEnum::SERVICE);
                } else {
                    $this->report['countChangedProducts']++;
                    $this->report['productData'][] = [
                        'isNew' => false,
                        'id' => $this->_productEntity->getId(),
                        'externalId' => $this->_productEntity->getExternalId(),
                        'name' => $this->_productEntity->getName()
                    ];
                    $this->report['totalCount']++;
                }

                $array['vat'] = VatRateEnum::getItem($array['vat']) ?? VatRateEnum::getItem(VatRateEnum::VAT_20);

                $this->setProduct($array);

                $this->entityManager->persist($this->_productEntity);

            } elseif ($action == 'Удалить') {
                if (!$this->_productEntity = $this->productRepository->findOneBy(['externalId' => $externalId])) {
                    $this->report['errors'][] = [
                        'action' => $action,
                        'type' => 'Услуга не найдена',
                        'info' => 'Услуга для удаления не найдена:' . $name,
                        'product' => ['externalId' => $externalId, 'name' => $name]
                    ];
                    $this->report['errorCount']++;
                }

                $this->_productEntity->setDeleted(true);
                $this->report['countDeletedProducts']++;
                $this->report['productDataСonsumption'][] = [
                    'id' => $this->_productEntity->getId(),
                    'externalId' => $this->_productEntity->getExternalId(),
                    'name' => $this->_productEntity->getName()
                ];
                $this->report['totalCount']++;
            }
        }

        return $this->historyResult;
    }

    /**
     * @param bool $useStub
     * @return array
     * @throws DBALException
     * @throws Exception
     */
    public function importStockMove(bool $useStub = false): array
    {
        $values = $this->getData(
            '/^\s*1c_.*?stock_move/iu',
            FtpHistoryTypeEnum::getItem(FtpHistoryTypeEnum::IMPORT_STOCK_MOVE),
            $useStub
        );

        foreach ($values as $value) {
            if (count($value) !== 8) {
                continue;
            }

            list ($date, $stockFromName, $stockFromId, $stockToName, $stockToId, $productName, $externalId, $quantity) = $value;
            /**@var Stock $stockFrom */
            if (!$stockFrom = $this->entityManager->getRepository(Stock::class)->findOneBy(['externalId' => $stockFromId])) {
                $this->report['errors'][] = [
                    'type' => 'Склад не найден',
                    'info' => 'Не найден склад:' . $stockFromId,
                    'stock' => ['externalId' => $stockFromId, 'name' => $stockFrom]
                ];
                $this->report['errorCount']++;

                continue;
            }

            if (!$stockTo = $this->entityManager->getRepository(Stock::class)->findOneBy(['externalId' => $stockToId])) {
                $stockTo = new Stock();
                $stockTo->setExternalId($stockToId);
                $stockTo->setName($stockToName);
                $this->entityManager->persist($stockTo);
                $this->report['countAddedStocks']++;
                $this->report['stockData'][] = [
                    'externalId' => $stockToId,
                    'name' => $stockToName
                ];
                $this->report['totalCount']++;
            }

            if (!$this->_productEntity = $this->productRepository->findOneBy(['externalId' => $externalId])) {
                $this->report['errors'][] = [
                    'type' => 'Товар не найден',
                    'info' => 'Номенклатура не найдена:' . $productName,
                    'product' => ['externalId' => $externalId, 'name' => $productName]
                ];
                $this->report['errorCount']++;
                continue;
            }

            /**@var ProductStock $productStockFrom */
            if (!$productStockFrom = $this->entityManager->getRepository(ProductStock::class)->findOneBy(
                ['product' => $this->_productEntity->getId(), 'stock' => $stockFrom->getId()])
            ) {
                $this->report['errors'][] = [
                    'type' => 'Товар не найден на складе',
                    'info' => 'Номенклатура ' . $this->_productEntity->getName() . ' не найдена на складе ' . $stockFrom->getName(),
                    'product' => ['externalId' => $this->_productEntity->getExternalId(), 'name' => $this->_productEntity->getName(), 'id' => $this->_productEntity->getId()],
                    'stock' => ['externalId' => $stockFrom->getExternalId(), 'name' => $stockFrom->getName(), 'id' => $stockFrom->getId()]
                ];
                $this->report['errorCount']++;

                continue;
            }

            if ($productStockFrom->getQuantity() < $quantity) {
                $this->report['errors'][] = [
                    'type' => 'Запрашиваемое количество больше чем на складе',
                    'info' =>
                        'На складе "' . $stockFrom->getName()
                        . '" всего: ' . $productStockFrom->getQuantity()
                        . ', необходимо:' . $quantity . ' для переноса номенклатуры: ' . $this->_productEntity->getName(),
                    'product' => ['externalId' => $this->_productEntity->getExternalId(), 'name' => $this->_productEntity->getName(), 'id' => $this->_productEntity->getId()],
                    'stock' => ['externalId' => $stockFrom->getExternalId(), 'name' => $stockFrom->getName(), 'id' => $stockFrom->getId()]
                ];
                $this->report['errorCount']++;

                continue;
            }

            /**@var Stock $productStockTo */
            if (!$productStockTo = $this->entityManager->getRepository(ProductStock::class)->findOneBy(
                ['product' => $this->_productEntity->getId(), 'stock' => $stockTo->getId()]
            )) {
                $productStockTo = new ProductStock();
                $productStockTo->setProduct($this->_productEntity);
                $productStockTo->setStock($stockTo);
                $this->entityManager->persist($productStockTo);
            }

            $productStockFrom->setQuantity($productStockFrom->getQuantity() - floatval($quantity));
            $productStockTo->setQuantity($productStockTo->getQuantity() + floatval($quantity));

            # Logger for KORVET-40
            $this->logger->error(sprintf('ImportStockService 685. productStockFromID: %d. $productStockToID: %d',
                $productStockFrom->getId(),
                $productStockTo->getId()
            ));

            $this->report['productData'][] = [
                'id' => $this->_productEntity->getId(),
                'externalId' => $this->_productEntity->getExternalId(),
                'name' => $this->_productEntity->getName(),
                'quantity' => floatval($quantity),
                'stockFrom' => ['externalId' => $stockFromId, 'name' => $stockFromName],
                'stockTo' => ['externalId' => $stockToId, 'name' => $stockToName]
            ];
            $this->report['countChangedProducts']++;
            $this->report['totalCount']++;
            //регистрация документа
            try {
                $this->documentService
                    ->getDocument(DocumentOperationTypeEnum::getItem(DocumentOperationTypeEnum::TRANSFER), $stockFrom,
                        $stockTo)
                    ->addProduct($this->_productEntity, floatval($quantity))
                    ->setState(DocumentStateEnum::getItem(DocumentStateEnum::REGISTERED));
            } catch (Exception $exception) {
                $this->documentService
                    ->getDocument(DocumentOperationTypeEnum::getItem(DocumentOperationTypeEnum::TRANSFER), $stockFrom,
                        $stockTo)
                    ->addError($exception->getMessage())
                    ->setState(DocumentStateEnum::getItem(DocumentStateEnum::ERROR));
            }

            $this->entityManager->flush();
        }

        return $this->historyResult;
    }

    /**
     * @param $filePath
     * @return int
     * @throws DBALException
     * @throws Exception
     */
    public function universalImport($filePath)
    {
        $items = $this->getItems($filePath);
        $countAdd = 0;
        foreach ($items as $item) {

            $updated = true;
            $stock = false;

            if (empty($item['1с_ID'])) {
                continue;
            }

            if (!$this->_productEntity = $this->productRepository->findOneBy(['externalId' => $item['1с_ID']])) {
                $this->_productEntity = new Product();
                $this->_productEntity->setExternalId($item['1с_ID']);
                $this->entityManager->persist($this->_productEntity);
                $updated = false;
            }

            $this->setProduct([
                'name' => $item['Номенклатура'] ?? '',
                'fullName' => $item['Полн наименование'] ?? '',
                'article' => $item['Артикул'] ?? '',
                'measure' => $item['Единица измерения'] ?? '',
                'country' => $item['Страна происхождения'] ?? '',
                'itemType' => $item['Тип номенклатуры'] ?? '',
                'quantity' => $item['Количество'] ?? 0,
                'price' => $item['Цена'] ?? 0,
                'code' => $item['Код'] ?? ''
            ]);

            if ($this->_productEntity->getPrice() > 0) {
                $this->_productEntity->setExistPrice(true);
            }

            //TODO: убирать при перемищении или когда закончились
            if ($this->_productEntity->getQuantity() > 0) {
                $this->_productEntity->setExistQuantity(true);
            }

            //если нет склада это сервис
            if (!empty($item['Склад_ID'])) {

                /** @var Stock $stock */
                if (!$stock = $this->entityManager->getRepository(Stock::class)
                    ->findOneBy(['externalId' => $item['Склад_ID']])) {
                    $stock = new Stock();
                    $stock->setExternalId($item['Склад_ID']);
                    $stock->setName($item['Склад'] ?? '');
                    $this->entityManager->persist($stock);
                }

                //$this->_productEntity->setStock($stock);

                // Добавление товаров в таблицу движения по складам
                $productStock = $this->entityManager->getRepository(ProductStock::class)->findOneBy([
                    'product' => $this->_productEntity,
                    'stock' => $stock,
                ]);
                if (!$productStock) {
                    $productStock = new ProductStock();
                    $productStock->setProduct($this->_productEntity);
                    $productStock->setStock($stock);
                }
                # Logger for KORVET-40
                $this->logger->error(sprintf('ImportStockService 792. productStock: %d', $productStock->getId()));
                $productStock->setQuantity($this->_productEntity->getQuantity());
                $this->entityManager->persist($productStock);

                //TODO: возможно paymentObject определять на другой основе
                $this->_productEntity->setPaymentObject(PaymentObjectEnum::getItem(PaymentObjectEnum::COMMODITY));
            } else {
                $this->_productEntity->setPaymentObject(PaymentObjectEnum::getItem(PaymentObjectEnum::SERVICE));
            }

            //поиск юнита
            if (!empty($item['Подразделение(ИФО)'])) {
                if (!$unit = $this->entityManager->getRepository(Unit::class)
                    ->findOneBy(['name' => $item['Подразделение(ИФО)']])) {
                    $unit = new Unit();
                    $unit->setName($item['Подразделение(ИФО)']);
                    $this->entityManager->persist($unit);
                }

                $this->_productEntity->setUnit($unit);
            }

            $vat = (!empty($this->vats[$item['Ставка НДС']])) ? $this->vats[$item['Ставка НДС']] : VatRateEnum::NONE;

            $this->_productEntity->setVatRate(VatRateEnum::getItem($vat));

            $this->entityManager->flush();

            echo sprintf(
                "%s номенклатура \"%s\"%s \r\n",
                $updated ? 'Обновлена' : 'Добавлена',
                $this->_productEntity->getName(),
                $stock ? sprintf(', на складе "%s"', $stock->getName()) : ''
            );
            $countAdd++;

            //add to history
            $docType = null;
            try {

                if ($updated) {

                    $docType = clone DocumentOperationTypeEnum::getItem(DocumentOperationTypeEnum::INVENTORY);
                    $this->documentService
                        ->getDocument($docType, $stock)
                        ->addProduct($this->_productEntity, $this->_productEntity->getQuantity())
                        ->setState(DocumentStateEnum::getItem(DocumentStateEnum::REGISTERED));

                } else {
                    $docType = clone DocumentOperationTypeEnum::getItem(DocumentOperationTypeEnum::IMPORT);
                    $this->documentService
                        ->getDocument($docType, $stock)
                        ->addProduct($this->_productEntity, $this->_productEntity->getQuantity())
                        ->setState(DocumentStateEnum::getItem(DocumentStateEnum::REGISTERED));
                }
            } catch (Exception $exception) {
                $doc = $this->documentService
                    ->getDocument($docType, $stock)
                    ->addError($exception->getMessage());
                $doc->setState(clone DocumentStateEnum::getItem(DocumentStateEnum::ERROR));
                $this->documentService->removeDocument($doc);
            }

        }

        return $countAdd;
    }

    /**
     * @param string|null $filePath
     * @return array
     */
    protected function getItems(?string $filePath)
    {
        $items = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            //берем заголовок
            $headers = fgetcsv($handle, 1000, ';');
            array_walk($headers, function (&$item) {
                $item = trim($item);
            });

            while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                array_walk($data, function (&$item) {
                    $item = trim($item);
                });
                $items[] = array_combine($headers, $data);
            }
        }

        return $items;
    }

    /**
     * Set product
     *
     * @param array $array
     * @throws DBALException
     */
    protected function setProduct(array $array): void
    {
        if (isset($array['vat'])) {
            $this->_productEntity->setVatRate(VatRateEnum::getItem($array['vat']));
        }

        if (isset($array['product_code'])) {
            $productCodeEntity = new ProductCode();
            $productCodeEntity->setType(ProductCodeTypeEnum::getItem($array['product_code']));
            $this->_productEntity->setProductCode($productCodeEntity);
        }

        if (isset($array['code'])) {
            $this->_productEntity->setCode($array['code']);
        }

        if (isset($array['name'])) {
            $this->_productEntity->setName($array['name']);
        }

        if (isset($array['price'])) {
            $this->_productEntity->setPrice($this->makeFloat($array['price']));
        }

        if (isset($array['quantity'])) {
            $this->_productEntity->setQuantity($this->makeFloat($array['quantity']));
        }

        if (isset($array['article'])) {
            $this->_productEntity->setArticle(trim($array['article']) ?? '');
        }

        if (isset($array['fullName'])) {
            $this->_productEntity->setFullName(trim($array['fullName']) ?? '');
        }

        if (isset($array['itemType'])) {
            $this->_productEntity->setItemType($array['itemType']);
        }

        if (isset($array['measure']) && $array['measure'] !== 'Null') {
            $measurement = $this->entityManager->getRepository(MeasurementUnits::class)->findOneBy([
                'name' => strtolower($array['measure'])
            ]);
            $this->_productEntity->setMeasurementUnits($measurement);
        }

        if (isset($array['country'])) {
            $country = $this->entityManager->getRepository(Countries::class)->findOneBy([
                'name' => strtolower($array['country'])
            ]);
            $this->_productEntity->setCountries($country);
        }

        if (isset($array['paymentObject'])) {
            $this->_productEntity->setPaymentObject($array['paymentObject']);
        }
    }

    /**
     * @param string $data
     * @return float
     */
    public static function makeFloat(string $data): float
    {
        $data = str_replace(',', '.', $data);
        //удаляем вес что не цифра или точкак
        $data = preg_replace('/[^\d\.]/', '', $data);
        //убираем все точки кроме последней
        $count = substr_count($data, '.');
        if ($count > 1) {
            $data = preg_replace('/[\.]/', '', $data, $count - 1);
        }
        return floatval($data);
    }
}


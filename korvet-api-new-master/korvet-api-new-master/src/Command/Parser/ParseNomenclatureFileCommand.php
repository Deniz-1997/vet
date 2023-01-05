<?php

namespace App\Command\Parser;

use App\Entity\Reference\Product;
use App\Repository\Reference\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Packages\DBAL\Types\PaymentObjectEnum;
use App\Packages\DBAL\Types\ProductCodeTypeEnum;
use App\Packages\DBAL\Types\VatRateEnum;
use App\Entity\Embeddable\ProductCode;

class ParseNomenclatureFileCommand extends Command
{
    protected static $defaultName = 'app:parse-nomenclature-file';

    /** @var ProductRepository */
    private ProductRepository $productRepository;

    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    /**
     * ParseNomenclatureFileCommand constructor.
     * @param ProductRepository $productRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ProductRepository $productRepository, EntityManagerInterface $entityManager)
    {
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;

        parent::__construct(self::$defaultName);
    }

    protected function configure()
    {
        $this
            ->setDescription('Parse nomenclature file')
            ->addArgument('file', InputArgument::OPTIONAL, 'File url')
            ->addOption('batches', 'b', InputOption::VALUE_REQUIRED, 'How much need save entity batch (every 20 items default)', 20);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $fileSource = $input->getArgument('file');
        $count = intval($input->getOption('batches'));

        $csv = file($fileSource);
        $products = array_slice($csv, 1, count($csv) - 1);
        $progressBar = new ProgressBar($output, count($products));

        $output->writeln(sprintf('Count: %d', count($products)));
        $progressBar->start();

        $errorLines = $duplicates = $usedExternalIds = [];
        foreach ($products as $index => $productLine) {
            $product = str_replace([';;;', "\r\n"], '', $productLine);
            $product = explode(';', $product);

            if (isset($product[8])) {
                $errorLines[] = $productLine;
                continue;
            }
            list ($measure, $price, $paymentObject, $productCode, $vatRate, $externalId, $name, $deleted) = $product;

            if (!$productEntity = $this->productRepository->findOneBy(['externalId' => $externalId])) {
                $productEntity = new Product();
            }

            $productEntity->setName($name);
            $productEntity->setMeasurementUnits($measure);
            $productEntity->setPrice(floatval(str_replace(',', '.', preg_replace('/[^0-9,]/', '', $price))));
            $productEntity->setVatRate(VatRateEnum::getItem($vatRate));
            $productEntity->setPaymentObject(PaymentObjectEnum::getItem($paymentObject));
            $productEntity->setDeleted(mb_strtolower($deleted) === 'да');
            $productEntity->setExternalId($externalId);
            $productCodeEntity = new ProductCode();
            $productCodeEntity->setType(ProductCodeTypeEnum::getItem($productCode));
            $productEntity->setProductCode($productCodeEntity);

            if (isset($usedExternalIds[$externalId])) {
                $duplicates[] = $productLine;
                continue;
            }

            $usedExternalIds[$externalId] = $externalId;
            $this->entityManager->persist($productEntity);

            if ($index % $count === 0) {
                $this->entityManager->flush();
            }

            $progressBar->advance();
        }

        $output->writeln('Count error lines: '.count($errorLines));
//        foreach ($errorLines as $errorLine) {
//            $output->writeln($errorLine);
//        }

        $output->writeln('Count duplicates lines: '.count($duplicates));
//        foreach ($duplicates as $duplicate) {
//            $output->writeln($duplicate);
//        }

        $this->entityManager->flush();
        $progressBar->finish();
        return Command::SUCCESS;
    }
}

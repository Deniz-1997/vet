<?php

namespace App\Command;

use App\Entity\ProductStock;
use App\Entity\Reference\Product;
use App\Repository\Reference\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class QuantityMigrationCommand
 * @package App\Command
 * @deprecated
 */
class QuantityMigrationCommand extends Command
{
    protected static $defaultName = 'app:quantity-migration';

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
        $this->setDescription('Quantity migration command');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $products = $this->productRepository->createQueryBuilder('p')->where('p.stock > 0')->getQuery()
            ->getResult();

        $progressBar = new ProgressBar($output, count($products));

        $output->writeln(sprintf('Count: %d', count($products)));
        $progressBar->start();
        $index = 0;
        $count = 100;
        /** @var Product $product*/
        foreach ($products as $product) {
            $stock = $product->getStock();
            if (!$productStock = $this->entityManager->getRepository(ProductStock::class)->findOneBy(
                ['product' => $product->getId(), 'stock' => $stock->getId()]
            )) {
                $productStock = new ProductStock();
                $this->entityManager->persist($productStock);
            }

            $productStock->setProduct($product);
            $productStock->setStock($stock);
            $productStock->setQuantity($product->getQuantity());

            if ($index % $count === 0) {
                $this->entityManager->flush();
            }

            $progressBar->advance();
            $index++;
        }

        $this->entityManager->flush();
        $progressBar->finish();
        return Command::SUCCESS;
    }
}


<?php

namespace App\Command\Check;

use App\Entity\ProductStock;
use App\Entity\Reference\Product;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Exception\ApiException;

class CheckBalanceCommand extends Command
{
    protected static $defaultName = 'app:check-history-stock-balance';

    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    /**
     * UpdateGroupRolesCommand constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct(self::$defaultName);
    }

    protected function configure()
    {
        $this->setDescription('Calculate summary product history balance and compare with product stock quantity');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sql = 'SELECT dh.product_id as product, dh.stock_id as stock, SUM(dh.quantity) as total_quantity
                    FROM document_history as dh
                    WHERE dh.deleted = false
                    GROUP BY dh.product_id, dh.stock_id';
        $conn = $this->entityManager->getConnection();
        try {
            $stmt = $conn->prepare($sql);
            $fetch = $stmt->executeQuery();
            $result = $fetch->fetchAllAssociative();
        } catch (Exception $e) {
            new ApiException('Database error - '.$e->getMessage(),'',null, 400);
        }

        $balanceDiff = [];
        if (isset($result)) {
            $productStocks = $this->entityManager->getRepository(ProductStock::class)->findAll();
            /** @var ProductStock $productStock */
            foreach ($productStocks as $productStock) {
                $stockId = $productStock->getStock()->getId();
                $productId = $productStock->getProduct()->getId();
                foreach ($result as $item) {
                    if ($item['product'] == $productId && $item['stock'] == $stockId) {
                        $balance = $productStock->getQuantity() - $item['total_quantity'];
                        if ($balance != 0) {
                            if (abs($balance) < 0.000001) break;
                            /** @var Product $product */
                            $product = $this->entityManager->getRepository(Product::class)->findOneBy(['id' => $productId]);
                            isset($product) ? $productName = $product->getName() : $productName = '';
                            $balanceDiff[] = ['product' => $productName ,'product_id' => $productId, 'stock_id' => $stockId, 'balance' => $balance];
                        }
                        break;
                    }
                }
            }
            count($balanceDiff) > 0 ? $output->writeln('Discrepancy detected on the following products:')
                                    : $output->writeln('Verification successful.');
            $i = 1;
            foreach ($balanceDiff as $item) {
                $output->writeln($i++.'. '.$item['product'].', id = '.$item['product_id'].', stock_id = '.$item['stock_id'].', balance = '.$item['balance']);
            }
        }
        return Command::SUCCESS;
    }
}

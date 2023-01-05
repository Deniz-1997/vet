<?php

namespace App\Command;

use App\Entity\ProductStock;
use App\Entity\Reference\Product;
use App\Enum\DocumentOperationTypeEnum;
use App\Enum\DocumentStateEnum;
use App\Service\Document\Document;
use App\Service\Document\DocumentService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use App\Packages\DBAL\Types\PaymentObjectEnum;

/**
 * ВНИМАНИЕ!!! После запуска скрипта, вся история движения по складам будет удалена!!!
 *
 * Скрипт необходимо выполнять только один раз,
 * в случае если в базе есть продукты которые не оприходованы на склад
 *
 *
 * Class ProductReceiptPatchCommand
 */
class ProductReceiptPatchCommand extends Command
{
    protected static $name = 'app:product:receipt:patch';

    /** @var DocumentService */
    private $documentService;

    /** @var EntityManagerInterface */
    private $entityManager;

    protected function configure()
    {
        $this->setDescription('Оприходование товаров которых нет в истории движения по складам, но есть в базе.');
    }

    public function __construct(EntityManagerInterface $entityManager, DocumentService $documentService) {
        parent::__construct(self::$name);
        $this->documentService = $documentService;
        $this->entityManager = $entityManager;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Doctrine\DBAL\Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion(
            'ВНИМАНИЕ!!! Вся история движения по складам будет удалена! Вы уверены? [y/n]',
            false
        );

        if (!$helper->ask($input, $output, $question)) {
            return Command::SUCCESS;
        }


        /** 1) Удаляется вся история со складов */
        foreach (['document_history', 'product.product_receipt'] as $tableName) {
            $this->entityManager->getConnection()->executeUpdate(
                $this->entityManager->getConnection()->getDatabasePlatform()->getTruncateTableSQL($tableName, true)
            );
        }


        /** 2) Выполняется оприходование товаров на склад */
        /** @var Product[] $products */
        $products = $this->entityManager->getRepository(Product::class)->findBy([
            'paymentObject' => PaymentObjectEnum::COMMODITY
        ]);

        foreach ($products as $product) {
            /** @var ProductStock[] $productStocks */
            $productStocks = $this->entityManager->getRepository(ProductStock::class)->findBy([
                'product' => $product
            ]);
            foreach ($productStocks as $productStock) {
                $stock = $productStock->getStock();

                /** @var Document $documentService */
                $documentService = $this->documentService->getDocument(
                    DocumentOperationTypeEnum::getItem(DocumentOperationTypeEnum::IMPORT),
                    $stock
                );

                $documentService->addProduct($product, floatval($productStock->getQuantity()));
                $documentService->setState(DocumentStateEnum::getItem(DocumentStateEnum::REGISTERED));
            }

            if (!count($productStocks)) {
                $output->writeln(sprintf('Нет на складе: %s - %s', $product->getId(), $product->getName()));
            }
        }
        return Command::SUCCESS;
    }
}

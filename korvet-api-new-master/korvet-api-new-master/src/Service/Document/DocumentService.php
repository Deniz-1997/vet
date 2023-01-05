<?php


namespace App\Service\Document;

use App\Entity\Document\DocumentHistory;
use App\Entity\Document\ProductExpense;
use App\Entity\Document\ProductInventory;
use App\Entity\Document\ProductReceipt;
use App\Entity\Document\ProductTransfer;
use App\Entity\Laboratory\ResearchDocument;
use App\Service\Document\DocumentShop;
use App\Entity\Reference\Product;
use App\Entity\Reference\Stock;
use App\Entity\Shop\ShopOrder;
use App\Entity\User\User;
use App\Enum\DocumentOperationTypeEnum;
use App\Interfaces\DocumentExpenseInterface;
use App\Interfaces\DocumentInterface;
use App\Interfaces\DocumentStockInterface;
use App\Interfaces\DocumentTransferInterface;
use App\Interfaces\ServiceDocumentInterface;
use App\Interfaces\DocumentShopInterface;
use App\Interfaces\DocumentResearchInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class DocumentService
 * @package App\Service\Document
 */
class DocumentService
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    /** @var ArrayCollection */
    private ArrayCollection $documents;

    /** @var User */
    private $user = null;

    /** @var TokenStorageInterface */
    private TokenStorageInterface $tokenStorage;


    /**
     * DocumentService constructor.
     * @param EntityManagerInterface $entityManager
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;

        if (null !== $tokenStorage->getToken() && is_object($tokenStorage->getToken()->getUser())) {
            $this->user = $tokenStorage->getToken()->getUser();
        }

        $this->documents = new ArrayCollection();
    }

    /**
     * @param ServiceDocumentInterface $document
     */
    public function removeDocument(ServiceDocumentInterface $document): void
    {
        $this->documents->remove($document->getId());
    }

    /**
     * @param DocumentOperationTypeEnum $operationType
     * @param Stock $stock
     * @param Stock|null $stockTo
     * @return Document
     */
    public function getDocument(DocumentOperationTypeEnum $operationType, Stock $stock, Stock $stockTo = null): ServiceDocumentInterface
    {

        if (null === $stockTo) {
            $id = spl_object_hash($stock);
        } else {
            $id = spl_object_hash($stock) . spl_object_hash($stockTo);
        }

        $document = $this->documents->get($id);
        //если такого нет, то генерим
        if (null === $document) {
            $productsDocument = null;

            if ($operationType->code === DocumentOperationTypeEnum::IMPORT) {
                $productsDocument = new ProductReceipt();
                $productsDocument->setStock($stock);
                $this->entityManager->persist($productsDocument);
            } elseif ($operationType->code === DocumentOperationTypeEnum::INVENTORY) {
                $productsDocument = new ProductInventory();
                $productsDocument->setStock($stock);
                $this->entityManager->persist($productsDocument);
            } elseif (null !== $stockTo && $operationType->code === DocumentOperationTypeEnum::TRANSFER) {
                $productsDocument = new ProductTransfer();
                $productsDocument->setStockFrom($stock);
                $productsDocument->setStockTo($stockTo);
                $this->entityManager->persist($productsDocument);
            } elseif ($operationType->code === DocumentOperationTypeEnum::EXPENSE) {
                $productsDocument = new ProductExpense();
                $productsDocument->setStock($stock);
                $this->entityManager->persist($productsDocument);
            } elseif ($operationType->code === DocumentOperationTypeEnum::SHOP) {
                $productsDocument = new ShopOrder();
                $productsDocument->setStock($stock);
                $this->entityManager->persist($productsDocument);
            } elseif ($operationType->code === DocumentOperationTypeEnum::LABORATORY) {
                $productsDocument = new ResearchDocument();
                $productsDocument->setStock($stock);
                $this->entityManager->persist($productsDocument);
            }
            //TODO: set create user
            //$productsDocument->setCreator($this->tokenStorage->getToken());

            if ($productsDocument instanceof ProductInventory) {
                $document = new DocumentInventory($productsDocument, $this->entityManager, $this);
            } elseif ($productsDocument instanceof DocumentStockInterface) {
                $document = new Document($productsDocument, $this->entityManager, $this);
            } else {
                $document = new DocumentTransfer($productsDocument, $this->entityManager, $this);
            }
            /** @var AbstractDocument $document */
            $document->setCreator($this->user);
            $this->documents->set($id, $document);
        } else {
            /** @var AbstractDocument $document */
            $document->setEditor($this->user);
        }

        return $document;
    }


    /**
     * @param int $number
     * @param string $className
     * @return ServiceDocumentInterface|null
     */
    public function addDocument(int $number, string $className): ?ServiceDocumentInterface
    {
        /** @var DocumentInterface $productsDocument */
        $productsDocument = $this->entityManager->getRepository($className)->find($number);

        if (null === $productsDocument) {
            return null;
        }

        if ($productsDocument instanceof ProductInventory) {
            $document = new DocumentInventory($productsDocument, $this->entityManager, $this);
        } elseif ($productsDocument instanceof DocumentStockInterface) {
            $document = new Document($productsDocument, $this->entityManager, $this);
        } elseif ($productsDocument instanceof DocumentExpenseInterface) {
            $document = new DocumentExpense($productsDocument, $this->entityManager, $this);
        } else if ($productsDocument instanceof DocumentTransferInterface) {
            $document = new DocumentTransfer($productsDocument, $this->entityManager, $this);
        } else if ($productsDocument instanceof DocumentShopInterface) {
            $document = new DocumentShop($productsDocument, $this->entityManager, $this);
        } else if ($productsDocument instanceof DocumentResearchInterface) {
            $document = new DocumentResearch($productsDocument, $this->entityManager, $this);
        } else if ($productsDocument instanceof DocumentInterface) {
            $document = new DocumentAppointment($productsDocument, $this->entityManager, $this);
        } else if ($productsDocument instanceof DocumentInterface) {
            $document = new DocumentLeaving($productsDocument, $this->entityManager, $this);
        } else {
            return null;
        }

        /** @var AbstractDocument $document */
        $document->setEditor($this->user);
        if (!($document instanceof DocumentResearch && $document->getStock() === null)) {
            $this->documents->set($document->getId(), $document);
        }

        return $document;
    }

    /**
     * @param Stock $stock
     * @param Product $product
     * @return float
     */
    public function getQuantityProduct(Stock $stock, Product $product): float
    {
        $select = $this->entityManager->createQueryBuilder()
            ->select('SUM(h.quantity) as sum')
            ->from(DocumentHistory::class, 'h')
            ->where('h.stock = :stock')
            ->andWhere('h.product = :product')
            ->andWhere('h.deleted = false')
            ->getQuery();

        $select->execute([
            'stock' => $stock,
            'product' => $product,
        ]);

        $result = $select->getResult();

        return floatval($result[0]['sum']);
    }


    /**
     *
     */
    public function __destruct()
    {
        if ($this->entityManager->isOpen()) {
            $this->entityManager->flush();
        }
    }

}

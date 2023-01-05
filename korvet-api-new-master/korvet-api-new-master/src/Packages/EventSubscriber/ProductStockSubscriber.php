<?php

namespace App\Packages\EventSubscriber;

use App\Entity\ProductStock;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Packages\EventDispatcher\EventRequest;

class ProductStockSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ProductStockSubscriber constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * @param EventRequest $event
     * @throws \Exception
     */
    public function onChangeProductStock(EventRequest $event) : void
    {
        /** @var ProductStock $productStock */
        $productStock = $event->getData();
        $id = $productStock->getProduct()->getId();

        $count = $this->entityManager->createQueryBuilder()
            ->select('sum(ps.quantity) as sum')
            ->from(ProductStock::class, 'ps')
            ->where('ps.product = :productId')
            ->setParameter('productId', $id)
            ->getQuery()
            ->getScalarResult();

        if (!empty($count[0]['sum']) && $count[0]['sum'] > 0) {
            $productStock->getProduct()->setExistQuantity(true);
        } else {
            $productStock->getProduct()->setExistQuantity(false);
        }

        $this->entityManager->flush();
    }

    /**
     *  TODO УБРАТЬ ПОСЛЕ ТОГО КАК НАЙДЕТСЯ ОШИБКА ПО КОЛИЧЕСТВАМ НА СКЛАДАХ!!!
     *
     * @return string
     */
    private function _getCallerInfo($id)
    {
        $c = date("y:m:d h:i:s") . " Product ID: $id";

        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

        foreach ($trace as $item) {
            if (isset($item['file'])) {
                $c .= basename($item['file']);
            }

            if (isset($item['line'])) {
                $c .= "(" . $item['line'] . ")";
            }

            if (isset($item['function'])) {
                $c .= "->" . $item['function'] . "()";
            }

            if (isset($item['class'])) {
                $c .= " Class: " . $item['class'];
            }

            $c .= "\n";
        }

        return $c . "\n";
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onBeforeProcessAppEntityProductStockPut'  => 'onChangeProductStock',
            'onBeforeProcessAppEntityProductStockPatch'  => 'onChangeProductStock',
            'onAfterProcessAppEntityProductStockPut'  => 'onChangeProductStock',
            'onAfterProcessAppEntityProductStockPatch'  => 'onChangeProductStock',
            'onAfterProcessAppEntityProductStockPost'  => 'onChangeProductStock',
            'onAfterProcessAppEntityProductStockDelete'  => 'onChangeProductStock'
        ];
    }
}

<?php

namespace App\Packages\EventSubscriber;

use App\Entity\ProductStock;
use App\Entity\Reference\Product;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Packages\EventDispatcher\EventRequest;

class ProductSubscriber implements EventSubscriberInterface
{
    // Склад которые добавляется из фильтра.
    // Если находится, значит кол-во товара в этом складе дублируется в самом товаре в Product::$quantity
    private $stock = null;

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * @param EventRequest $event
     */
    public function onBeforeGetProductList(EventRequest $event)
    {
        $this->stock = $event->getData()['filter']['productStock']['stock'] ?? null;
    }

    /**
     * @param EventRequest $event
     */
    public function onAfterGetProductList(EventRequest $event)
    {
        if (!empty($event->getData())) {
            /** @var Product $product */
            foreach ($event->getData() as $product) {
                /** @var ProductStock $productItem */
                foreach ($product->getProductStock() as $productStock) {
                    if ($productStock->getStock()->getId() === $this->stock['id']) {
                        // Дублирование кол-ва товаров на складе в самой сущности товара
                        $product->setQuantity($productStock->getQuantity());
                    }
                }
            }
        }
    }

    /**
     * @param EventRequest $event
     */
    public function onBeforePut(EventRequest $event)
    {
        // При обновлении товара нельзя обновлять кол-во на складах без проведение через документы. Поэтому кол-во
        // на складах удаляется из запроса.
        // https://portal.web-slon.ru/company/personal/user/570/tasks/task/view/12289/
        $data = $event->getData();

        $this->logger->error("Update product: ".$data['content']);

        $product = json_decode($data['content'], true);
        if (isset($product['productStock'])) {
            unset($product['productStock']);
            $data['content'] = json_encode($product);
            $event->setData($data);
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onBeforeProcessAppEntityReferenceProductGet' => 'onBeforeGetProductList',
            'onAfterProcessAppEntityReferenceProductGet' => 'onAfterGetProductList',
            'onBeforeProcessAppEntityReferenceProductPut' => 'onBeforePut',
        ];
    }
}

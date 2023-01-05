<?php

namespace App\Packages\EventSubscriber;

use App\Entity\Shop\ShopOrder;
use App\Entity\Shop\ShopProductItem;
use App\Entity\Appointment\AppointmentLogs;
use App\Entity\ProductStock;
use App\Entity\Reference\Appointment\AppointmentProductItem;
use App\Entity\Reference\Product;
use App\Entity\Reference\Stock;
use App\Entity\User\User;
use App\Enum\DocumentStateEnum;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Packages\EventDispatcher\EventRequest;
use App\Packages\DBAL\Types\PaymentObjectEnum;
use App\Exception\ApiException;
use App\Service\DeserializeService;
use App\Packages\DBAL\Types\PaymentStateEnum;
use App\Packages\DBAL\Types\FiscalReceiptStateEnum;

class ShopOrderSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var User|null */
    private $currentUser = null;
    /** @var DeserializeService */
    private $deserializeService;
    /** @var SerializerInterface */
    private $serializer;
    /** @var String */
    private $beforeState;
    /** @var String */
    private $logger;

    /**
     * AppointmentSubscriber constructor.
     * @param EntityManagerInterface $entityManager
     * @param TokenStorageInterface $tokenStorage
     * @param DeserializeService $deserializeService
     * @param SerializerInterface $serializeService
     * @param LoggerInterface $logger
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        DeserializeService $deserializeService,
        SerializerInterface $serializeService,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $token = $tokenStorage->getToken();
        $this->currentUser = $token ? $token->getUser() : null;
        $this->deserializeService = $deserializeService;
        $this->serializer = $serializeService;
        $this->logger = $logger;
    }

    /**
     * @param EventRequest $event
     * @throws \Exception
     */
    public function onCreateShopOrder(EventRequest $event)
    {
        /** @var ShopOrder $shopOrder */
        $shopOrder = $event->getData();

        foreach ($shopOrder->getDocumentProducts() as $productItem) {
            $productItem->setShopOrder($shopOrder);
            $this->entityManager->persist($productItem);
            $this->entityManager->flush();
        }
    }
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onAfterProcessAppEntityShopShopOrderPost' => 'onCreateShopOrder',
            'onBeforeSaveEntityAppEntityShopShopOrderPatch' => 'onCreateShopOrder',
        ];
    }
}

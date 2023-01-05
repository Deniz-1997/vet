<?php

namespace App\Packages\EventSubscriber;

use App\Packages\EventDispatcher\GetListEvent;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class OrderByAssociationSubscriber
 */
class OrderByAssociationSubscriber implements EventSubscriberInterface
{
    /** @var RequestStack */
    private $requestStack;

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * OrderByAssociationSubscriber constructor.
     * @param RequestStack $requestStack
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->requestStack = $requestStack;
        $this->entityManager = $entityManager;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            GetListEvent::NAME => 'onGetListEvent',
        ];
    }

    /**
     * @param GetListEvent $getListEvent
     */
    public function onGetListEvent(GetListEvent $getListEvent)
    {
        $entityClass = $getListEvent->getEntityClass();
        $entityMetadata = $this->entityManager->getClassMetadata($entityClass);
        $order = json_decode($this->requestStack->getCurrentRequest()->query->get('order'), true) ?? [];
        $items = $getListEvent->getItems();

        foreach ($order as $orderBy => $orderData) {
            if (!is_array($orderData)) {
                continue;
            }

            if (!isset($entityMetadata->associationMappings[$orderBy])) {
                continue;
            }

            $getter = 'get'.ucfirst(mb_strtolower($orderBy));
            $setter = 'set'.ucfirst(mb_strtolower($orderBy));
            foreach ($items as $item) {
                $subItems = $item->{$getter}(); //$action->getItems()
                if (!$subItems instanceof Collection) {
                    $subItems = new ArrayCollection($subItems);
                }
                $subItems = $subItems->matching(Criteria::create()->orderBy($orderData))->getValues();
                $item->{$setter}($subItems);
            }
        }

        $getListEvent->setItems($items);
    }
}

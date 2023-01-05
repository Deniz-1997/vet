<?php

namespace App\Packages\EventSubscriber;

use App\Service\SerializeService;
use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping\PostUpdate;
use App\Packages\AMQP\EventManager;
use App\Packages\AMQP\Router\Route;

/**
 * Class CrudProduceEntitySubscriber
 */
class CrudProduceEntitySubscriber
{
    /** @var EventManager */
    private $eventManager;

    /** @var SerializeService */
    private $serializer;

    /** @var array */
    private $delayedForDelete = [];

    /**
     * CrudProduceEntitySubscriber constructor.
     * @param EventManager $eventManager
     * @param SerializeService $serializer
     */
    public function __construct(EventManager $eventManager, SerializeService $serializer)
    {
        $this->eventManager = $eventManager;
        $this->serializer = $serializer;
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function preRemove(LifecycleEventArgs $event)
    {
        $entity = $event->getObject();
        $this->delayedForDelete[spl_object_hash($entity)] = $entity->getId();
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function postRemove(LifecycleEventArgs $event)
    {
        $entity = $event->getObject();
        if (!isset($this->delayedForDelete[spl_object_hash($entity)])) {
            return;
        }
        $entityId = $this->delayedForDelete[spl_object_hash($entity)];

        if (!$this->eventManager->isSupportEntity(get_class($entity))) {
            return;
        }

        $this->eventManager->pushEvent(Route::TOPIC_DELETE_PREFIX, ['id' => $entityId], get_class($entity));
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function postPersist(LifecycleEventArgs $event)
    {
        $entity = $event->getObject();

        if (!$this->eventManager->isSupportEntity(get_class($entity))) {
            return;
        }

        $this->eventManager->pushEvent(Route::TOPIC_CREATE_PREFIX, $this->getEventData($entity), get_class($entity));
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function postUpdate(LifecycleEventArgs $event)
    {
        $entity = $event->getObject();

        if (!$this->eventManager->isSupportEntity(get_class($entity))) {
            return;
        }

        $this->eventManager->pushEvent(Route::TOPIC_UPDATE_PREFIX, $this->getEventData($entity), get_class($entity));
    }

    /**
     * @param $entity
     * @return array
     */
    private function getEventData($entity)
    {
        return json_decode($this->serializer->serialize($entity, 'json', ['amqp' => true]), true);
    }
}

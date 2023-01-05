<?php

namespace App\Packages\AMQP;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\PsrCachedReader;
use Doctrine\Common\Annotations\Reader;
use PhpAmqpLib\Message\AMQPMessage;
use Ramsey\Uuid\Uuid;
use App\Packages\AMQP\Exception\AmqpFatalException;
use App\Packages\Annotation\Enqueue\CrudProduce;
use App\Packages\Annotation\Enqueue\Produce;

/**
 * Class EventManager
 */
class EventManager
{
    /** @var PsrCachedReader */
    private $annotationReader;

    /** @var Producer */
    private $producer;

    /** @var array */
    private $cache;

    /**
     * EventManager constructor.
     *
     * @param PsrCachedReader $annotationReader
     * @param Producer $producer
     */
    public function __construct(PsrCachedReader $annotationReader, Producer $producer)
    {
        $this->annotationReader = $annotationReader;
        $this->producer = $producer;
    }

    /**
     * @param $entityClass
     * @return bool
     */
    public function isSupportEntity($entityClass)
    {
        /** @var CrudProduce $crudProduceAnnotation */
        $crudProduceAnnotation = $this->findCrudProduceAnnotation($entityClass);

        return $crudProduceAnnotation instanceof CrudProduce;
    }

    public function pushEvent(string $event, $data, string $entityClass)
    {
        /** @var CrudProduce $crudProduceAnnotation */
        $crudProduceAnnotation = $this->findCrudProduceAnnotation($entityClass);
        if (!$crudProduceAnnotation instanceof CrudProduce || !isset($crudProduceAnnotation->topicsMap[$event])) {
            return;
        }

        $packet = new Packet(Uuid::uuid4()->toString(), new \DateTime(), $data);

        /** @var Produce $produce */
        $produce = $crudProduceAnnotation->topicsMap[$event];
        if ($produce instanceof Produce) {
            if ($produce->queue) {
                $this->producer->sendPacket('', $packet, $produce->queue);
            } elseif ($produce->exchange) {
                $this->producer->sendPacket($produce->exchange, $packet, $produce->routingKey ?? '');
            } else {
                throw new AmqpFatalException(sprintf('You must define queue or exchange name for %s event %s', $entityClass, $event));
            }
        } elseif (is_scalar($produce)) {
            $queueName = $produce;
            $this->producer->sendPacket('', $packet, $queueName);
        } else {
            throw new AmqpFatalException(sprintf('Cannot push event %s for class %s, expected @Produce or string queue data', $event, $entityClass));
        }
    }

    /**
     * @param $entityClass
     * @return CrudProduce|null
     */
    private function findCrudProduceAnnotation($entityClass)
    {
        if (!isset($this->cache[$entityClass])) {
            $this->cache[$entityClass] = $this->annotationReader->getClassAnnotation(
                new \ReflectionClass($entityClass),
                CrudProduce::class
            );
        }

        return $this->cache[$entityClass];
    }
}

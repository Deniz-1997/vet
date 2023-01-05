<?php

namespace App\Packages\Consumer;

use App\Interfaces\WebslonConsumer;
use App\Packages\AMQP\Packet;

/**
 * Class AbstractProducer
 */
abstract class AbstractConsumer extends Consumer implements WebslonConsumer
{
    protected string $entityClass;

    /**
     * AbstractProducer constructor.
     *
     * @param string $entityClass
     */
    public function __construct(string $entityClass = "")
    {
        $this->entityClass = $entityClass;
    }

    /**
     * @param Packet $packet
     *
     * @return object|string
     */
    public abstract function process(Packet $packet);
}

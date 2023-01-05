<?php

namespace App\Interfaces;

use Enqueue\Client\ProducerInterface;
use Interop\Queue\Processor;
use PhpAmqpLib\Message\AMQPMessage;
use App\Packages\AMQP\Packet;

/**
 * Interface WebslonProducer
 */
interface WebslonConsumer
{
    /**
     * @param Packet $packet
     * @return object|string
     */
    public function process(Packet $packet);
}

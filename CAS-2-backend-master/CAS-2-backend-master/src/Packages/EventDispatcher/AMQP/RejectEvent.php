<?php

namespace App\Packages\EventDispatcher\AMQP;

use Symfony\Component\EventDispatcher\Event;
use App\Packages\AMQP\Packet;

/**
 * Class RejectEvent
 */
class RejectEvent extends Event
{
    /**
     * @var bool
     */
    private $isRequeue = false;
    /**
     * @var Packet|null
     */
    private $packet;
    /**
     * @var \Exception
     */
    private $exception;

    /**
     * RejectEvent constructor.
     *
     * @param Packet $packet
     */
    public function __construct(Packet $packet, \Exception $exception)
    {
        $this->packet = $packet;
        $this->exception = $exception;
    }

    /**
     * to return message to queue
     * @return bool
     */
    public function isRequeue():bool
    {
        return $this->isRequeue;
    }

    /**
     * @param bool $isRequeue - to return message to queue
     *
     * @return RejectEvent
     */
    public function setIsRequeue(bool $isRequeue):RejectEvent
    {
        $this->isRequeue = $isRequeue;

        return $this;
    }

    /**
     * @return Packet|null
     */
    public function getPacket():?Packet
    {
        return $this->packet;
    }

    /**
     * @param Packet|null $packet
     *
     * @return RejectEvent
     */
    public function setPacket(?Packet $packet):RejectEvent
    {
        $this->packet = $packet;

        return $this;
    }

    /**
     * @return \Exception
     */
    public function getException():\Exception
    {
        return $this->exception;
    }
}

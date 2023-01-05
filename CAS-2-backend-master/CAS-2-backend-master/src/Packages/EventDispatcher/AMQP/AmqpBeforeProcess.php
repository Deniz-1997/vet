<?php

namespace App\Packages\EventDispatcher\AMQP;

use Symfony\Component\EventDispatcher\Event;
use App\Packages\AMQP\Packet;

/**
 * class AmqpBeforeProcess
 */
class AmqpBeforeProcess extends Event
{
    const AMQP_EVENT_BEFORE_PROCESS = 'onAmqpBeforeProcess';
    const AMQP_EVENT_BEFORE_PROCESS_BATCH = 'onAmqpBeforeProcessBatch';
    /**
     * @var Packet
     */
    private $packet;
    /**
     * @var string
     */
    private $type;

    /**
     * AmqpBeforeProcess constructor.
     *
     * @param array  $data
     * @param string $type
     */
    public function __construct(Packet $packet, string $type)
    {
        $this->packet = $packet;
        $this->type = $type;
    }

    /**
     * @return Packet
     */
    public function getPacket():Packet
    {
        return $this->packet;
    }

    /**
     * @param Packet $packet
     *
     * @return AmqpBeforeProcess
     */
    public function setPacket(Packet $packet):AmqpBeforeProcess
    {
        $this->packet = $packet;

        return $this;
    }

    /**
     * @return string
     */
    public function getType():string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return AmqpBeforeProcess
     */
    public function setType(string $type):AmqpBeforeProcess
    {
        $this->type = $type;

        return $this;
    }
}

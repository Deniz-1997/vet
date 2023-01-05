<?php

namespace App\Service\EventDispatcher\Event;

use App\Entity\HistoryEntity;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class HistoryEntityEvent
 */
class HistoryEntityEvent extends Event
{
    /**
     * @var bool
     */
    public bool $isValid = true;

    /** @var HistoryEntity */
    private HistoryEntity $historyEntity;

    /** @var object */
    private object $object;

    /**
     * HistoryEntityEvent constructor.
     *
     * @param HistoryEntity $historyEntity
     * @param object $object - object loggable entity
     */
    public function __construct(HistoryEntity $historyEntity, object $object)
    {
        $this->historyEntity = $historyEntity;
        $this->object = $object;

    }

    /**
     * @return HistoryEntity
     */
    public function getHistoryEntity(): HistoryEntity
    {
        return $this->historyEntity;
    }

    /**
     * @return object
     */
    public function getObject(): object
    {
        return $this->object;
    }
}

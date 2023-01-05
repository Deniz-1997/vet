<?php

namespace App\Packages\EventDispatcher;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class ReplaceItemEvent
 */
class ReplaceItemEvent extends Event
{
    const NAME = 'webslon.api.crud.replace_item';

    /** @var object */
    private $entity;

    /**
     * UpdateItemEvent constructor.
     * @param object $entity
     */
    public function __construct($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return object
     */
    public function getEntity()
    {
        return $this->entity;
    }
}

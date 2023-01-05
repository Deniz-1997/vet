<?php

namespace App\Packages\EventDispatcher;

use Symfony\Component\EventDispatcher\Event;

class AddItemEvent extends Event
{
    const NAME = 'webslon.api.crud.add_item';

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

<?php

namespace App\Packages\EventDispatcher;

//use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\Event;

class UpdateItemEvent extends Event
{
    const NAME = 'webslon.api.crud.update_item';

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

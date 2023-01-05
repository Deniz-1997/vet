<?php

namespace App\Interfaces;


interface DTOControllerInterface
{
    /** @return object */
    public function createEntity(): object;

    /**
     * @param object $entity
     */
    public function loadEntity(object $entity);
}

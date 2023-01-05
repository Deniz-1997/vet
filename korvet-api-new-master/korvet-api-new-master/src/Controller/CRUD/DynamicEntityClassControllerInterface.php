<?php

namespace App\Controller\CRUD;

/**
 * Interface DynamicEntityClassControllerInterface
 */
interface DynamicEntityClassControllerInterface
{
    /**
     * @return string
     */
    public function getEntityClass() : string;
}

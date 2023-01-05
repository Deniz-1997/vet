<?php

namespace App\Packages\Utils;

/**
 * Class CircularReferenceHandler
 */
class CircularReferenceHandler
{
    /**
     * @param object $object
     *
     * @return mixed
     */
    public function __invoke(object $object)
    {
        return $object->getId();
    }
}

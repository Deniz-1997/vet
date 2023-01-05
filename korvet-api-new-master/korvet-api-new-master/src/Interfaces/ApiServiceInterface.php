<?php

namespace App\Interfaces;

use App\Packages\EventDispatcher\EventRequest;

/**
 * Interface ApiServiceInterface
 */
interface ApiServiceInterface
{
    /**
     * @param mixed $data
     * @param array $eventsList
     *
     * @return EventRequest
     */
    public function generateEvent($data, array $eventsList = []): EventRequest;
}

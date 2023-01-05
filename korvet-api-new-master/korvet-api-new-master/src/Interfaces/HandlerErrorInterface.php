<?php

namespace App\Interfaces;

/**
 * Interface HandlerErrorInterface
 */
interface HandlerErrorInterface
{
    /**
     * @return boolean
     */
    public function supportException(): bool;
    
    /**
     * @return ApiResponseInterface
     */
    public function handle(): ApiResponseInterface;
    
    /**
     * @return integer
     */
    public function getStatusCode(): int;
    
    /**
     * @return array
     */
    public function getHeaders(): array;
}

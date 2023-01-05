<?php

namespace App\Interfaces;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Interface DefaultExceptionInterface
 */
interface DefaultListenerExceptionInterface
{
    /**
     * @param array $handlers
     * @return mixed
     */
    public function setHandlers(array $handlers);
    
    /**
     * @return array
     */
    public function getHandlers(): array;
    
    /**
     * @param array $handlers
     * @return mixed
     */
    public function addHandlers(array $handlers);
    
    /**
     * @return array
     */
    public function getDefaulthandlers(): array;
    
    /**
     * @return mixed
     */
    public function getException();
    
    /**
     * @return ApiResponseInterface
     */
    public function getResponse(): ApiResponseInterface;
    
    /**
     * @return boolean
     */
    public function isProd(): bool;
    
    /**
     * @return boolean
     */
    public function isDev(): bool;
    
    /**
     * @return boolean
     */
    public function isTest(): bool;
    
    /**
     * @return string
     */
    public function getRequestId(): string;
    
    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface;
    
    /**
     * @param ApiResponseInterface $response
     * @return ApiResponseInterface
     */
    public function addDirtyResponse(ApiResponseInterface $response): ApiResponseInterface;
    
    /**
     * @return TranslatorInterface
     */
    public function getTranslator(): TranslatorInterface;
}

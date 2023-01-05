<?php

namespace App\Packages\EventDispatcher\GatewayConnectorEvents;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class EventRegisterQueue
 */
class EventRegisterQueue extends Event
{
    /**
     * @var int
     */
    private $queueId;
    /**
     * @var string
     */
    private $route;
    
    /**
     * @var string
     */
    private $method;
    
    /**
     * @var array
     */
    private $options;
    
    /**
     * @var array|null
     */
    private $errorData;
    /**
     * @var bool
     */
    private $isRegisterQueue = false;
    /**
     * @var array
     */
    private $resultRequest;
    
    /**
     * EventRegisterQueue constructor.
     *
     * @param string     $route
     * @param string     $method
     * @param array      $options
     * @param array|null $errorData
     */
    public function __construct(string $route, string $method, array $options, ?array $errorData = null)
    {
        $this->route = $route;
        $this->method = $method;
        $this->options = $options;
        $this->errorData = $errorData;
    }
    
    /**
     * @param int $id
     */
    public function setQueueId(int $id)
    {
        $this->queueId = $id;
        $this->isRegisterQueue = true;
    }
    
    /**
     * @return int
     */
    public function getQueueId()
    {
        return $this->queueId;
    }
    
    /**
     * @return bool
     */
    public function isRegisterQueue()
    {
        return $this->isRegisterQueue;
    }
    
    /**
     * @param array|null $resultRequest
     */
    public function setResultRequest(?array $resultRequest)
    {
        $this->resultRequest = $resultRequest;
    }
    
    /**
     * @return array
     */
    public function getResultRequest()
    {
        return $this->resultRequest;
    }
    
    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            'route' => $this->route,
            'method' => $this->method,
            'options' => $this->options,
            'errorData' => $this->errorData,
        ];
    }
}

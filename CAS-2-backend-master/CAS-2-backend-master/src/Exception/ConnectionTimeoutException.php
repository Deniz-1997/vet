<?php

namespace App\Exception;

/**
 * Class ConnectionTimeoutException
 */
class ConnectionTimeoutException extends \RuntimeException
{
    /**
     * @var string|null
     */
    private $serviceName;

    /**
     * @return string|null
     */
    public function getServiceName():?string
    {
        return $this->serviceName;
    }

    /**
     * @param string|null $serviceName
     *
     * @return ConnectionTimeoutException
     */
    public function setServiceName(?string $serviceName)
    {
        $this->serviceName = $serviceName;

        return $this;
    }
}

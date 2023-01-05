<?php


namespace App\Packages\AMQP\Exception;

use App\Exception\ApiException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AmqpNotFoundProcessEntityException
 */
class AmqpNotFoundEntityException extends ApiException
{
    private $entityId;

    /**
     * @return mixed
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * @param mixed $entityId
     *
     * @return AmqpNotFoundEntityException
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;

        return $this;
    }
}

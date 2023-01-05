<?php

namespace App\Service\EventDispatcher\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use App\Exception\DenormalizeCollectionException;

/**
 * Class DeserializeEvent
 */
class DeserializeEvent extends Event
{
    /**
     * @var object
     */
    private object $entity;
    /**
     * @var array
     */
    private array $data = [];
    /**
     * @var array
     */
    private array $serializerExceptions = [];
    /**
     * @var ConstraintViolationListInterface
     */
    private ?ConstraintViolationListInterface $validationException=null;

    /**
     * DeserializeEvent constructor.
     *
     * @param object $entity
     * @param array $data
     * @param array $serializerExceptions
     */
    public function __construct($entity, array $data, array $serializerExceptions)
    {
        $this->entity = $entity;
        $this->data = $data;
        $this->serializerExceptions = $serializerExceptions;
    }

    /**
     * @return object
     */
    public function getEntity(): object
    {
        return $this->entity;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return array|DenormalizeCollectionException[]
     */
    public function getSerializerException(): array
    {
        return $this->serializerExceptions;
    }

    /**
     * @return ConstraintViolationListInterface|null
     */
    public function getValidationException(): ?ConstraintViolationListInterface
    {
        return $this->validationException;
    }

    /**
     * @param ConstraintViolationListInterface $validationException
     */
    public function setValidationException(ConstraintViolationListInterface $validationException)
    {
        $this->validationException = $validationException;
    }
}

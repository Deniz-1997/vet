<?php

namespace App\Packages\EventDispatcher;

use Symfony\Contracts\EventDispatcher\Event;

class GetItemEvent extends Event
{
    const NAME = 'webslon_api.crud.get_item';

    /** @var object */
    private $item;

    /** @var string */
    private $entityClass;

    /** @var array */
    private $response = [];

    /** @var array */
    private $serializationContext = [];

    /**
     * GetItemEvent constructor.
     * @param object $item
     * @param string $entityClass
     * @param array $serializationContext
     */
    public function __construct($item, string $entityClass, array $serializationContext)
    {
        $this->item = $item;
        $this->entityClass = $entityClass;
        $this->serializationContext = $serializationContext;
    }

    /**
     * @return object
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param object $item
     * @return $this
     */
    public function setItem($item): self
    {
        $this->item = $item;

        return $this;
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    /**
     * @param string $entityClass
     * @return $this
     */
    public function setEntityClass(string $entityClass): self
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    /**
     * @return array
     */
    public function getResponse(): array
    {
        return $this->response;
    }

    /**
     * @param array $response
     * @return $this
     */
    public function setResponse(array $response): self
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return array
     */
    public function getSerializationContext(): array
    {
        return $this->serializationContext;
    }
}

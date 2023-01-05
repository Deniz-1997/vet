<?php

namespace App\Packages\EventDispatcher;

use Symfony\Contracts\EventDispatcher\Event;

class GetListEvent extends Event
{
    const NAME = 'webslon_api.crud.get_list';

    /** @var array */
    private $items;

    /** @var string */
    private $entityClass;

    /** @var array */
    private $response = [];

    /**
     * GetListEvent constructor.
     * @param array $items
     * @param string $entityClass
     */
    public function __construct(array $items, string $entityClass)
    {
        $this->items = $items;
        $this->entityClass = $entityClass;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array $items
     * @return $this
     */
    public function setItems(array $items): self
    {
        $this->items = $items;

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
}

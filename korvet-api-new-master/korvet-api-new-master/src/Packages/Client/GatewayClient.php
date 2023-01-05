<?php

namespace App\Packages\Client;

use App\Exception\ApiException;
use GuzzleHttp\Exception\GuzzleException;

class GatewayClient extends AbstractClient
{
    /** @var string|null */
    protected ?string $id;

    /** @var array|null */
    protected ?array $filter;

    /** @var array|null */
    protected ?array $fields;

    /** @var array|null */
    protected ?array $order;

    /** @var int */
    protected int $offset = 0;

    /** @var int */
    protected int $limit = 20;

    /**
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param null|string $id
     *
     * @return GatewayClient
     */
    public function setId(?string $id): GatewayClient
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getFilter(): ?array
    {
        return $this->filter;
    }

    /**
     * @param array|null $filter
     *
     * @return GatewayClient
     */
    public function setFilter(?array $filter): GatewayClient
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getFields(): ?array
    {
        return $this->fields;
    }

    /**
     * @param array|null $fields
     *
     * @return GatewayClient
     */
    public function setFields(?array $fields): GatewayClient
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getOrder(): ?array
    {
        return $this->order;
    }

    /**
     * @param array|null $order
     *
     * @return GatewayClient
     */
    public function setOrder(?array $order): GatewayClient
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     *
     * @return GatewayClient
     */
    public function setOffset(int $offset): GatewayClient
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     *
     * @return GatewayClient
     */
    public function setLimit(int $limit): GatewayClient
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return self
     */
    public function clear(): self
    {
        parent::clear();
        $this->fields = null;
        $this->filter = null;
        $this->id = null;
        $this->limit = 20;
        $this->offset = 0;
        $this->order = null;

        return $this;
    }

    /**
     * @param bool $isAsync
     *
     * @return array
     * @throws GuzzleException
     */
    public function requestCrudGetList($isAsync = false): array
    {
        if (is_array($filter = $this->getFilter())) $this->setQueryValue('filter', json_encode($filter));
        if (is_array($fields = $this->getFields())) $this->setQueryValue('fields', json_encode($fields));
        if (is_array($order = $this->getOrder())) $this->setQueryValue('order', json_encode($order));

        $this
            ->setMethod('GET')
            ->setQueryValue('offset', $this->getOffset())
            ->setQueryValue('limit', $this->getLimit());

        if ($isAsync) {
            return $this->requestAsync();
        }

        return $this->request();
    }

    /**
     * @param bool $isAsync
     *
     * @return array|null
     * @throws GuzzleException
     */
    public function requestCrudGet($isAsync = false): ?array
    {
        if (is_array($fields = $this->getFields())) $this->setQueryValue('fields', json_encode($fields));

        $this
            ->setMethod('GET')
            ->setRouteSuffix($this->getId() . '/');

        if ($isAsync) {
            return $this->requestAsync();
        }

        return $this->request();
    }

    /**
     * @param bool $isAsync
     *
     * @return array|null
     * @throws GuzzleException
     */
    public function requestCrudPost($isAsync = false): ?array
    {
        $this
            ->setMethod('POST');

        if ($isAsync) {
            return $this->requestAsync();
        }

        return $this->request();
    }

    /**
     * @param bool $isAsync
     *
     * @return array|null
     * @throws GuzzleException
     */
    public function requestCrudPatch($isAsync = false): ?array
    {
        $this
            ->setMethod('PATCH')
            ->setRouteSuffix($this->getId() . '/');

        if ($isAsync) {
            return $this->requestAsync();
        }

        return $this->request();
    }

    /**
     * @param bool $isAsync
     *
     * @return array|null
     * @throws GuzzleException
     */
    public function requestCrudPut($isAsync = false): ?array
    {
        $this
            ->setMethod('PUT')
            ->setRouteSuffix($this->getId() . '/');

        if ($isAsync) {
            return $this->requestAsync();
        }

        return $this->request();
    }

    /**
     * @param bool $isAsync
     *
     * @return array|null
     * @throws GuzzleException
     */
    public function requestCrudDelete($isAsync = false): ?array
    {
        $this
            ->setMethod('DELETE')
            ->setRouteSuffix($this->getId() . '/');
        if ($isAsync) {
            return $this->requestAsync();
        }

        return $this->request();
    }
}

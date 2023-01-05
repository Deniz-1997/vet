<?php

namespace App\Service\Test\ValueObject;

class RequestData
{
    /**
     * @var integer|null
     */
    private $id;

    /**
     * @var string
     */
    private $route;

    public function __construct(string $route, int $id = null)
    {
        $this->id = $id;
        $this->route = $route;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }
}

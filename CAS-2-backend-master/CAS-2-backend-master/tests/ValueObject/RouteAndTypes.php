<?php

namespace App\Service\Test\ValueObject;

class RouteAndTypes
{
    /**
     * @var string
     */
    private $route;
    /**
     * @var array
     */
    private $expectedFieldsAndTypes;

    public function __construct(string $route, array $expectedFieldsAndTypes)
    {
        $this->route = $route;
        $this->expectedFieldsAndTypes = $expectedFieldsAndTypes;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @return array
     */
    public function getExpectedFieldsAndTypes(): array
    {
        return $this->expectedFieldsAndTypes;
    }
}

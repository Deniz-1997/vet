<?php

namespace App\Service\Test\CRUD;

use GuzzleHttp\Exception\GuzzleException;
use App\Service\Test\ValueObject\RouteAndTypes;

class GetByIdTest extends NewlyCreatedTest
{
    /**
     * @var RouteAndTypes
     */
    private $routeAndTypes;

    /**
     * @var integer|null
     */
    private $id;

    public function __construct(RouteAndTypes $routeAndTypes, ?int $id = null)
    {
        parent::__construct();
        $this->routeAndTypes = $routeAndTypes;
        $this->id = $id;
    }

    /**
     * @throws GuzzleException
     */
    public function testExecute(): void
    {
        $result = $this->gatewayClient
            ->setRoute($this->routeAndTypes->getRoute())
            ->setId($this->getCreatedId() ?? $this->id)
            ->requestCrudGet();

        (new CheckFields($result['response']))
            ->checkTypes($this->routeAndTypes->getExpectedFieldsAndTypes());
    }
}

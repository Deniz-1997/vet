<?php

namespace App\Service\Test\CRUD;

use GuzzleHttp\Exception\GuzzleException;
use App\Service\Test\ValueObject\RouteAndTypes;

class GetListTest extends AbstractCrudTest
{
    /**
     * @var RouteAndTypes
     */
    private $routeAndTypes;

    public function __construct(RouteAndTypes $routeAndTypes)
    {
        parent::__construct();
        $this->routeAndTypes = $routeAndTypes;
    }

    /**
     * @throws GuzzleException
     */
    public function testExecute(): void
    {
        $result = $this->gatewayClient
            ->setRoute($this->routeAndTypes->getRoute())
            ->requestCrudGetList();

        foreach ($result['response']['items'] as $item) {
            (new CheckFields($item))
                ->checkTypes($this->routeAndTypes->getExpectedFieldsAndTypes());
        }
    }
}

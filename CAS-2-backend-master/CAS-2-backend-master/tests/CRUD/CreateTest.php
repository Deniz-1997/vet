<?php

namespace App\Service\Test\CRUD;

use App\Service\Test\ValueObject\RouteAndTypes;

class CreateTest extends AbstractCrudTest
{
    /**
     * @var RouteAndTypes
     */
    private $routeAndTypes;

    /**
     * @var array
     */
    private $data;

    /**
     * @var integer
     */
    private $id;

    public function __construct(RouteAndTypes $routeAndTypes, array $data)
    {
        parent::__construct();
        $this->routeAndTypes = $routeAndTypes;
        $this->data = $data;
    }

    public function testExecute(): void
    {
        $result = $this->encodedBody(
            $this->routeAndTypes->getRoute(),
            $this->data,
            null
        )->requestCrudPost();

        $check = new CheckFields($result['response']);
        $check->checkSame($this->data);
        $check->checkTypes($this->routeAndTypes->getExpectedFieldsAndTypes());

        $this->id = $result['response']['id'];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}

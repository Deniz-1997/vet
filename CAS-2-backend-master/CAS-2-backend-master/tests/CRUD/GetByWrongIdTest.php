<?php

namespace App\Service\Test\CRUD;

use GuzzleHttp\Exception\GuzzleException;

class GetByWrongIdTest extends AbstractCrudTest
{
    /**
     * @var string
     */
    private $route;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $message;

    public function __construct(string $route, int $id, string $message)
    {
        parent::__construct();
        $this->route = $route;
        $this->id = $id;
        $this->message = $message;
    }

    /**
     * @throws GuzzleException
     */
    public function testExecute(): void
    {
        $result = $this->gatewayClient
            ->setRoute($this->route)
            ->setId($this->id)
            ->requestCrudGet();

        WrongIdTest::expected($result, 404, $this->message);
    }
}

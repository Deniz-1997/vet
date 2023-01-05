<?php

namespace App\Service\Test\CRUD;

use GuzzleHttp\Exception\GuzzleException;

class UpdateByWrongIdTest extends AbstractCrudTest
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

    /**
     * @var bool
     */
    private $patch;

    public function __construct(
        string $route,
        int $id,
        string $message,
        bool $patch = false
    ) {
        parent::__construct();
        $this->route = $route;
        $this->id = $id;
        $this->message = $message;
        $this->patch = $patch;
    }

    /**
     * @throws GuzzleException
     */
    public function testExecute(): void
    {
        $result = $this->gatewayClient
            ->setRoute($this->route)
            ->setId($this->id);

        WrongIdTest::expected(
            true === $this->patch
                ? $result->requestCrudPatch()
                : $result->requestCrudPut(),
            404,
            $this->message
        );
    }
}

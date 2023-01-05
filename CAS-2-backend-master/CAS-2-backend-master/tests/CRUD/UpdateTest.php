<?php

namespace App\Service\Test\CRUD;

use App\Service\Test\ValueObject\RouteAndTypes;

class UpdateTest extends NewlyCreatedTest
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
     * @var bool
     */
    private $patch;

    /**
     * @var int
     */
    private $id;

    /**
     * @var bool
     */
    private $forceId;

    public function __construct(
        RouteAndTypes $routeAndTypes,
        array $data,
        bool $patch = false,
        int $id = null,
        bool $forceId = false
    ) {
        parent::__construct();
        $this->routeAndTypes = $routeAndTypes;
        $this->data = $data;
        $this->patch = $patch;
        $this->id = $id;
        $this->forceId = $forceId;
    }

    public function testExecute(): void
    {
        $id = $this->forceId ? $this->id :
            $this->getCreatedId() ?? $this->id;

        $result = $this->encodedBody(
            $this->routeAndTypes->getRoute(),
            $this->data,
            $id
        );

        $result = true === $this->patch
            ? $result->requestCrudPatch()
            : $result->requestCrudPut();

        $check = new CheckFields($result['response']);
        $check->checkSame($this->data);
        $check->checkTypes($this->routeAndTypes->getExpectedFieldsAndTypes());
    }
}

<?php

namespace App\Service\Test\CRUD;

use GuzzleHttp\Exception\GuzzleException;
use App\Service\Test\ValueObject\RequestData;

class DeleteTest extends NewlyCreatedTest
{
    /**
     * @var RequestData
     */
    private $requestData;


    public function __construct(RequestData $requestData)
    {
        parent::__construct();

        $this->requestData = $requestData;
    }

    /**
     * @throws GuzzleException
     */
    public function testExecute(): void
    {
        $result = $this->gatewayClient
            ->setRoute($this->requestData->getRoute())
            ->setId($this->getCreatedId() ?? $this->requestData->getId())
            ->requestCrudDelete();

        static::assertTrue($result['status']);
        static::assertNull($result['errors']);
        static::assertSame(200, $result['httpResponseCode']);
    }
}

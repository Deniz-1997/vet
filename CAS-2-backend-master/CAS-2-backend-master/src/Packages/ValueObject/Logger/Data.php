<?php

namespace App\Packages\ValueObject\Logger;

use DateTime;
use DateTimeImmutable;

class Data
{
    /**
     * @var Request
     */
    private Request $request;

    /**
     * @var string
     */
    private string $response;

    /**
     * @var string
     */
    private string $requestDate;

    /**
     * @var string
     */
    private string $responseDate;

    public function __construct(Request $request, string $response, DateTimeImmutable $requestDate, DateTimeImmutable $responseDate) {
        $this->request = $request;
        $this->response = $response;
        $this->requestDate = $requestDate->format(DateTime::ATOM);
        $this->responseDate = $responseDate->format(DateTime::ATOM);
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return string
     */
    public function getResponse(): string
    {
        return $this->response;
    }

    /**
     * @return string
     */
    public function getRequestDate(): string
    {
        return $this->requestDate;
    }

    /**
     * @return string
     */
    public function getResponseDate(): string
    {
        return $this->responseDate;
    }
}

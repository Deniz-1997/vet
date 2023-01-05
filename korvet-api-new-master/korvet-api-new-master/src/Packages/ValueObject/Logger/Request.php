<?php

namespace App\Packages\ValueObject\Logger;

class Request
{
    /**
     * @var string
     */
    private string $uri;

    /**
     * @var string
     */
    private string $method;

    /**
     * @var array
     */
    private array $parameters;

    /**
     * @var array
     */
    private array $body;

    public function __construct(string $uri, string $method, array $parameters, array $body) {
        $this->uri = $uri;
        $this->method = $method;
        $this->parameters = $parameters;
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return array
     */
    public function getBody(): array
    {
        return $this->body;
    }
}

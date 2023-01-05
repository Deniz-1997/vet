<?php

namespace App\Service\Request;

use SoapClient;
use SoapFault;

class Soap
{
    /**
     * @var SoapClient
     */
    private SoapClient $client;

    /**
     * @var string
     */
    private string $method;

    /**
     * @var array
     */
    private array $params;

    /**
     * @var array
     */
    private array $options;

    /**
     * Soap constructor.
     * @param string $url
     * @param string $method
     * @param array $params
     * @param array $options
     * @throws SoapFault
     */
    public function __construct(string $url, string $method, array $params, array $options = []) {
        $defaultOptions = [
            'trace' => true,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'compression' => SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_ACCEPT,
            'encoding' => 'UTF-8',
            'exceptions' => true,
            'soap_version' => SOAP_1_1,
            'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
            'use' => SOAP_LITERAL,
            'style' => SOAP_DOCUMENT,
        ];

        $this->options = array_merge($defaultOptions, $options);

        $this->client = new SoapClient($url, $this->options);
        $this->method = $method;
        $this->params = $params;
    }

    /**
     * @return object
     */
    public function call(): object
    {
        return $this->client->{$this->method}($this->params);
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}

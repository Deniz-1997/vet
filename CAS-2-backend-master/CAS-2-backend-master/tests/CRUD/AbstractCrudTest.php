<?php

namespace App\Service\Test\CRUD;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\GatewayConnector\AbstractClient;
use App\GatewayConnector\GatewayClient;

abstract class AbstractCrudTest extends WebTestCase
{
    /**
     * @var GatewayClient
     */
    protected $gatewayClient;

    public function __construct()
    {
        parent::__construct(null, [], '');

        $this->gatewayClient = static::createClient()
            ->getContainer()
            ->get(GatewayClient::class);
    }

    /**
     * @param string $route
     * @param array $body
     * @param int|null $id
     * @return AbstractClient
     */
    public function encodedBody(
        string $route,
        array $body,
        int $id = null
    ): AbstractClient {
        $gateway = $this->gatewayClient;

        if (null !== $id) {
            $gateway->setId($id);
        }

        $gateway->setRoute($route)
            ->setBody(json_encode($body));

        return $gateway;
    }

    abstract public function testExecute(): void;
}

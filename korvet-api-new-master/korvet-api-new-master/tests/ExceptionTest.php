<?php

namespace App\Tests;

use App\Tests\Base\BaseCrudTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ExceptionTest
 */
class ExceptionTest extends BaseCrudTestCase
{
    public static $actions = [
        'pickingStart',
    ];
    public $isGateway = true;
    
    public function setUp()
    {
        parent::setUp();
    }
    
    public function testExceptionWithGatewayClient()
    {
        $orderNumberNotExist = (string) mt_rand(1525454, 256598989);
        foreach (self::$actions as $actionName) {
            $route = '/oms/order/' . $orderNumberNotExist . '/action/' . $actionName;
            $response = $this->gatewayClient
                ->setRoute($route)
                ->setBody(['orderNumber' => '78878787'])
                ->requestCrudPost();
            
            self::assertInternalType('array', $response['errors']);
            self::assertTrue(is_array($response['response']) || is_null($response['response']));
            self::assertTrue(false === $response['status']);
            self::assertTrue($response['httpResponseCode'] == Response::HTTP_BAD_REQUEST, ' actual: ' . $response['httpResponseCode']);
            self::assertEquals('Ошибка сравнения номера заказа, номер заказа в УРЛ должен совпадать с номером заказа в BODY.', $response['errors'][0]['message']);
            self::assertInternalType('string', $response['errors'][0]['stringCode']);
            self::assertInternalType('string', $response['errors'][0]['relatedField']);
            self::assertInternalType('string', $response['requestId']);
        }
    }
    
    public function testExceptionWithClient()
    {
        $orderNumberNotExist = (string) mt_rand(1525454, 256598989);
        
        foreach (self::$actions as $actionName) {
            $route = '/api/order/' . $orderNumberNotExist . '/action/' . $actionName . '/';
            $this->client
                ->request(Request::METHOD_POST, $route, [], [], $this->headers, json_encode(['orderNumber' => '78878787']));
            $response = $this->client->getResponse();
            $content = json_decode($response->getContent(), true);
            self::assertTrue(false === $content['status']);
            self::assertInternalType('array', $content['errors']);
            self::assertTrue($response->getStatusCode() === Response::HTTP_BAD_REQUEST, 'actual ' . $response->getStatusCode());
            self::assertEquals('Bad request, failure compare order number', $content['errors'][0]['message']);
            self::assertInternalType('string', $content['errors'][0]['file']);
            self::assertInternalType('integer', $content['errors'][0]['line']);
            self::assertNull($content['requestId']);
            self::assertNull($content['response']);
        }
    }
    
    public function testExceptionWithClient500Actual400()
    {
        $orderNumberNotExist = (string) mt_rand(1525454, 256598989);
        foreach (self::$actions as $actionName) {
            $route = '/api/order/' . $orderNumberNotExist . '/action/' . $actionName . '/';
            $this->client
                ->request(Request::METHOD_POST, $route, [], [], $this->headers, json_encode(['orderNumber' => 78878787]));
            $response = $this->client->getResponse();
            $content = json_decode($response->getContent(), true);
            self::assertTrue(false === $content['status']);
            self::assertInternalType('array', $content['errors']);
            self::assertTrue($response->getStatusCode() === Response::HTTP_BAD_REQUEST, 'actual ' . $response->getStatusCode());
            self::assertEquals('The type of the "orderNumber" attribute must be one of "string" ("integer" given).', $content['errors'][0]['message']);
            self::assertInternalType('string', $content['errors'][0]['file']);
            self::assertInternalType('integer', $content['errors'][0]['line']);
            self::assertNull($content['requestId']);
            self::assertNull($content['response']);
        }
    }
}

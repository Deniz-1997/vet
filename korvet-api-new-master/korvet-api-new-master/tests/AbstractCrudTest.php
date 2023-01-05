<?php

namespace App\Tests;

use App\Packages\Client\GatewayClient;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class AbstractCrudTest
 * @package App\\Tests
 * @deprecated use  App\\Service\Test\CRUD\AbstractCrudTest instead.
 */
abstract class AbstractCrudTest extends WebTestCase
{
    /**
     * @var GatewayClient
     */
    protected $gatewayClient;

    public function __construct()
    {
        parent::__construct(null, []);

        $this->gatewayClient = static::createClient()
            ->getContainer()
            ->get(GatewayClient::class);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetWithCorrectId(): void
    {
        $result = $this->gatewayClient
            ->setRoute(static::path())
            ->setId(static::id())
            ->requestCrudGet();

        $this->expectedType($result['response'], static::entity());
    }

    /**
     * @throws GuzzleException
     */
    public function testGetWithWrongId(): void
    {
        $id = 9884661;

        $result = $this->gatewayClient
            ->setRoute(static::path())
            ->setId($id)
            ->requestCrudGet();

        static::errorResult(
            $result,
            static::error(
                static::getByWrongIdMessage($id)
            ),
            404
        );
    }

    /**
     * @throws GuzzleException
     */
    public function testGetList(): void
    {
        $result = $this->gatewayClient
            ->setRoute(static::path())
            ->setLimit(3)
            ->requestCrudGetList();

        static::assertTrue($result['status']);

        $this->expectedTypes(
            $result['response']['items'],
            static::entity()
        );
    }

    /**
     * @throws GuzzleException
     */
    public function testUpdateWithWrongId(): void
    {
        $id = 9884661;

        $result = $this->gatewayClient
            ->setRoute(static::path())
            ->setId($id)
            ->requestCrudPut();

        static::errorResult(
            $result,
            static::error(
                static::updateByWrongIdMessage($id)
            ),
            404
        );
    }

    /**
     * @throws GuzzleException
     */
    public function testDelete(): void
    {
        $result = $this->gatewayClient
            ->setId(static::id())
            ->setRoute(static::path())
            ->requestCrudDelete();

        static::assertTrue($result['status']);
        static::assertNull($result['errors']);
    }

    /**
     * @throws GuzzleException
     */
    public function testDeleteWithWrongId(): void
    {
        $expected = [
            'message' => 'Internal server error',
            'stringCode' => '500',
            'relatedField' => '',
        ];

        $result = $this->gatewayClient
            ->setId(99989888)
            ->setRoute(static::path())
            ->requestCrudDelete();

        static::errorResult($result, $expected, 500);
    }

    /**
     * @param array $body
     * @param int|null $id
     * @return AbstractClient
     */
    public function encodedBody(array $body, int $id = null): AbstractClient
    {
        $gateway = $this->gatewayClient;

        if (null !== $id) {
            $gateway->setId($id);
        }

        $gateway->setRoute(static::path())
            ->setBody(json_encode($body));

        return $gateway;
    }

    /**
     * @param array $data
     * @param array $types
     */
    public function expectedType(array $data, array $types): void
    {
        foreach ($types as $key => $type) {
            if (\is_callable($type)) {
                $type($data[$key]);
            } elseif (\count($explode = explode('||', $type)) >= 2) {
                $this->assertThat($data[$key], $this->logicalOr(
                    $this->isType($explode[0]),
                    $this->isType($explode[1])
                ));
            } else {
                static::assertInternalType($type, $data[$key]);
            }
        }
    }

    /**
     * @param array $items
     * @param array $types
     */
    public function expectedTypes(array $items, array $types): void
    {
        foreach ($items as $item) {
            $this->expectedType($item, $types);
        }
    }

    /**
     * @param string $message
     * @return array
     */
    public static function error(string $message): array
    {
        return [
            'message' => $message,
            'stringCode' => 'Error_404',
            'relatedField' => '',
        ];
    }

    /**
     * @param array $result
     * @param array $expected
     * @param int $expectedCode
     */
    public static function errorResult(
        array $result,
        array $expected,
        int $expectedCode
    ): void {
        static::assertFalse($result['status']);
        static::assertSame($expectedCode, $result['httpResponseCode']);
        static::assertSame($expected, $result['errors'][0]);
    }

    /**
     * @return string
     */
    abstract public static function path(): string;

    /**
     * @return int
     */
    abstract public static function id(): int;

    /**
     * @return array
     */
    abstract public static function entity(): array;

    /**
     * @param int $id
     * @return string
     */
    abstract public static function getByWrongIdMessage(int $id): string;

    /**
     * @param int $id
     * @return string
     */
    abstract public static function updateByWrongIdMessage(int $id): string;
}

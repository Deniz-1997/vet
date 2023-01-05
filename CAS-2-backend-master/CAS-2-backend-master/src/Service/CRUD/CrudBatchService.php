<?php


namespace App\Service\CRUD;

use App\Interfaces\CRUD\CrudBatchServiceInterface;
use Exception;
use Ramsey\Uuid\Uuid;
use App\Packages\AMQP\Packet;
use App\Packages\AMQP\Producer;
use App\Packages\AMQP\Router\Route;
use App\Packages\AMQP\RPC\Exchange;
use App\Packages\Response\BaseResponse;
use Doctrine\Common\Annotations\AnnotationReader;
use App\Packages\Annotation\Enqueue\Consume;
use App\Packages\Annotation\Enqueue\CrudBatchConsume;
use App\Exception\AmqpEmptyBatchAnnotationException;
use ReflectionClass;
use ReflectionException;

/**
 * Class CrudBatchService
 */
class CrudBatchService implements CrudBatchServiceInterface
{
    /**
     * @var AnnotationReader
     */
    public AnnotationReader $reader;

    /**
     * @var Producer
     */
    public Producer $producer;

    /**
     * CrudBatchService constructor.
     *
     * @param AnnotationReader     $reader
     * @param Producer             $producer
     */
    public function __construct(AnnotationReader $reader, Producer $producer)
    {
        $this->reader = $reader;
        $this->producer = $producer;
    }

    /**
     * @param string $data
     * @param string $entityName
     * @param string|null $dtoName
     * @param array|null $params
     *
     * @return mixed|void|BaseResponse
     * @throws AmqpEmptyBatchAnnotationException
     * @throws Exception
     */
    public function handleCreate(string $data, string $entityName, string $dtoName = null, ?array $params = [])
    {
        if ($exchange = $this->getExchange($entityName, Route::TOPIC_CREATE_PREFIX)) {
            $correlationId = Uuid::uuid4();
            $replyTo = $this->producer->getRpcManager()->declareRpcCallbackQueue($this->producer->getChannel(), $correlationId);

            return $this->producer->rpcCallAsync($exchange, $this->createPacket(Route::TOPIC_CREATE_PREFIX, $data,  $entityName, $dtoName), $correlationId, $replyTo);
        }
    }

    /**
     * @param string      $data
     * @param string      $entityName
     * @param string|null $dtoName
     * @param array|null  $params
     *
     * @return mixed|void|BaseResponse
     * @throws AmqpEmptyBatchAnnotationException
     * @throws Exception
     */
    public function handleUpdate(string $data, string $entityName, string $dtoName = null, ?array $params = [])
    {
        if ($exchange = $this->getExchange($entityName, Route::TOPIC_UPDATE_PREFIX)) {
            $correlationId = Uuid::uuid4();
            $replyTo = $this->producer->getRpcManager()->declareRpcCallbackQueue($this->producer->getChannel(), $correlationId);

            return $this->producer->rpcCallAsync($exchange, $this->createPacket(Route::TOPIC_UPDATE_PREFIX, $data,  $entityName, $dtoName), $correlationId, $replyTo);
        }
    }

    /**
     * @param string $data
     * @param string $entityName
     * @param string|null $dtoName
     * @param array|null $params
     *
     * @return mixed|void|BaseResponse
     * @throws AmqpEmptyBatchAnnotationException
     * @throws ReflectionException
     */
    public function handleDelete(string $data, string $entityName, string $dtoName = null, ?array $params = [])
    {
        if ($exchange = $this->getExchange($entityName, Route::TOPIC_DELETE_PREFIX)) {
            $correlationId = Uuid::uuid4();
            $replyTo = $this->producer->getRpcManager()->declareRpcCallbackQueue($this->producer->getChannel(), $correlationId);

            return $this->producer->rpcCallAsync($exchange, $this->createPacket(Route::TOPIC_DELETE_PREFIX, $data,  $entityName, $dtoName), $correlationId, $replyTo);
        }
    }

    /**
     * @param string      $data
     * @param string      $entityName
     * @param string|null $dtoName
     * @param array|null  $params
     *
     * @return mixed|void|BaseResponse
     * @throws AmqpEmptyBatchAnnotationException
     * @throws Exception
     */
    public function handleReplace(string $data, string $entityName, string $dtoName = null, ?array $params = [])
    {
        if ($exchange = $this->getExchange($entityName, Route::TOPIC_REPLACE_PREFIX)) {
            $correlationId = Uuid::uuid4();
            $replyTo = $this->producer->getRpcManager()->declareRpcCallbackQueue($this->producer->getChannel(), $correlationId);

            return $this->producer->rpcCallAsync($exchange, $this->createPacket(Route::TOPIC_REPLACE_PREFIX, $data,  $entityName, $dtoName), $correlationId, $replyTo);
        }
    }

    /**
     * @param string $entityNamespace
     * @param string $action
     *
     * @return Consume
     * @throws AmqpEmptyBatchAnnotationException
     * @throws ReflectionException
     */
    private function resolveAmqpData(string $entityNamespace, string $action):Consume
    {
        /** @var CrudBatchConsume $annotationCrudBatch */
        $annotationCrudBatch = $this->reader->getClassAnnotation(new ReflectionClass($entityNamespace), CrudBatchConsume::class);
        if (!isset($annotationCrudBatch->topicsMap[$action])) {
            throw new AmqpEmptyBatchAnnotationException('error.amqp.entity.empty.batch_annotation', 'Error_500', null, 500, ['{entityName}' => $entityNamespace]);
        }

        return $annotationCrudBatch->topicsMap[$action];
    }

    /**
     * @param $entityName
     * @param $action
     * @return string|Exchange
     * @throws AmqpEmptyBatchAnnotationException
     * @throws ReflectionException
     */
    private function getExchange($entityName, $action)
    {
        $amqpData = $this->resolveAmqpData($entityName, $action);

        if ($amqpData->exchangeName && $amqpData->exchangeBindKey) {
            list($exchangeName) = $this->producer->getPlaceholderResolver()->handlePlaceholdersParameters($amqpData->exchangeName);
            list($exchangeBindKey) = $this->producer->getPlaceholderResolver()->handlePlaceholdersParameters($amqpData->exchangeBindKey);
            $exchange = new Exchange($exchangeName, $exchangeBindKey);
        } else {
            list($exchange) = $this->producer->getPlaceholderResolver()->handlePlaceholdersParameters($amqpData->queue);
        }

        return $exchange;
    }

    /**
     * @param string $action
     * @param string $data
     * @param string $entityName
     * @param string|null $dtoName
     *
     * @return Packet
     */
    private function createPacket(string $action, string $data, string $entityName, ?string $dtoName): Packet
    {
        return Packet::createFromData([
            'action' => $action,
            'entity' => $entityName,
            'dtoName' => $dtoName,
            'package' => json_decode($data, true),
        ]);
    }
}

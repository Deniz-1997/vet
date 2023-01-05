<?php

namespace App\Packages\Consumer;

use App\Interfaces\HandlerInterface;
use PhpAmqpLib\Channel\AMQPChannel;
use Psr\Log\LoggerInterface;
use App\Packages\AMQP\Packet;
use App\Packages\AMQP\Producer;
use App\Packages\AMQP\RPC\RpcManager;
use App\Packages\AMQP\RPC\RpcResponse;
use App\Exception\ErrorResponseException;
use App\Packages\Annotation\Enqueue\Consume;

/**
 * Class HandleCashRegisterResponsesConsumer
 */
class HandleCashRegisterResponsesConsumer extends Consumer
{
    /** @var RpcManager */
    private RpcManager $rpcManager;

    /** @var Producer */
    protected Producer $producer;

    /** @var LoggerInterface */
    private LoggerInterface $logger;

    /** @var HandlerInterface[] */
    private $handlers;

    /**
     * HandleCashRegisterResponsesConsumer constructor.
     *
     * @param RpcManager $rpcManager
     * @param Producer $producer
     * @param LoggerInterface $logger
     * @param HandlerInterface[] $handlers
     */
    public function __construct(RpcManager $rpcManager, Producer $producer, LoggerInterface $logger, iterable $handlers)
    {
        $this->rpcManager = $rpcManager;
        $this->producer = $producer;
        $this->logger = $logger;
        $this->handlers = $handlers;
    }

    /**
     * @Consume(queue="%webslon_api.rabbitmq.rpc_response_queue%")
     *
     * @param Packet $responsePacket
     * @return mixed
     */
    public function process(Packet $responsePacket)
    {
        /** @var $AMQPMessage */
        $AMQPMessage = $responsePacket->getAMQPMessage();
        /** @var AMQPChannel $channel */
        $channel = $AMQPMessage->delivery_info['channel'];
        /** @var string $correlationId */
        $correlationId = $AMQPMessage->has('correlation_id') ? $AMQPMessage->get('correlation_id') : $responsePacket->getId();

        if (!$correlationId) {
            $this->logger->error('Not found correlation_id property in msg '.$AMQPMessage->getBody().'. Reject.');
            $this->reject(false);
            return RpcResponse::RESPONSE_NONE;
        }

        $queueForFrontend = $this->rpcManager->declareRpcCallbackQueue($channel, $correlationId);

        $response = $responsePacket->getData();

        $type = $responsePacket->getField('type');
        if (!$type) {
            $this->logger->error('Not found type property in msg '.$AMQPMessage->getBody().'. Reject.');
            $this->reject(false);
            return RpcResponse::RESPONSE_NONE;
        } elseif ($type === 'reportOfdExchangeStatus') {
            // Исключение для отчета о текущем состоянии т.к. массив с ошибками в ответе по умолчанию
            // TODO Подумать как вынести отсюда
            $response['disableErrorsCheck'] = true;
        }

        $handler = null;
        foreach ($this->handlers as $supportedHandler) {
            if ($supportedHandler->support($type, $response)) {
                $handler = $supportedHandler;
                break;
            }
        }

        if (isset($response['errors']) && is_array($response['errors'])
            && !$this->disableErrorsCheck($response)
        ) {
            $response = [
                'errors' => $response['errors'],
            ];

            if ($handler) {
                $handler->handleErrors($type, $response);
            }
        } else {
            if ($handler) {
                try {
                    $handler->handle($type, $response);
                } catch (ErrorResponseException $errorResponseException) {
                    $response = [
                        'errorCode' => $errorResponseException->getErrorCode(),
                        'errorResponseMessage' => $errorResponseException->getErrorResponseMessage(),
                    ];
                }
            }
        }

        $this->producer->publishToQueue($queueForFrontend, json_encode($response));

        $this->ack();
        
        return RpcResponse::RESPONSE_NONE;
    }

    /**
     * Check disabling errors (for specific responses containing errors by default)
     * @param array $data
     * @return bool
     */
    private function disableErrorsCheck(array $data): bool
    {
        return isset($data['disableErrorsCheck']) && $data['disableErrorsCheck'];
    }

}

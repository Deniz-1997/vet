<?php

namespace App\Packages\Response\Async;

use PhpAmqpLib\Message\AMQPMessage;
use App\Packages\AMQP\Packet;
use App\Packages\AMQP\Producer;

/**
 * Class RpcAsyncResponse
 */
class AsyncResponseSender extends Producer
{
    /**
     * @param string $correlationId
     * @param array  $message
     * @param array  $errors
     *
     * @return array
     * @throws \Exception
     */
    public function send(string $correlationId, array $message, array $errors)
    {
        $packet = new Packet(
            $correlationId,
            new \DateTime(),
            ['response' => $message, 'errors' => $errors]
        );
        $queue = $this->getRpcManager()->declareRpcCallbackQueue($this->getChannel(), $correlationId);

        $request = new AMQPMessage(json_encode($packet), [
            'deliveryMode' => 2,
            'correlation_id' => $correlationId,
        ]);

        $this->publishToQueue($queue, $request);

        return [
            'queueName' => $queue,
            'correlationId' => $correlationId,
        ];
    }
}

<?php

namespace App\Controller\API\Async;

use Exception;
use Nelmio\ApiDocBundle\Annotation\Model;
use PhpAmqpLib\Exception\AMQPProtocolChannelException;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;
use OpenApi\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\AMQP\AMQPConnection;
use App\Packages\AMQP\RPC\RpcManager;
use App\Packages\Annotation\Resource;
use App\Enum\AsyncStatusEnum;
use App\Packages\Response\Async\AsyncResultResponse;
use App\Exception\ApiException;
use App\Packages\Response\BaseResponse;

/**
 * Class AsyncOperationsController
 * @Route("/api/async")
 */
class AsyncOperationsController extends AbstractController
{
    /**
     * @var AsyncResultResponse
     */
    private $asyncResultResponse;

    /**
     * EnumController constructor.
     *
     * @param AsyncResultResponse $asyncResultResponse
     */
    public function __construct(AsyncResultResponse $asyncResultResponse)
    {
        $this->asyncResultResponse = $asyncResultResponse;
    }

    /**
     * @Route("/result/{correlationId}/", name="app.async.result.get", methods={"GET"})
     * @SWG\Get(
     *     summary="Получить ответ асинхронной функции",
     *     description="Возвращает результат работы асинхронной функции. По параметру ответа asyncStatus можно отследить статус результата ответа.",
     *     tags={"Async"},
     *     @SWG\Parameter(
     *          name="correlationId",
     *          in="path",
     *          description="Идентификатор запроса, полученный в ответе асинхронной функции",
     *          @SWG\Schema(
     *              type="string"
     *          )
     *     ),
     *     @SWG\Response(
     *          response=200,
     *         @SWG\JsonContent(),
     *          description="Ответ в случае успешной работы функции",
     *          @Model(type=AsyncResultResponse::class)
     *     ),
     *     @SWG\Response(
     *          response="default",
     *         @SWG\JsonContent(),
     *          description="Error",
     *          @Model(type=BaseResponse::class)
     *     )
     * )
     *
     * @param Request $request
     * @param AMQPConnection $connection
     * @param RpcManager $rpcManager
     * @param LoggerInterface $logger
     * @param string $correlationId
     *
     * @return Response
     * @throws Exception
     */
    public function getAsyncResponseAction(Request $request, AMQPConnection $connection, RpcManager $rpcManager, LoggerInterface $logger, $correlationId)
    {
        $callbackQueue = $rpcManager->getRpcCallbackQueueName($correlationId);

        try {
            $ch = $connection->channel();
            $result = null;
            $ch->basic_consume($callbackQueue, '', false, false, false, false, function(AMQPMessage $message) use(&$result) {
                $result = $message;
            });
            $ch->wait(null, true);
            $ch->close();
            $connection->close();

            if ($result instanceof AMQPMessage) {
                $response = json_decode($result->getBody(), true);

                if (isset($response['errors']) && !empty($response['errors'])
                    && !$this->disableErrorsCheck($response)
                ) {
                    $this->asyncResultResponse->setAsyncStatus(AsyncStatusEnum::ERROR);
                    $this->asyncResultResponse->setStatus(false);

                    if (isset($response['errors'][0])) {
                        foreach ($response['errors'] as $error) {
                            $this->asyncResultResponse->addError(new ApiException($error['message'], $error['stringCode']));
                        }
                    } else {
                        $this->asyncResultResponse->addError(new ApiException($response['errors']['message'], $response['errors']['stringCode']));
                    }
                    $this->asyncResultResponse->setHttpResponseCode(Response::HTTP_BAD_REQUEST);
                } else {
                    $this->asyncResultResponse->setResponse($response);
                    $this->asyncResultResponse->setStatus(true);
                    $this->asyncResultResponse->setAsyncStatus(AsyncStatusEnum::DONE);
                }
            } else {
                $this->asyncResultResponse->setAsyncStatus(AsyncStatusEnum::WAIT);
                $this->asyncResultResponse->setStatus(true);
            }
        } catch (AMQPProtocolChannelException $channelException) {
            $logger->error('AMQPProtocolChannelException: '.$channelException->getMessage(), ['exception' => $channelException, 'correlationId' => $correlationId, 'queue' => $callbackQueue]);
            $this->asyncResultResponse->setAsyncStatus(AsyncStatusEnum::ERROR);
            $this->asyncResultResponse->setStatus(false);
            $this->asyncResultResponse->addError($channelException);
        } catch (Exception $exception) {
            $logger->error('AMQP: '.$exception->getMessage(), ['exception' => $exception, 'correlationId' => $correlationId, 'queue' => $callbackQueue]);
            throw $exception;
        }

        return $this->asyncResultResponse->send();
    }

    /**
     * Disabling errors check for specific responses containing errors by default
     * @param array $response
     * @return bool
     */
    private function disableErrorsCheck(array $response): bool
    {
        return isset($response['disableErrorsCheck']) && $response['disableErrorsCheck'];
    }
}

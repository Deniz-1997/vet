<?php

namespace App\Controller;

use OpenApi\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\AMQP\RabbitRestClient;
use App\Packages\Annotation\Resource;
use App\Service\DependenciesService;
use App\Packages\Response\BaseResponse;

/**
 * Class MessageController
 * @Route("/api/amqp", name="app_amqp_get_messages")
 * @Resource(
 *     tags={"AMQP"},
 *     description="AMQP API",
 *     summariesMap={
 *          "getMessageByQueue": "Получить сообщения из очереди",
 *     },
 *     descriptionsMap={
 *          "getMessageByQueue": "Получить сообщения из очереди",
 *     }
 * )
 */
class MessageController extends AbstractController
{
    /**
     * @var RabbitRestClient
     */
    private RabbitRestClient $rabbitRestClient;
    /**
     * @var DependenciesService
     */
    private $dependenciesService;

    /**
     * @SWG\Get(
     *     tags={"AMQP"},
     *     operationId="getMessageByQueue",
     *     summary="Получить сообщения из очереди",
     *     description="Получить сообщения из очереди",
     *     @SWG\Parameter(
     *          name="host",
     *          in="query",
     *          @SWG\Schema(
     *              type="string"
     *          ),
     *          description="Хост"
     *     ),
     *     @SWG\Parameter(
     *          name="queue",
     *          in="query",
     *          @SWG\Schema(
     *              type="string"
     *          ),
     *          description="Очередь",
     *          required=false
     *     ),
     *     @SWG\Parameter(
     *          name="count",
     *          in="query",
     *          @SWG\Schema(
     *              type="string"
     *          ),
     *          description="Количество сообщений",
     *          required=false
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         @SWG\JsonContent(),
     *         description="Успешный ответ сервиса, в результате приходят
     *     данные запрошенного объекта",
     *         @Model(type=MessageQueueResponse::class)
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         @SWG\JsonContent(),
     *         description="Ошибка выполнения операции",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     )
     * )
     *         examples={
     *              "application/json":{
     *                  "status": false,
     *                  "response": null,
     *                  "errors": {0: {"message": "Текст ошибки", "stringCode":
     *     "ERROR_STRING_CODE", "relatedField": null}}
     *              }
     *         },
     *
     * @Route("/messages", requirements={"host":"\w+", "queue":"\w+"}, defaults={"host":"%2F"})
     * @param Request $request
     *
     * @return Response
     */
    public function getMessage(Request $request, DependenciesService $dependenciesService)
    {
        $this->dependenciesService = $dependenciesService;

        $host = $request->get('host', '%2F');
        if ($host === '/') {
            $host = '%2F';
        }
        $queue = $request->get('queue');
        $count = $request->get('count', 300);
        $this->rabbitRestClient = new RabbitRestClient(getenv('RABBITMQ_HOST'), $host, getenv('RABBITMQ_HTTP_PROTOCOL'), getenv('RABBITMQ_HTTP_PORT'), getenv('RABBITMQ_USERNAME'), getenv('RABBITMQ_PASSWORD'));

        $messages = $this->rabbitRestClient
            ->setBody(['count' => $count,'ackmode' => 'ack_requeue_true', 'encoding' => 'auto', 'truncate' => 50000])
            ->setHeaders(['content-type' => 'application/x-www-form-urlencoded'])
            ->request(Request::METHOD_POST, '/api/queues/'.$host.'/'.$queue.'/get');

        $result = [];
        foreach ($messages as $message) {
            $message['payload'] = json_decode($message['payload'], true);
            $result[] = $message;
        }

        return $this->handleResponse([
            'totalCount' => $messages[0]['message_count'],
            'countItems' => $count,
            'items' => $result,
        ])->send();
    }

    /**
     * @param array               $result
     *
     * @return BaseResponse
     */
    private function handleResponse($result)
    {
        $this->dependenciesService->getResponse()->setResponse($result);

        return $this->dependenciesService->getResponse();
    }
}

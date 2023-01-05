<?php

namespace App\Packages\Consumer;

use App\Exception\DenormalizeException;
use App\Model\Env;
use Doctrine\Common\Annotations\Reader;
use Error;
use Exception;
use PhpAmqpLib\Exception\AMQPConnectionClosedException;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\HttpFoundation\Response;
use App\Packages\AMQP\AMQPConnection;
use App\Packages\AMQP\Exception\AmqpFatalException;
use App\Packages\AMQP\Packet;
use App\Packages\AMQP\Producer;
use App\Packages\Annotation\Enqueue\Consume;
use App\Packages\Annotation\Enqueue\CrudConsume;
use App\Exception\ConnectionTimeoutException;
use App\Packages\Monolog\RabbitMqContext;
use App\Packages\Utils\ErrorMessageHelper;
use App\Exception\ApiException;
use App\Packages\Response\BaseResponse;
use App\Service\HandlerException\Validation\ValidationException;
use App\Service\HandlerException\ValidationAndNormalizationException;
use Throwable;

abstract class AbstractCrudConsumer extends AbstractConsumer
{
    /**
     * @var string
     */
    protected $topicName;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Producer
     */
    protected Producer $producer;

    /**
     * @var Reader
     */
    private $annotationReader;

    /**
     * @var AMQPConnection
     */
    private AMQPConnection $connection;

    /**
     * @required
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @required
     *
     * @param Producer $producer
     *
     * @return $this
     */
    public function setProducer(Producer $producer): self
    {
        $this->producer = $producer;

        return $this;
    }

    /**
     * @required
     *
     * @param Reader $annotationReader
     */
    public function setAnnotationReader(Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    /**
     * @required
     *
     * @param AMQPConnection $connection
     */
    public function setConnection(AMQPConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param Packet $packet
     *
     * @return object|string|void
     * @throws ReflectionException
     */
    public function process(Packet $packet)
    {
        try {
            $this->doProcess($packet);
            $this->ack();
        } catch (ConnectionTimeoutException | AMQPConnectionClosedException | Exception | Throwable $exception){
            $this->resolveFailureProcess($packet, $exception);
        }
    }

    /**
     * @param Packet $packet
     * @param Exception|Error $exception
     * @return bool
     * @throws ReflectionException
     */
    protected function resolveFailureProcess(Packet $packet,  $exception)
    {
        $this->handleException($packet, $exception);
        /** @var CrudConsume $annotation */
        $annotation = $this->annotationReader->getClassAnnotation(new ReflectionClass($this->entityClass), CrudConsume::class);
        $newPacket = Packet::createFromData($packet->getData(),null, $packet->getErrors());

        $data = json_encode($newPacket);
        if (array_filter($this->getReplyData($packet))) {
            $this->reply(json_decode(json_encode(Packet::createFromData($packet->getData(),null, $packet->getErrors())), true));
            $this->ack();

            return;
        }
        if ($annotation instanceof CrudConsume) {
            $consumer = $annotation->topicsMap[$this->topicName] ?? null;
            if ($consumer instanceof Consume && $consumer->onErrors) {
                if ($consumer->onErrors->exchange && $consumer->onErrors->routingKey) {
                    list($exchangeName) = $this->producer->getPlaceholderResolver()->handlePlaceholdersParameters($consumer->onErrors->exchange);
                    $this->producer->getChannel()->exchange_declare($exchangeName, 'direct', false,true, false, '');
                    $this->producer->publishToExchange($exchangeName, $data, $consumer->onErrors->routingKey, ['routing_key' => $consumer->onErrors->routingKey]);
                    $this->reject(false);

                    return true;
                }
                if($consumer->onErrors->queue) {
                    list($queue) = $this->producer->getPlaceholderResolver()->handlePlaceholdersParameters($consumer->onErrors->queue);
                    $this->producer->publishToQueue($queue, $data);
                    $this->reject(false);

                    return true;
                }
            }
        } else {
            $this->reject();

            return true;
        }
        $this->ack();
    }

    /**
     * @param Packet     $packet
     * @param Exception|Throwable $ex
     *
     * @throws ReflectionException
     */
    protected function handleException(Packet $packet, $ex)
    {
        $errors = [];
        if($ex instanceof ValidationException){
            foreach ($ex->getConstraints() ? : [] as $constraint) {
                $errorItem = sprintf('[%s] %s', $constraint->getPropertyPath(), $constraint->getMessage());
                $ex = (new ApiException($errorItem, 'Error_' . Response::HTTP_BAD_REQUEST, $constraint->getPropertyPath(),Response::HTTP_BAD_REQUEST))
                    ->setType(ValidationException::class)
                ;
                $packet->addError($ex);
                $errors[] = $errorItem;
            }
            $this->logger->error(sprintf('Validation errors: %s', implode(',', $errors)), RabbitMqContext::getLoggingContext($packet, $ex));
        } elseif ($ex instanceof ValidationAndNormalizationException){
            $validationErrors = [];
            foreach ($ex->getValidationError() ?: [] as $violation) {
                $errorItem = sprintf('[%s] %s', $violation->getPropertyPath(), $violation->getMessage());
                $packet->addError(new ApiException($errorItem, 'Error_' . Response::HTTP_BAD_REQUEST, $violation->getPropertyPath(), Response::HTTP_BAD_REQUEST));
                $validationErrors[] = $errorItem;
            }
            $normalizationErrors = array_map(function ($error) {
                if (is_array($error) && isset($error[0]) && $error[0] instanceof DenormalizeException) {
                    return $error[0]->getMessage();
                }
                if (is_scalar($error)) {
                    return $error;
                }
                return gettype($error);
            }, $ex->getDenormalizationError() ?: []);
            $ex = (new ApiException(implode(',', $normalizationErrors), 'Error_' . Response::HTTP_BAD_REQUEST, null, Response::HTTP_BAD_REQUEST))
                ->setType(ValidationAndNormalizationException::class)
            ;
            $packet->addError($ex);
            $this->logger->error(sprintf(
                'Validation errors: %s normalized errors: %s',
                implode(',', $validationErrors),
                implode(',', $normalizationErrors)
            ), RabbitMqContext::getLoggingContext($packet, $ex));
        } else {
            if (method_exists($ex, 'getStatusCode')) {
                $code = $ex->getStatusCode();
            }else{
                $code = $ex->getCode();
            }
            if (!isset(Response::$statusTexts[$code])) {
                $code = Response::HTTP_INTERNAL_SERVER_ERROR;
            }
            $ex = (new ApiException($ex->getMessage(), 'Error_' . $code, null, $code))
                ->setType((new ReflectionClass($ex))->getName())
                ->setErrorTrace(Env::getenv('APP_ENV') !== 'prod' ? $ex->getTraceAsString(): null)
            ;

            $packet->addError($ex);
            $this->logger->error(sprintf(
                'Exception: %s',
                $ex->getMessage()
            ), RabbitMqContext::getLoggingContext($packet, $ex));
        }
    }


    /**
     * @param $package
     * @param $index
     * @param $exception
     *
     * @return array
     */
    protected function createResponseWitnException($package, $index, $exception):array
    {
        return [
            'status' => false,
            'package' => $package,
            'response' => null,
            'errors' => [
                [
                    'message' => ErrorMessageHelper::getMessageError($exception),
                    'type' => get_class($exception),
                    'relatedField' => $index,
                    'errorTrace' => Env::getenv('APP_ENV') !== 'prod' ? $exception->getTraceAsString() : null,
                ]
            ],
        ];
    }


    /**
     * @param BaseResponse $response
     *
     * @return array
     */
    protected function createSuccessResponse(BaseResponse $baseResponse): array
    {
        $responseItem = $baseResponse->getResponse();
        $response = [];

        if (method_exists($responseItem, 'getId')) {
            $response['id'] = $responseItem->getId();
        }
        if (method_exists($responseItem, 'getCode')) {
            $response['code'] = $responseItem->getCode();
        }
        if (method_exists($responseItem, 'getCreatedAt')) {
            $response['createdAt'] = $responseItem->getCreatedAt();
        }
        if (method_exists($responseItem, 'getUpdatedAt')) {
            $response['updatedAt'] = $responseItem->getUpdatedAt();
        }
        if (method_exists($responseItem, 'getDeleted')) {
            $response['deleted'] = $responseItem->getDeleted();
        }

        return $response;
    }

    abstract public function doProcess(Packet $packet);
}

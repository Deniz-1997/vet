<?php

namespace App\Packages\Response;

use App\Exception\ApiException;
use App\Interfaces\ApiResponseInterface;
use App\Packages\Event\AfterBaseResponseSendEvent;
use App\Packages\Event\BeforeBaseResponseSendEvent;
use App\Service\DeserializeService;
use App\Service\SerializeService;
use Exception;
use InvalidArgumentException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use function in_array;
use function is_array;

/**
 * Response.
 */
class BaseResponse implements ApiResponseInterface
{
    const ENV_PROD = 'prod';
    const ENV_DEV = 'dev';
    const ENV_TEST = 'test';

    /**
     * @var boolean
     *
     * @SWG\Property(type="boolean", description="Признак успешного ответа сервиса")
     */
    private bool $status = true;

    /**
     * @var integer
     * @SWG\Property(type="integer")
     */
    private int $httpResponseCode = Response::HTTP_OK;

    /**
     * @var array|null
     *
     * @SWG\Property(type="array", description="Массив ошибок, в случае status=false", @SWG\Items(ref=@Model(type=ApiException::class)))
     */
    private ?array $errors = null;

    /**
     * @var BaseItemChildResponse
     *
     * @SWG\Property(type="object", description="Результат работы сервиса, в случае status=true. Может содержать любое значение или объект")
     */
    private $response;

    /**
     * @var string
     *
     * @SWG\Property(type="string", description="Идентификатор запроса для систем логирования")
     */
    private $requestId;

    /**
     * @var bool
     *
     * @SWG\Property(type="bool", description="Флаг для возврата обычного ответа, без error, status, requestId")
     */
    private bool $simpleReturn = false;

    /**
     * @var array|null
     */
    private ?array $headers = [];

    /**
     * @var SerializeService
     */
    private SerializeService $serializer;

    /**
     * @var DeserializeService
     */
    private DeserializeService $deserializer;

    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;

    /**
     * @var array
     */
    private array $attributes = [];

    /**
     * @var array
     */
    private array $serializationContext = [];

    /**
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $eventDispatcher;

    /**
     * BaseResponse constructor.
     *
     * @param SerializeService $serializer
     * @param RequestStack $requestStack
     * @param DeserializeService $deserializer
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(SerializeService $serializer, RequestStack $requestStack, DeserializeService $deserializer, EventDispatcherInterface $eventDispatcher)
    {
        $this->serializer = $serializer;
        $this->deserializer = $deserializer;
        $this->requestStack = $requestStack;
        $this->eventDispatcher = $eventDispatcher;
        /** @var Request|null $request */
        $request = $this->requestStack->getCurrentRequest();
        $this->requestId = $request instanceof Request ? $request->headers->get('X-Request-Id') : "";
    }

    /**
     * @return array
     */
    protected function prepareContent(): array
    {
        return $this->getSimpleReturn() ? $this->response : [
            'status' => $this->status,
            'errors' => $this->errors,
            'requestId' => $this->requestId,
            'response' => $this->response,
        ];
    }

    /**
     * @return Response
     * @throws InvalidArgumentException
     */
    public function send(): Response
    {
        
        if (!is_array($this->headers)) {
            $this->headers = [];
        }
        if ($this->status === true && !$this->httpResponseCode) {
            $this->httpResponseCode = Response::HTTP_OK;
        }

        if (!in_array('Content-type', $this->headers, true)) {
            $this->headers['Content-type'] = 'application/json';
        }
        
        $beforeEvent = new BeforeBaseResponseSendEvent($this);
        $this->eventDispatcher->dispatch($beforeEvent);
       
        $serializedData = $this->serializer->serialize(
            $this->prepareContent(), 'json',
            $this->getSerializationContext()
        );

        $afterEvent = new AfterBaseResponseSendEvent($this);
        $this->eventDispatcher->dispatch($afterEvent);
     
        return new Response($serializedData, $this->httpResponseCode ?? Response::HTTP_INTERNAL_SERVER_ERROR,
            $this->headers);
    }

    /**
     * @param array|null $keys
     * @return $this
     */
    public function unsetErrors(?array $keys): BaseResponse
    {
        if (!$keys) {
            $this->errors = null;
        } else {
            foreach ($this->errors as &$error) {
                foreach ($keys as $key) {
                    if ($error->get . ucfirst($key)) {
                        unset($error[$key]);
                    }
                }
            }
        }

        return $this;
    }

    /**
     * @return array|null
     */
    public function getErrors(): ?array
    {
        return $this->errors;
    }

    /**
     * @param array|null $errors
     *
     * @return $this
     */
    public function setErrors(?array $errors): BaseResponse
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * @param Exception $exception
     * @return BaseResponse
     */
    public function addError(Exception $exception): self
    {
        if ($exception instanceof ApiException) {
            $this->httpResponseCode = $exception->getCode();
        }

        $this->errors[] = $exception;

        return $this;
    }

    /**
     * @return BaseResponse
     * @deprecated Более не используем этот метод, т.к. status по умолчанию = true
     */
    public function statusOk(): self
    {
        $this->status = true;

        return $this;
    }

    /**
     * @return BaseResponse
     */
    public function statusError(): self
    {
        $this->status = false;

        return $this;
    }

    /**
     * @return bool
     */
    public function isStatusOk(): bool
    {
        return $this->status;
    }

    /**
     * @return array|null|object
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param $response
     *
     * @return BaseResponse
     */
    public function setResponse($response): self
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @param bool $simple
     * @return $this
     */
    public function setSimpleReturn(bool $simple): self
    {
        $this->simpleReturn = $simple;

        return $this;
    }

    /**
     * @return bool
     */
    public function getSimpleReturn(): bool
    {
        return $this->simpleReturn;
    }

    /**
     * @param int $code
     *
     * @return BaseResponse
     */
    public function setHttpResponseCode(int $code): BaseResponse
    {
        $this->httpResponseCode = $code;

        return $this;
    }

    /**
     * @return int
     */
    public function getHttpResponseCode(): int
    {
        return $this->httpResponseCode;
    }

    /**
     * @param array $attributes
     *
     * @return BaseResponse
     */
    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;
        $this->serializationContext['attributes'] = $this->attributes;

        return $this;
    }

    /**
     * @return array
     */
    public function getSerializationContext(): array
    {
        if (!empty($this->attributes) && is_array($this->attributes)) {
            $this->serializationContext['attributes'] = $this->attributes;
        }

        return $this->serializationContext;
    }

    /**
     * @param array $serializationContext
     *
     * @return $this
     */
    public function setSerializationContext(array $serializationContext): self
    {
        $this->serializationContext = $serializationContext;

        return $this;
    }

    /**
     * @return bool|float|int|string
     */
    public function toJsonResponse()
    {
        return $this->getSerializer()->serialize($this->getResponse() ?? [], 'json');
    }

    /**
     * @return Serializer|SerializeService
     */
    public function getSerializer(): SerializeService
    {
        return $this->serializer;
    }

    /**
     * @return DeserializeService
     */
    public function getDeserializer(): DeserializeService
    {
        return $this->deserializer;
    }

    /**
     * @return EventDispatcherInterface
     */
    public function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @return BaseResponse
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): self
    {
        $this->eventDispatcher = $eventDispatcher;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getHeaders(): ?array
    {
        return $this->headers;
    }

    /**
     * @param array|null $headers
     * @return BaseResponse
     */
    public function setHeaders(?array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @return bool
     */
    public function isStatus(): bool
    {
        return $this->status;
    }
}

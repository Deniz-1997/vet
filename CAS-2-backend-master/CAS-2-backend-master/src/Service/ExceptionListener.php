<?php

namespace App\Service;

use App\Interfaces\ApiResponseInterface;
use App\Interfaces\DefaultListenerExceptionInterface;
use App\Interfaces\HandlerErrorInterface;
use App\Packages\Response\BaseResponse as ApiResponse;
use App\Service\HandlerException\AuthenticationCredentialsNotFoundExceptionHandler;
use App\Service\HandlerException\AuthenticationErrorHandler;
use App\Service\HandlerException\Database\DriverExceptionHandler;
use App\Service\HandlerException\Database\ORMExceptionHandler;
use App\Service\HandlerException\Database\UniqueConstraintViolationExceptionHandler;
use App\Service\HandlerException\DefaultHandler;
use App\Service\HandlerException\DenormalizeExceptionHandler;
use App\Service\HandlerException\EnumExceptionHandler;
use App\Service\HandlerException\HttpExceptionHandler;
use App\Service\HandlerException\InsufficientAuthenticationExceptionHandler;
use App\Service\HandlerException\InvalidArgumentExceptionHandler;
use App\Service\HandlerException\NormalizableValueExceptionHandler;
use App\Service\HandlerException\Request\ValidationHandler;
use App\Service\HandlerException\Validation\ValidationErrorHandler;
use App\Service\HandlerException\ValidationAndNormalizationExceptionHandler;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ExceptionListener
 * Class mediator for handlers exception
 */
class ExceptionListener implements DefaultListenerExceptionInterface
{
    const ENV_PROD = 'prod';

    const ENV_DEV = 'dev';

    const ENV_STAGE = 'stage';

    const ENV_TEST = 'test';

    /**
     * @var ApiResponse
     */
    private ApiResponse $response;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var string
     */
    private string $basePath;

    /**
     * @var \Throwable
     */
    private \Throwable $exception;

    /**
     * @var string
     */
    private string $requestId;

    /**
     * @var string
     */
    private string $env;

    /**
     * @var array
     */
    private array $handlers = [];

    /** @var TelegramBotService */
    private $telegram;

    /**
     * @var HandlerErrorInterface
     */
    private HandlerErrorInterface $handler;

    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * ExceptionListener constructor.
     *
     * @param ApiResponse $response
     * @param LoggerInterface $logger
     * @param array $basePath
     * @param string $env
     * @param TranslatorInterface $translator
     */
    public function __construct(ApiResponse $response, LoggerInterface $logger, array $basePath, string $env, TranslatorInterface $translator, TelegramBotService $telegram)
    {
        $this->response = $response;
        $this->logger = $logger;
        $this->basePath = $basePath[0];
        $this->env = $env;
        $this->translator = $translator;
        $this->telegram = $telegram;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     *
     */
    public function onKernelException(GetResponseForExceptionEvent $event): void
    {
        $event1 = $event;

        if (!strpos($event1->getRequest()->getPathInfo(), $this->basePath) === 0) {
            return;
        }

        $this->requestId = $event1->getRequest()->headers->get('X-Request-Id', '');
        $this->exception = $event1->getThrowable();
        $response = [];
        /** @var HandlerErrorInterface $handler */
        foreach ($this->getHandlers() as $handler) {
            if (!$handler->supportException()) {
                continue;
            }

            $this->handler = $handler;
            $response = $this->handler->handle()->send();

            break;
        }
        // Logged real exception
        $ex = $event1->getThrowable();
        $this->getLogger()->error($ex, array_merge($this->getContext(), ['exception' => $ex]));

        $class = new \ReflectionClass(Response::class);
        $response->setStatusCode(!in_array($this->handler->getStatusCode(), $class->getConstants()) ? Response::HTTP_INTERNAL_SERVER_ERROR : $this->handler->getStatusCode());
        if ($headers = $this->handler->getHeaders()) {
            $response->headers->replace($headers);
        }
        $event1->setResponse($response);


        if ($this->isProd()) {
            if (
                strpos($ex->getMessage(), 'Для данной ККМ') !== false ||
                strpos($ex->getMessage(), 'Не заполнено поле') !== false ||
                strpos($ex->getMessage(), 'Документ уже зарегистрирован') !== false ||
                strpos($ex->getMessage(), 'Access Denied') !== false ||
                strpos($ex->getMessage(), 'No route found') !== false
            ) {
                return;
            }

            $message = "*KAC2*" . "%0A%0A";
            $message .= "*Error*: " . preg_replace('/(\*|_)/', '', $ex->getMessage()) . "%0A%0A";
            $message .= "*File*: " . $ex->getFile() . '(' . $ex->getLine() . ")%0A%0A";
            $message .= "*Trace*: %0A" . $this->convertTrace(preg_replace('/#\d+/', '', $ex->getTraceAsString()), $message) . "%0A%0A";
            $this->telegram
                ->setMarkDown(true)
                ->setChannel(getenv('TELEGRAM_EXCEPTION_CHAT_ID'))
                ->send_message($message);
        }
    }

    /**
     * @return array
     */
    public function getContext(): array
    {
        $context = [];
        if ($this->requestId) {
            $context = [
                'tags' => [
                    'Request-Id' => $this->requestId,
                ],
            ];
        }

        return $context;
    }

    /**
     * @return array
     */
    public function getHandlers(): array
    {
        if (!$this->handlers) {
            $this->handlers = $this->getDefaultHandlers();
        }

        return $this->handlers;
    }

    /**
     * for configure
     *
     * @param array $handlers
     *
     * @return mixed|void
     */
    public function setHandlers(array $handlers)
    {
        $this->handlers = $handlers;
    }

    /**
     * @param array $handlers
     */
    public function addHandlers(array $handlers): void
    {
        $addHandler = [];
        foreach ($handlers as $handler) {
            $addHandler[] = new $handler($this);
        }
        $this->handlers = array_merge($this->handlers, $addHandler);
    }

    /**
     * @return array
     */
    public function getDefaultHandlers(): array
    {
        return [
            new ValidationHandler($this),
            new EnumExceptionHandler($this),
            new ORMExceptionHandler($this),
            new ValidationErrorHandler($this),
            new UniqueConstraintViolationExceptionHandler($this),
            new DriverExceptionHandler($this),
            new AuthenticationCredentialsNotFoundExceptionHandler($this),
            new InsufficientAuthenticationExceptionHandler($this),
            new AuthenticationErrorHandler($this),
            new DenormalizeExceptionHandler($this),
            new ValidationAndNormalizationExceptionHandler($this),
            new NormalizableValueExceptionHandler($this),
            new HttpExceptionHandler($this),
            new InvalidArgumentExceptionHandler($this),
            new DefaultHandler($this),
        ];
    }

    /**
     * @return mixed
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @return string
     */
    public function getRequestId(): string
    {
        return $this->requestId;
    }

    /**
     * @param ApiResponseInterface $response
     * @return ApiResponseInterface
     */
    public function addDirtyResponse(ApiResponseInterface $response): ApiResponseInterface
    {
        $dirtyResponse = $response;

        return $response;
    }

    /**
     * @return ApiResponseInterface
     */
    public function getResponse(): ApiResponseInterface
    {
        return $this->response;
    }

    /**
     * @return TranslatorInterface
     */
    public function getTranslator(): TranslatorInterface
    {
        return $this->translator;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    public function isProd(): bool
    {
        return $this->env == self::ENV_PROD;
    }

    public function isDev(): bool
    {
        return in_array($this->env, [self::ENV_DEV, self::ENV_STAGE, 'local']);
    }

    public function isTest(): bool
    {
        return $this->env == self::ENV_TEST;
    }

    private function convertTrace($string, $message)
    {
        $array = explode("\n", $string);

        $str = 0;

        $ret = [];

        foreach ($array as $i => $item) {
            $str += strlen($item);

            if ($str > 1500) break;

            $ret[] = "*№" . ++$i . "* $item%0A";
        }

        return implode('%0A', $ret);
    }
}

<?php
namespace App\Packages\Client;

use App\Model\Env;
use App\Packages\EventDispatcher\GatewayConnectorEvents\EventRegisterQueue;
use App\Exception\ApiException;
use App\Exception\ConfigurationEnvException;
use App\Exception\ConnectionTimeoutException;
use App\Exception\InvalidResponseException;
use App\Packages\Security\Encryptor;
use App\Service\Trace\GuzzleMiddleware;
use App\Traits\AsyncTrait;
use Exception;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class AbstractClient
 */
abstract class AbstractClient
{
    use AsyncTrait;

    public const EVENT_REGISTER_QUEUE = 'onRegisterQueue';
    public const EVENT_UNREGISTER_QUEUE = 'onUnregisterQueue';
    /** @var int */
    public const DEFAULT_TIMEOUT = 3;

    /** @var int */
    protected int $timeout;

    /** @var string */
    private string $route;

    /** @var string */
    private string $routeSuffix = '';

    /** @var string */
    private string $method = 'GET';

    /** @var array|null */
    private ?array $headers;

    /** @var array|null */
    private ?array $query;

    /** @var array|null */
    private ?array $formParams;

    /** @var array|string|null */
    private $body;

    /** @var ?array */
    private ?array $result = null;

    /** @var RequestStack|null */
    private ?RequestStack $requestStack;

    /** @var  LoggerInterface */
    private LoggerInterface $logger;

    /** @var Encryptor */
    private Encryptor $encryptor;

    /** @var string|null */
    private ?string $microServiceSignature;

    /** @var OAuthClient */
    private OAuthClient $authClient;

    /**
     * Будет запрашивает токен по client_credentials
     * Игнорируя наличие\отсутствие токена в заголовке Auhtorization текущего мкс
     * А также игнорируя факт о среде выпонения (HTTP, CLI)
     *
     * @var boolean
     */
    private bool $enableForceClientCredentialsAuthorization = false;

    /** @var bool */
    protected bool $useQueue = false;

    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * AbstractClient constructor.
     *
     * @param LoggerInterface $logger
     * @param Encryptor $encryptor
     * @param OAuthClient $authClient
     * @param EventDispatcherInterface $eventDispatcher
     * @param string $microServiceSignature
     */
    public function __construct(LoggerInterface $logger, Encryptor $encryptor, OAuthClient $authClient, EventDispatcherInterface $eventDispatcher, string $microServiceSignature)
    {
        $this->logger = $logger;
        $this->encryptor = $encryptor;
        $this->eventDispatcher = $eventDispatcher;
        $this->microServiceSignature = $microServiceSignature;
        $this->authClient = $authClient;
        $this->timeout = $this->getDefaultTimeout();
    }

    /**
     * @return int
     */
    protected function getDefaultTimeout(): int
    {
        $env = (int)Env::getenv('API_GATEWAY_TIMEOUT');

        return $env > 0 ? $env : static::DEFAULT_TIMEOUT;
    }

    /**
     * Timeout request - for debug
     *
     * @param $timeout int
     *
     * @return $this
     */
    public function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout > 0 ? $timeout : $this->getDefaultTimeout();

        return $this;
    }

    /**
     * @required
     * For http controller usage
     * Null if not http scope
     *
     * @param RequestStack $requestStack
     */
    public function setRequestStack(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @return array|null
     * @throws GuzzleException
     */
    public function request(): ?array
    {
        //If http scope logic
        $defaultHeaders = [];
        if ($this->requestStack && $currentRequest = $this->requestStack->getCurrentRequest()) {
            $requestId = $currentRequest->headers->get('X-Request-Id');
            $microServiceSignature = null;

            if ($signature = $currentRequest->headers->get('X-Microservice-Signature')) {
                $microServiceSignature = $signature;
            } else {
                if ($this->microServiceSignature) {
                    $microServiceSignature = $this->microServiceSignature;
                }
            }

            if ($requestId) {
                $defaultHeaders['X-Request-Id'] = $requestId;
            }

            if ($microServiceSignature) {
                try {
                    $hashedSignature = $this->encryptor->encrypt($microServiceSignature);
                    $defaultHeaders['X-Microservice-Signature'] = $hashedSignature;
                } catch (Exception $exception) {
                    $this->logger->error($exception, ['exception' => $exception]);
                }
            }
        }

        $headers = array_merge($defaultHeaders, $this->getHeaders() ?? []);
        //$headers = $defaultHeaders; // fix for gateway requests
        $headers['Authorization'] = $this->getAuthorizationHeader();

        $options = [
            'headers' => $headers,
            'query' => $this->getQuery() ?? [],
            'form_params' => $this->getFormParams() ?? null,
        ];
        if ($body = $this->getBody()) {
            if (is_array($body)) {
                $options['json'] = $body;
            } else {
                $arJson = json_decode($body);
                if (!is_null($arJson)) {
                    $options['json'] = $arJson;
                } else {
                    $options['body'] = $body;
                }
            }
        }

        $url = rtrim($this->getRoute() . $this->getRouteSuffix(), '/') . '/';

        return $this
            ->doRequest($url, $this->getMethod(), $options)
            ->clear()
            ->getResult();
    }

    /**
     * @param string $route
     * @param string $method
     * @param array $options
     *
     * @return AbstractClient
     */
    private function doRequest(string $route, $method = 'GET', $options = []): self
    {
        $this->result = null;

        $gatewayBasePath = Env::getenv('API_GATEWAY_BASE_PATH');
        $urlParams = parse_url($gatewayBasePath);
        $domain = $urlParams['host'];
        $gatewayApiToken = Env::getenv('API_GATEWAY_TOKEN');
        $gatewayBasicAuthLogin = Env::getenv('API_GATEWAY_BASIC_AUTH_LOGIN');
        $gatewayBasicAuthPassword = Env::getenv('API_GATEWAY_BASIC_AUTH_PASSWORD');
        $gatewayIsDebug = (bool)(Env::getenv('API_GATEWAY_DEBUG') == 'Y');

        if (!$gatewayBasePath) {
            throw new ConfigurationEnvException('API_GATEWAY_BASE_PATH environment variable not set.');
        }

        $defaultGuzzleOptions = [
            'timeout' => 100,//$this->timeout,
            'headers' => [],
            'http_errors' => false,
            'debug' => $gatewayIsDebug,
        ];

        if (!isset($options['headers']['Authorization']) && $gatewayApiToken) {
            $defaultGuzzleOptions['headers']['Authorization'] = 'Access-Token: ' . $gatewayApiToken;
        }

        if (isset($options['form_params'])) {
            $defaultGuzzleOptions['headers']['Accept'] = 'multipart/form-data';
            $defaultGuzzleOptions['headers']['Content-Type'] = 'application/x-www-form-urlencoded';
        } else {
            $defaultGuzzleOptions['headers']['Accept'] = 'application/json';
            $defaultGuzzleOptions['headers']['Content-Type'] = 'application/json';
        }

        $stack = HandlerStack::create();
        if (Env::getenv('ZIPKIN_ENDPOINT_URL')) {
            $stack->push(GuzzleMiddleware::create('GatewayConnector', ['endpointGateway' => $route]));
        }

        $client = new GuzzleHttpClient(['base_uri' => rtrim($gatewayBasePath, '/') . '/', 'handler' => $stack]);
        $options = array_merge_recursive($options, $defaultGuzzleOptions);

        if ($gatewayBasicAuthLogin && $gatewayBasicAuthPassword) {
            $options['auth'] = [$gatewayBasicAuthLogin, $gatewayBasicAuthPassword];
        }

        /** @var EventRegisterQueue $event */
        $event = $this->beforeSend($route, $method, [
            'body' => $options['body'] ?? [],
            'request' => $options['request'] ?? [],
            'query' => $options['query'] ?? [],
            'verify' => false
        ]);

        //$response = $client->request($method, $route, $options);
        $response = $this->makeRequest($client, $method, $route, $options, $domain);

        $json = $response->getBody()->getContents();
        if (!$json) {
            $this->logger->error('Gateway response couldn\'t be read.', [
                'request' => [
                    'method' => $method,
                    'route' => rtrim($gatewayBasePath, '/') . '/' . $route,
                    'options' => $options,
                ],
                'response' => [
                    'answer' => $json,
                    'headers' => $response->getHeaders(),
                ],
            ]);
            throw new InvalidResponseException('Gateway response couldn\'t be read.');
        }

        $result = json_decode($json, true);

        if (!is_array($result)) {
            $this->logger->error('Gateway response doesn\'t contain a valid json.', [
                'request' => [
                    'method' => $method,
                    'route' => rtrim($gatewayBasePath, '/') . '/' . $route,
                    'options' => $options,
                ],
                'response' => [
                    'answer' => $json,
                    'headers' => $response->getHeaders(),
                ],
            ]);
            throw new InvalidResponseException('Gateway response doesn\'t contain a valid json.');
        }

        $this->result = $result;

        $this->afterSend($event);

        return $this;
    }

    /**
     * @param GuzzleHttpClient $client
     * @param string $method
     * @param string $route
     * @param array $options
     * @param string $url
     *
     * @return ResponseInterface
     */
    public function makeRequest(GuzzleHttpClient $client, string $method, string $route, array $options, string $url): ResponseInterface
    {
        $urlParameters = parse_url($url);
        $domain = $urlParameters['path'];

        try {
            $response = $client->request($method, ltrim($route, "/"), $options);
        } catch (ConnectException $e) {
            $msg = 'No connection to server ' . $domain;
            throw (new ConnectionTimeoutException($msg))->setServiceName($domain);
        } catch (Exception $exception) {
            throw new RuntimeException($exception->getMessage());
        }

        if ($response->getStatusCode() === 504) {
            $msg = 'Gateway timeout when requesting a domain ' . $domain;
            throw (new ConnectionTimeoutException($msg))->setServiceName($domain);
        }

        return $response;
    }

    private function getAuthorizationHeader()
    {
        $token = null;

        if ($this->requestStack && !$this->isEnableForceClientCredentialsAuthorization() && $currentRequest = $this->requestStack->getCurrentRequest()) {
            $authorizationHeader = $currentRequest->headers->get('Authorization');
            if ($authorizationHeader) {
                $token = $authorizationHeader;
            }
        } else {
            $oauthResponse = $this->authClient->getAccessTokenForClient();
            $token = ucfirst($oauthResponse->getTokenType()) . ' ' . $oauthResponse->getAccessToken();
        }

        return $token;
    }

    protected function checkResultHasItems(): AbstractClient
    {
        if (
            !is_array($this->result)
            || !isset($this->result['response']['items'])
            || !is_array($this->result['response']['items'])
        ) {
            throw new RuntimeException('Gateway response doesn\'t contain response->items section.');
        }

        return $this;
    }

    /**
     * @return self
     */
    public function clear(): AbstractClient
    {
        $this->routeSuffix = '';
        $this->method = 'GET';
        $this->headers = null;
        $this->query = null;
        $this->formParams = null;
        $this->body = null;
        $this->timeout = $this->getDefaultTimeout();

        return $this;
    }

    /**
     * @return array|null
     */
    public function getResult(): ?array
    {
        return $this->result;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @param string $route
     *
     * @return static
     */
    public function setRoute(string $route): self
    {
        $this->route = trim($route, '/') . '/';

        return $this;
    }

    /**
     * @return string
     */
    protected function getRouteSuffix(): string
    {
        return $this->routeSuffix;
    }

    /**
     * @param string $routeSuffix
     *
     * @return AbstractClient
     */
    protected function setRouteSuffix(string $routeSuffix): AbstractClient
    {
        $this->routeSuffix = $routeSuffix;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     *
     * @return self
     */
    public function setMethod(string $method): self
    {
        $this->method = $method;

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
     *
     * @return self
     */
    public function setHeaders(?array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return AbstractClient
     */
    public function setHeadersValue(string $key, string $value): self
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getQuery(): ?array
    {
        return $this->query;
    }

    /**
     * @param array|null $query
     *
     * @return self
     */
    public function setQuery(?array $query): self
    {
        $this->query = $query;

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return self
     */
    public function setQueryValue(string $key, string $value): self
    {
        $this->query[$key] = $value;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getFormParams(): ?array
    {
        return $this->formParams;
    }

    /**
     * @param array|null $formParams
     *
     * @return AbstractClient
     */
    public function setFormParams(?array $formParams): AbstractClient
    {
        $this->formParams = $formParams;

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return AbstractClient
     */
    public function setFormParamsValue(string $key, string $value): self
    {
        $this->formParams[$key] = $value;

        return $this;
    }

    /**
     * @return array|null|string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param array|null|string $body
     *
     * @return AbstractClient
     */
    public function setBody($body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return bool
     */
    public function isUseQueue(): bool
    {
        return $this->useQueue;
    }

    /**
     * @return bool
     */
    public function isEnableForceClientCredentialsAuthorization(): bool
    {
        return $this->enableForceClientCredentialsAuthorization;
    }

    /**
     * @param bool $enableForceClientCredentialsAuthorization
     *
     * @return $this
     */
    public function setEnableForceClientCredentialsAuthorization(bool $enableForceClientCredentialsAuthorization): self
    {
        $this->enableForceClientCredentialsAuthorization = $enableForceClientCredentialsAuthorization;

        return $this;
    }

    /**
     * @param bool $value
     *
     * @return AbstractClient
     */
    public function setUseQueue(bool $value): self
    {
        try {
            if (true === $value && !class_exists('Webslon\Bundle\MessageExchange\MessageExchangeBundle')) {
                throw new ApiException('MessageExchangeBundle не установлен', 'Error_500');
            }

            $this->useQueue = $value;
        } catch (Exception $exception) {
            $this->useQueue = false;
        }

        return $this;
    }

    /**
     * @param string $route
     * @param string $method
     * @param array $options
     *
     * @return EventRegisterQueue|Event|null
     */
    protected function beforeSend(string $route, string $method, array $options)
    {
        if ($this->useQueue) {
            $event = new EventRegisterQueue($route, $method, $options, null);
            $this->eventDispatcher->dispatch($event, self::EVENT_REGISTER_QUEUE);

            return $event;
        }

        return null;
    }

    /**
     * @param Event|null $event
     */
    protected function afterSend(Event $event = null)
    {
        if ($this->useQueue && $event instanceof EventRegisterQueue && $event->isRegisterQueue()) {
            $event->setResultRequest($this->result);
            $this->eventDispatcher->dispatch($event, self::EVENT_UNREGISTER_QUEUE);
        }
    }
}

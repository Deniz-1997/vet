<?php

namespace App\Traits;

use App\Service\Trace\GuzzleMiddleware;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\PromiseInterface;

/**
 * Trait AsyncTrait
 *
 * @package App\GatewayConnector
 */
trait AsyncTrait
{
    public function requestAsync(): ?array
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
                } catch (\Exception $exception) {
                    $this->logger->error($exception, ['exception' => $exception]);
                }
            }
        }

        // $headers = array_merge($defaultHeaders, $this->getHeaders() ?? []);
        $headers = $defaultHeaders; // fix for gateway requests
        $headers['Authorization'] = $this->getAuthorizationHeader();

        $options = [
            'headers' => $headers,
            'query' => $this->getQuery() ?? [],
            'form_params' => $this->getFormParams() ?? null,
            'verify' => false
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
            ->doRequestAsync($url, $this->getMethod(), $options)
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
    private function doRequestAsync(string $route, $method = 'GET', $options = []): self
    {
        $this->result = null;

        $gatewayBasePath = getenv('API_GATEWAY_BASE_PATH');
        $urlParams = parse_url($gatewayBasePath);
        $domain = $urlParams['host'];
        $gatewayApiToken = getenv('API_GATEWAY_TOKEN');
        $gatewayBasicAuthLogin = getenv('API_GATEWAY_BASIC_AUTH_LOGIN');
        $gatewayBasicAuthPassword = getenv('API_GATEWAY_BASIC_AUTH_PASSWORD');
        $gatewayIsDebug = (bool)(getenv('API_GATEWAY_DEBUG') == 'Y');

        if (!$gatewayBasePath) {
            throw new \RuntimeException('API_GATEWAY_BASE_PATH environment variable not set.');
        }

        $defaultGuzzleOptions = [
            'timeout' => $this->timeout,
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
        if (getenv('ZIPKIN_ENDPOINT_URL')) {
            $stack->push(GuzzleMiddleware::create('GatewayConnector', ['endpointGateway' => $route]));
        }

        $client = new GuzzleHttpClient(['base_uri' => rtrim($gatewayBasePath, '/') . '/', 'handler' => $stack]);
        $options = array_merge_recursive($options, $defaultGuzzleOptions);

        if ($gatewayBasicAuthLogin && $gatewayBasicAuthPassword) {
            $options['auth'] = [$gatewayBasicAuthLogin, $gatewayBasicAuthPassword];
        }

        //$response = $client->request($method, $route, $options);
        $this->result = $this->makeRequestAsync($client, $method, $route, $options, $domain);

        return $this;
    }

    /**
     * @param GuzzleHttpClient $client
     * @param string $method
     * @param string $route
     * @param array $options
     * @param string $url
     *
     * @return PromiseInterface
     */
    public function makeRequestAsync(GuzzleHttpClient $client, string $method, string $route, array $options, string $url): PromiseInterface
    {
        return $client->requestAsync($method, ltrim($route, '/'), $options);
    }
}

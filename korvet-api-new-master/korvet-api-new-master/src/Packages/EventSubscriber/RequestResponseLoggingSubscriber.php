<?php

namespace App\Packages\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class RequestResponseLoggingSubscriber
 */
class RequestResponseLoggingSubscriber implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var bool
     */
    private $sanitize;

    /**
     * @var array
     */
    private $sanitizeKeys;

    /**
     * Filter keys for logging
     */
    const DEFAULT_SANITIZE_KEYS = [
        'authorization',
        'database_url',
        'http_authorization',
        'phpsessid',
        'password',
        'client_secret'
    ];

    const IGNORE_URLS = [
        [['GET', 'POST'], '/oauth\/v2\/token\/?/'], //Получение токена, с передачей пароля
        [['POST'], '/api\/user\/?/'] //Передача пароля - регистрация пользователя
    ];

    /**
     * RequestResponseLoggingSubscriber constructor.
     * @param LoggerInterface $logger
     * @param bool $sanitize
     * @param array $sanitizeKeys
     */
    public function __construct(LoggerInterface $logger, $sanitize = true, array $sanitizeKeys = self::DEFAULT_SANITIZE_KEYS)
    {
        $this->logger = $logger;
        $this->sanitize = $sanitize;
        $this->sanitizeKeys = $sanitizeKeys;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::TERMINATE => 'onTerminate',
        ];
    }

    public function onTerminate(TerminateEvent $postResponseEvent)
    {
        $requestData = $responseData = [];
        $request = $postResponseEvent->getRequest();
        $response = $postResponseEvent->getResponse();

        foreach (self::IGNORE_URLS as $ignoreUrlData) {
            $allowedMethods = $ignoreUrlData[0];
            $uri = $ignoreUrlData[1];

            $methodCheck = false;
            foreach ($allowedMethods as $allowedMethod) {
                if ($request->isMethod($allowedMethod)) {
                    $methodCheck = true;
                }
            }

            if (preg_match($uri, $request->getRequestUri()) && $methodCheck) {
                return; //Ignore this request
            }
        }

        $func = function($headerValue) {
            if (is_array($headerValue) && count($headerValue) == 1 && array_key_exists(0, $headerValue)) {
                return $headerValue[0];
            }

            return $headerValue;
        };

        $headers = array_map($func, $request->headers->all());
        $cookies = array_map($func, $request->cookies->all());
        $query = array_map($func, $request->query->all());
        $postData = array_map($func, $request->request->all());

        $this->sanitize($headers);
        $this->sanitize($cookies);
        $this->sanitize($postData);
        $this->sanitize($query);

        $requestData['query'] = json_encode($query);
        $requestData['headers'] = json_encode($headers);
        $requestData['cookies'] = json_encode($cookies);
        $requestData['request'] = json_encode($postData);
        $requestData['body'] = $request->getContent();
        $requestData['host'] = $request->getSchemeAndHttpHost().$request->getRequestUri();
        $requestData['method'] = $request->getMethod();

        $responseHeaders = array_map($func, $response->headers->all());
        $this->sanitize($responseHeaders);

        $responseData['headers'] = json_encode($responseHeaders);
        $responseData['statusCode'] = $response->getStatusCode();
        $responseData['body'] = $response->getContent();

        if ($this->sanitize) {
            $this->sanitize($requestData);
            $this->sanitize($responseData);
        }

        //Body > 9kb (limit fluentd 10kb)
        if (strlen($responseData['body']) > 9216) {
            $responseData['body'] = substr($responseData['body'], 0, 9216).'...'; //Get first 9kb
        }

        $this->logger->warning('Matched request '.$request->getPathInfo().' ['.$response->getStatusCode().']', [
            'extra' => [
                'request' => $requestData,
                'response' => $responseData,
                'microservice' => true,
            ],
        ]);
    }

    private function sanitize(array &$array)
    {
        $sanitizeKeys = array_map('strtolower', $this->sanitizeKeys);
        foreach ($array as $key => $value) {
            if (in_array(strtolower($key), $sanitizeKeys)) {
                $array[$key] = '*** FILTER ***';
            }

            if (is_array($value)) {
                $this->sanitize($value);
                $array[$key] = $value;
            }
        }
    }
}

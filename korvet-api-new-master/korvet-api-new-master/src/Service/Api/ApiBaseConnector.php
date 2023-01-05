<?php

namespace App\Service\Api;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\HttpClient;
use App\Service\Api\ApiAuthService;
use App\Model\Env;

abstract class ApiBaseConnector
{
    /**
     * @var ApiAuthService
     */
    protected ApiAuthService $apiAuthService;
    /**
     * @var HttpClientInterface
     */
    public HttpClientInterface $httpClientInterface;

    public string $apiUrl;
    public string $apiAuthUrl;
    public string $apiClientId;
    public string $apiClientSecret;
    public string $apiLogin;
    public string $apiPassword;

    public function __construct(ApiAuthService $apiAuthService)
    {
        $this->apiAuthService = $apiAuthService;
        $this->initialize();
    }

    protected function initialize()
    {
        $this->httpClientInterface = HttpClient::create(['verify_peer' => false, 'verify_host' => false]);
        $this->apiUrl = Env::getenv('WEB_SOCKET_API_URL');
        $this->apiAuthUrl = Env::getenv('WEB_SOCKET_API_AUTH');
        $this->apiLogin = Env::getenv('WEB_SOCKET_API_LOGIN');
        $this->apiPassword = Env::getenv('WEB_SOCKET_API_PASSWORD');
        $this->apiClientId = '';
        $this->apiClientSecret = '';
    }
    protected function getErrorMessage($errors): string
    {
        $message = '';
        foreach($errors['fields'] as $key => $value) {
            foreach($value as $errorData) {
                $message .= "{$key}: {$errorData['message']};";
            }
        }
        return $message;
    }
}

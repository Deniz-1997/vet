<?php

namespace App\Service\CasApi;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\HttpClient;
use App\Interfaces\CAS\CasApiAuthInterface;

abstract class ApiBaseConnector
{
    /**
     * @var CasApiAuthInterface
     */
    protected CasApiAuthInterface $casApiAuthService;
    /**
     * @var HttpClientInterface
     */
    public HttpClientInterface $httpClientInterface;

    public ?string $apiUrl;
    public ?string $apiAuthUrl;
    public ?string $apiClientId;
    public ?string $casApiClientSecret;
    public ?string $apiLogin;
    public ?string $apiPassword;

    public function __construct(CasApiAuthInterface $casApiAuthService)
    {
        $this->casApiAuthService = $casApiAuthService;
        $this->initialize();
    }

    protected function initialize()
    {
        $this->httpClientInterface = HttpClient::create(['verify_peer' => false, 'verify_host' => false]);
        $this->apiUrl = getenv('CAS_API_URL');
        $this->apiAuthUrl = getenv('CAS_API_AUTH');
        $this->apiClientId = getenv('CAS_API_CLIENT_ID');
        $this->casApiClientSecret = getenv('CAS_API_CLIENT_SECRET');
        $this->apiLogin = getenv('CAS_API_LOGIN');
        $this->apiPassword = getenv('CAS_API_PASSWORD');
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

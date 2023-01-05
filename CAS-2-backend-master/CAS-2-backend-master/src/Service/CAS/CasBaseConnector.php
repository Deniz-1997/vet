<?php

namespace App\Service\CAS;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\HttpClient;

abstract class CasBaseConnector
{
    /**
     * @var HttpClientInterface
     */
    protected HttpClientInterface $httpClientInterface;

    protected string $casLogin;
    protected string $casPassword;
    protected string $casExcelUrl;
    protected string $casAuthUrl;

    public function __construct()
    {
        //TODO Включить при работе с боевым сервером КАС!!!!
       /*  if (getenv('APP_ENV') === 'dev') {
            $this->httpClientInterface = HttpClient::create(['verify_peer' => false, 'verify_host' => false]);
        } else {
            $this->httpClientInterface = HttpClient::create();
        } */
        $this->httpClientInterface = HttpClient::create(['verify_peer' => false, 'verify_host' => false]);
        $this->casExcelUrl = getenv('CAS_EXCEL_UPLOAD');
        $this->casLogin = getenv('CAS_LOGIN');
        $this->casPassword = getenv('CAS_PASSWORD');
        $this->casAuthUrl = getenv('CAS_AUTH');
    }
}

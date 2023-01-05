<?php

namespace App\Packages\Client;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Exception\ApiException;
use App\Service\Trace\Tracer;

class VaultClient extends Client
{
    /**
     * VaultClient constructor.
     * @param array           $config
     */
    public function __construct(array $config = [])
    {
        if ($baseUri = getenv('VAULT_SERVER')) {
            $config['base_uri'] = $baseUri;
        }
        
        parent::__construct($config);
    }

    /**
     * @return string
     */
    private function getToken(): ?string
    {
        if ($token = getenv('VAULT_TOKEN')) {
            //Временно для уже запущенных сервисов
            return $token;
        }

        Tracer::getInstance()->createSpan('vault_token', 'Vault get approle token', 'CLIENT', ['endpoint' => getenv('VAULT_SERVER')]);
        $response = parent::request('POST', '/v1/auth/approle/login', [
            'json' => ['role_id' => getenv('VAULT_ROLE_ID'), 'secret_id' => getenv('VAULT_SECRET_ID')],
            'verify' => false,
        ])->getBody()->getContents();
        Tracer::getInstance()->finishSpan('vault_token');

        $token = json_decode($response, true)['auth']['client_token'] ?? null;

        return $token;
    }

    public function request($method = '', $uri = '', array $options = []): ResponseInterface
    {
        $options['headers'] = $options['headers'] ?? [];
        $options['headers']['X-Vault-Token'] = $this->getToken();
        $options['verify'] = false;

        return parent::request($method, $uri, $options);
    }
}

<?php

namespace App\Packages\Security;

use App\Model\Env;
use App\Packages\Client\VaultClient;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use ParagonIE\Paseto\Keys\SymmetricKey;
use Psr\Log\LoggerInterface;
use App\Service\Trace\Tracer;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

/**
 * Class SecretProvider
 */
class SecretStorageProvider extends VaultClient
{
    const DEFAULT_LIFE_TIME = 60 * 60 * 24 * 7;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger, array $config = [])
    {
        parent::__construct($config);
        $this->logger = $logger;
    }

    /**
     * Get secret for paseto token from Vault storage
     *
     * @return mixed|null|string
     *
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function findEncodingUserKey(): ?string
    {
        $cache = new FilesystemAdapter('', self::DEFAULT_LIFE_TIME);
        $cacheKey = md5(date('Y:m') . md5(Env::getenv('VAULT_TOKEN')));

        if (!$cache->hasItem($cacheKey)) {

            Tracer::getInstance()->createSpan('vault', 'Vault FindEncodingUserKey', 'CLIENT', ['endpoint' => Env::getenv('VAULT_SERVER')]);
            $secretPath = Env::getenv('VAULT_SECRET_PATH') ? Env::getenv('VAULT_SECRET_PATH') : 'secret/paseto';

            $response = $this->request(
                'GET',
                '/v1/'.$secretPath
            );

            if ($response->getStatusCode() === 200) {
                $result = json_decode($response->getBody()->getContents(), true);
                $key = $result['data']['key'];
                $cache->save($key);
            } else {
                throw new Exception(sprintf('Request to vault server failed with status %s', $response->getStatusCode()));
            }
            Tracer::getInstance()->finishSpan('vault');

        } else {
            $key = $cache->save($cacheKey);
        }

        return $key;
    }
}

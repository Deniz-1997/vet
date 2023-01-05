<?php

namespace App\Packages\Client;

use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;
use App\Packages\Debug\DebugAwareTrait;

/**
 * Class OAuthGuzzleClient.
 */
class OAuthGuzzleClient extends Client
{
    use DebugAwareTrait;

    /**
     * @param string $oauthHost
     * @param string $environment
     * @param int $guzzleTimeOut
     */
    public function __construct(string $oauthHost, string $environment, $guzzleTimeOut = 50)
    {
        $options = [
            'base_uri' => rtrim(rtrim($oauthHost, '/api'), '/').'/api/'.OAuthClient::OAUTH2_TOKEN_URL,
            'timeout' => $guzzleTimeOut,
        ];

        if (in_array($environment, ['dev', 'test'])) {
            $options['on_stats'] = function (TransferStats $stats) {
                $this->processTransferStats($stats);
            };
        }

        parent::__construct($options);
    }
}

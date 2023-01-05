<?php

namespace App\Packages\Client;

use GuzzleHttp\Client;

/**
 * Class ElasticSearchClient
 */
class ElasticSearchClient extends Client
{
    /**
     * ElasticSearchClient constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config = array_merge($config, [
            'verify' => false,
            'base_uri' => getenv('ELASTIC_SEARCH_HOST')
        ]);

        $config['headers']['Authorization'] = getenv('ELASTIC_SEARCH_AUTHORIZATION');

        parent::__construct($config);
    }
}

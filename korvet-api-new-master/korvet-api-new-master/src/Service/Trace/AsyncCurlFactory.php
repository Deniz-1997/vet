<?php

namespace App\Service\Trace;

use Zipkin\Reporters\Http\ClientFactory;

class AsyncCurlFactory implements ClientFactory
{
    /**
     * {@inheritdoc}
     */
    public function build(array $options = []): callable
    {
        return function ($payload) use ($options) {
            $requiredHeaders = [
                'Content-Type' => 'application/json',
                'Content-Length' => strlen($payload),
            ];
            $additionalHeaders = (isset($options['headers']) ? $options['headers'] : []);
            $headers = array_merge($additionalHeaders, $requiredHeaders);

            $cmd = 'curl -X POST ';
            foreach ($headers as $key => $value) {
                $cmd .= '-H ' . escapeshellarg($key.': '.$value). ' ';
            }
            $cmd .= '-d ' . escapeshellarg($payload) . ' ';
            $cmd .= escapeshellarg($options['endpoint_url']) . ' ';
            $cmd .= '-m 5 ';
            $cmd .= '> /dev/null 2>&1 &';

            exec($cmd, $return, $status);
        };
    }
}

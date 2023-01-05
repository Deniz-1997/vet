<?php

namespace App\Packages\Debug;

use GuzzleHttp\TransferStats;
use Psr\Http\Message\MessageInterface;
use Psr\Log\LoggerInterface;

/**
 * DebugAwareTrait.
 */
trait DebugAwareTrait
{
    /** @var array */
    private array $sentRequests = [];

    /** @var LoggerInterface|null */
    private ?LoggerInterface $logger;

    /** @var array */
    private array $jsonContentTypes = [
        'application/json',
    ];

    /**
     * @param LoggerInterface|null $logger
     */
    public function setLogger(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public function getSentRequests(): array
    {
        return $this->sentRequests;
    }

    /**
     * @param TransferStats $stats
     */
    private function processTransferStats(TransferStats $stats)
    {
        // Rewind request body stream to the start
        $stats->getRequest()->getBody()->rewind();

        $body = null;
        if ($stats->getRequest()->getBody()->getSize() > 0) {
            $body = $stats->getRequest()->getBody()->getContents();
        }

        $headers = $stats->getRequest()->getHeaders();
        ksort($headers);

        $transferInfo = [
            'request' => [
                'method' => $stats->getRequest()->getMethod(),
                'uri' => (string) $stats->getEffectiveUri(),
                'body' => $body,
                'headers' => $headers,
                'time' => $stats->getTransferTime(),
                'body_is_json' => $this->checkJsonContentType($stats->getRequest()),
            ],
            'response' => null,
        ];

        if ($stats->hasResponse()) {
            // Rewind response body stream to the start
            $stats->getResponse()->getBody()->rewind();

            $headers = $stats->getResponse()->getHeaders();
            ksort($headers);

            $transferInfo['response'] = [
                'status_code' => $stats->getResponse()->getStatusCode(),
                'headers' => $headers,
                'body' => $stats->getResponse()->getBody()->getContents(),
                'body_is_json' => $this->checkJsonContentType($stats->getResponse()),
            ];
        }

        $this->sentRequests[] = $transferInfo;
    }

    /**
     * @param MessageInterface $message
     *
     * @return bool
     */
    private function checkJsonContentType(MessageInterface $message): bool
    {
        return $message->hasHeader('Content-Type') && in_array($message->getHeader('Content-Type')[0], $this->jsonContentTypes);
    }
}

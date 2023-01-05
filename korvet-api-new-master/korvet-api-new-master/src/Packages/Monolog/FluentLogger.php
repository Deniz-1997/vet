<?php

namespace App\Packages\Monolog;

use Fluent\Logger\Entity;
use Fluent\Logger\PackerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class FluentLogger
 */
class FluentLogger extends \Fluent\Logger\FluentLogger
{
    /** @var LoggerInterface */
    private $alternativeLogger;

    public function __construct(string $host = \Fluent\Logger\FluentLogger::DEFAULT_ADDRESS, int $port = \Fluent\Logger\FluentLogger::DEFAULT_LISTEN_PORT, array $options = array(), PackerInterface $packer = null)
    {
        $options['socket_timeout'] = 1;
        $options['connection_timeout'] = 1;

        parent::__construct($host, $port, $options, $packer);
    }

    /**
     * @required
     * @param LoggerInterface $alternativeLogger
     */
    public function setAlternativeLogger(LoggerInterface $alternativeLogger)
    {
        $this->alternativeLogger = $alternativeLogger;
    }

    /**
     * @return void
     */
    protected function connect()
    {
        try {
            parent::connect();
        } catch (\Exception $exception) {
            $this->alternativeLogException($exception);
        }
    }

    /**
     * @param string $tag
     * @param array $data
     * @return bool
     */
    public function post($tag, array $data): bool
    {
        try {
            return parent::post($tag, $data);
        } catch (\Exception $exception) {
            $this->alternativeLogException($exception);
        }

        return false;
    }

    /**
     * @param Entity $entity
     * @return bool
     */
    public function post2(Entity $entity)
    {
        try {
            return parent::post2($entity);
        } catch (\Exception $exception) {
            $this->alternativeLogException($exception);
        }

        return false;
    }

    /**
     * @param \Exception $exception
     */
    private function alternativeLogException(\Exception $exception)
    {
        try {
            $this->alternativeLogger->error($exception->getMessage(), ['exception' => $exception]);
        } catch (\Exception $exception) {
            @error_log(sprintf('%s %s', $exception->getMessage(), $exception->getTraceAsString()), 0);
        }
    }
}

<?php

namespace App\Packages\Handler;

use Exception;
use Fluent\Logger\Entity;
use Fluent\Logger\FluentLogger;
use LogicException;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Psr\Log\InvalidArgumentException;

/**
 * Class FluentdHandler
 * @package App\Packages\Handler
 */
class FluentdHandler extends AbstractProcessingHandler
{
    const DEFAULT_TAG_FORMAT = '{{channel}}.{{level_name}}';

    protected static array $psr3Levels = [
        Logger::DEBUG => LOG_DEBUG,
        Logger::INFO => LOG_INFO,
        Logger::NOTICE => LOG_NOTICE,
        Logger::WARNING => LOG_WARNING,
        Logger::ERROR => LOG_ERR,
        Logger::CRITICAL => LOG_CRIT,
        Logger::ALERT => LOG_ALERT,
        Logger::EMERGENCY => LOG_EMERG,
    ];

    /** @var FluentLogger */
    protected FluentLogger $logger;
    /** @var string */
    protected string $tagFormat = self::DEFAULT_TAG_FORMAT;
    /** @var bool */
    protected bool $exceptions = true;

    /**
     * @param FluentLogger $logger An instance of FluentdLogger
     * @param int          $level  The minimum logging level at which this handler will be triggered
     * @param bool         $bubble Whether the messages that are handled can bubble up the stack or not
     */
    public function __construct(FluentLogger $logger, $level = Logger::DEBUG, $bubble = true) {
        $this->logger = $logger;
        parent::__construct($level, $bubble);
    }

    /**
     * @return FluentLogger
     */
    public function getLogger(): FluentLogger
    {
        return $this->logger;
    }

    /**
     * @param string $tagFormat
     */
    public function setTagFormat(string $tagFormat)
    {
        $this->tagFormat = $tagFormat;
    }

    /**
     * @param bool $exceptions
     */
    public function setExceptions(bool $exceptions)
    {
        $this->exceptions = (bool) $exceptions;
    }

    /**
     * {@inheritdoc}
     */
    public function close(): void
    {
        $this->logger->close();
    }

    /**
     * @param string|int $level Level number (monolog)
     * @throws InvalidArgumentException
     * @return int
     */
    public static function toPsr3Level($level): int
    {
        if (isset(static::$psr3Levels[$level])) {
            return static::$psr3Levels[$level];
        }

        throw new InvalidArgumentException(sprintf(
            'Level "%s" is not defined, use one among "%s".',
            $level,
            implode('", "', array_keys(static::$psr3Levels))
        ));
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    protected function write(array $record): void
    {
        unset($record['formatted']);
        $record['level'] = static::toPsr3Level($record['level']);
        try {
            $this->logger->post2(new Entity(
                $this->buildTag($record),
                $record,
                $record['datetime']->getTimestamp()
            ));
        } catch (Exception $e) {
            @error_log(sprintf('%s %s', $e->getMessage(), $e->getTraceAsString()), 0);
        }
    }

    /**
     * @param array $record
     *
     * @throws LogicException
     *
     * @return string
     */
    protected function buildTag(array $record): string
    {
        $tag = $this->tagFormat;
        if (!preg_match_all('/\{\{(.*?)\}\}/', $tag, $matches)) {
            return $tag;
        }
        /** @var array[] $matches */
        foreach ($matches[1] as $match) {
            if (isset($record[$match])) {
                $tag = str_replace("{{{$match}}}", $record[$match], $tag);
                continue;
            }
            throw new LogicException(sprintf('No such field "%s" in the record', $match));
        }
        return $tag;
    }
}

<?php

namespace App\Packages\Annotation\Enqueue;

/**
 * Class ConsumerParameters
 * @Annotation
 */
class ConsumerParameters
{
    /** @var bool */
    public bool $noLocal = false;

    /** @var bool */
    public bool $noAck = false;

    /** @var bool */
    public bool $exclusive = false;

    /** @var bool */
    public bool $noWait = false;

    /** @var array */
    public array $arguments = [];
}

<?php

namespace App\Packages\Annotation\Enqueue;

/**
 * Class Produce
 * @Annotation
 */
class Produce
{
    /** @var string */
    public string $exchange;

    /** @var string */
    public string $routingKey;

    /** @var string */
    public string $queue;
}

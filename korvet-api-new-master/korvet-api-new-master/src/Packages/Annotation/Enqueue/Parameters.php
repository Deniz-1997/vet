<?php

namespace App\Packages\Annotation\Enqueue;

/**
 * Class Parameters
 * @Annotation
 */
class Parameters
{
    /**
     * @var bool
     */
    public bool $passive = false;

    /**
     * @var bool
     */
    public bool $durable = true;

    /**
     * @var bool
     */
    public bool $autoDelete = false;

    /**
     * @var array
     */
    public array $arguments = [];
}

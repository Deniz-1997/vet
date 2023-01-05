<?php

namespace App\Packages\Annotation\Enqueue;

/**
 * Class QueueParameters
 * @Annotation
 */
class QueueParameters extends Parameters
{
    /** @var bool */
    public bool $exclusive = false;
}

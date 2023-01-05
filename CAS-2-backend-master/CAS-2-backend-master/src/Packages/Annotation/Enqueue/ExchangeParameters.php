<?php

namespace App\Packages\Annotation\Enqueue;

/**
 * Class ExchangeParameters
 * @Annotation
 */
class ExchangeParameters extends Parameters
{
    /** @var bool */
    public bool $internal = false;

    /** @var string */
    public string $type = 'topic';
}

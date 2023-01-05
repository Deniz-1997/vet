<?php

namespace App\Packages\Annotation\Enqueue;

/**
 * @Annotation
 * @Target("CLASS")
 */
class CrudConsume
{
    /**
     * @var Consume[]
     */
    public $topicsMap;

    /**
     * CrudConsume constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->topicsMap = $values['value'];
    }
}

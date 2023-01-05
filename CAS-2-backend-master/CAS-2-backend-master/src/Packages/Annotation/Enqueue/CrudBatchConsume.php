<?php

namespace App\Packages\Annotation\Enqueue;

/**
 * Class CrudBatchConsume
 *
 * @Annotation
 */
class CrudBatchConsume
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

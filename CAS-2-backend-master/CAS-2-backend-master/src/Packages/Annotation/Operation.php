<?php

namespace App\Packages\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
class Operation
{
    /** @var string */
    public $id;

    /**
     * Operation constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (empty($values['value'])) {
            throw new \LogicException('Operation name not set in annotation @Operation');
        }

        $this->id = $values['value'];
    }
}

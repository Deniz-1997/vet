<?php

namespace App\Packages\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class ExternalEntityReference
{
    /**
     * @var string
     */
    private string $key;

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }
}

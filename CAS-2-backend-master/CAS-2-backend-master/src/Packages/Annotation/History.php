<?php

namespace App\Packages\Annotation;

/**
 * Class History
 * @Annotation
 */
final class History
{
    /**
     * @var string
     */
    public string $alias;

    /**
     * @var string
     */
    public ?string $fieldIdentifier = null;
    /**
     * Relation entities for show log
     * @var array
     */
    public array $relationField;
    /**
     * @var string
     */
    public string $entity;
}

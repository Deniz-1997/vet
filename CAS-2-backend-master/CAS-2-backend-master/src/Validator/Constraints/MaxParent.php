<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class Phone
 * @package App\Validator
 * @Annotation
 */
class MaxParent extends Constraint
{
    public string $message = 'Слишком большой уровень вложенности';
    public int $maxParentLevel = 4;

    public function validatedBy()
    {
        return static::class . 'Validator';
    }
}

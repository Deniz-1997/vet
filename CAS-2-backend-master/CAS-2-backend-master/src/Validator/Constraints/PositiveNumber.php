<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;


/**
 * Class PositiveNumber
 * @package App\Validator
 * @Annotation
 */

class PositiveNumber extends Constraint
{
    public string $message = 'Вы ввели отрицательное число';

    public function validatedBy()
    {
        return static::class . 'Validator';
    }
}

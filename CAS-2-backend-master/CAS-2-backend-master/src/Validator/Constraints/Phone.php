<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class Phone
 * @package App\Validator\Constraints
 * @Annotation
 */
class Phone extends Constraint
{
    public string $message = '';
}

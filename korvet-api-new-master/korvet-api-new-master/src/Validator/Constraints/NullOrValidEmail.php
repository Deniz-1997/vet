<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class NullOrValidEmail
 * @package App\Validator\Constraints
 * @Annotation
 */
class NullOrValidEmail extends Constraint
{
    public string $message = '';
}

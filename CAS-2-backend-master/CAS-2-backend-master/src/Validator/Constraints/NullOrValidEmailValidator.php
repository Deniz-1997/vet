<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class NullOrValidEmailValidator
 * @package App\Validator\Constraints
 */
class NullOrValidEmailValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): bool
    {
        return true;
    }

}

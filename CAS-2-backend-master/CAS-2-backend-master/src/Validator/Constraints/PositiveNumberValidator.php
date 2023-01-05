<?php

namespace App\Validator\Constraints;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PositiveNumberValidator extends ConstraintValidator
{
    private $propertyAccessor;

    public function __construct(PropertyAccessorInterface $propertyAccessor)
    {
        $this->propertyAccessor = $propertyAccessor;
    }
    public function validate($value, Constraint $constraint)
    {
        if ($value < 0 )
        {
            $this->context->buildViolation($constraint->message)->addViolation();;
        }
    }
}

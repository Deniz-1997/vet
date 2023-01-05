<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class MaxParentValidator extends ConstraintValidator
{
    private $propertyAccessor;

    public function __construct(PropertyAccessorInterface $propertyAccessor)
    {
        $this->propertyAccessor = $propertyAccessor;
    }
    public function validate($value, Constraint $constraint)
    {
        if ($this->getMaxParentCount($value, 0, $constraint->maxParentLevel)) {
            $this->context->buildViolation($constraint->message)->addViolation();;
        }
    }

    private function getMaxParentCount($value, $currentLevel, $maxParentLevel): bool
    {
        if (($currentLevel)  >= $maxParentLevel) {
            return true;
        }
        $parent = null;
        if ($this->propertyAccessor->isReadable($value, 'parent')){
            $parent = $this->propertyAccessor->getValue($value, 'parent');
        }

        if (isset($parent) && $currentLevel < $maxParentLevel) {
            $currentLevel++;
            return $this->getMaxParentCount($parent, $currentLevel, $maxParentLevel);
        }
        return false;
    }
}

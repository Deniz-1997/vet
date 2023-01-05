<?php

namespace App\Validator;

use App\Enum\BaseEnum;
use App\Interfaces\EnumInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class ConstraintEnumValidator
 */
class ConstraintEnumValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /** @var BaseEnum $value */
        if (!$value instanceof EnumInterface) {
            return;
        }

        if ($value->code === null) {
            $value->code = 'NULL';
        }

        $choices = array_keys($value::choices());
        $allowValues = implode(', ', $value::choices());
        if (!in_array($value->code, $choices, true)) {
            $this->context->buildViolation('error.validation.enum')
                ->setParameter('{{class}}', get_class($value))
                ->setParameter('{{value}}', $value->code)
                ->setParameter('{{allow_values}}', $allowValues)
                ->atPath('code')
                ->addViolation();
        }
    }
}

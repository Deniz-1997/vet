<?php


namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * Class ConstraintEnum
 */
class ConstraintEnum extends Constraint
{
    public string $message = 'error.validation.enum';

    /**
     * @inheritDoc
     * @return array|string
     */
    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}

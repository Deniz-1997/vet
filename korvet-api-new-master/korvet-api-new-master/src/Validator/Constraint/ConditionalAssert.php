<?php

namespace App\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use App\Validator\ConditionalValidator;

/**
 * Class Conditional
 * @Annotation
 */
class ConditionalAssert extends Constraint
{
    /**
     * @var Constraint[]
     */
    public $constraints;

    /**
     * @var string
     */
    public string $expressionCondition;

    /**
     * Conditional constructor.
     * @param $values
     */
    public function __construct($values)
    {
        $values = $values['value'];

        if (count($values) < 2) {
            throw new \RuntimeException('Expected expression string & validators');
        }

        if (!isset($values[0]) || !is_string($values[0])) {
            throw new \RuntimeException('First argument expression language expected string got '.gettype($values[0]));
        }

        $this->expressionCondition = $values[0];
        unset($values[0]);
        $this->constraints = $values;

        parent::__construct([]);
    }

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return ConditionalValidator::class;
    }
}

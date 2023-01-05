<?php

namespace App\Validator;

use LogicException;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use App\Validator\Constraint\ConditionalAssert;
use Symfony\Component\Validator\Validator\ContextualValidatorInterface;

class ConditionalValidator extends ConstraintValidator
{
    /**
     * @var ExpressionLanguage
     */
    private ?ExpressionLanguage $expressionLanguage=null;

    /**
     * @param object $value
     * @param Constraint $constraint
     * @return ContextualValidatorInterface|void
     */
    public function validate($value, Constraint $constraint): ?ContextualValidatorInterface
    {
        if (!$constraint instanceof ConditionalAssert) {
            throw new UnexpectedTypeException($constraint, ConditionalAssert::class);
        }

        $variables = [];
        $variables['value'] = $value;
        $variables['this'] = $this->context->getObject();

        if (!$this->getExpressionLanguage()->evaluate($constraint->expressionCondition, $variables)) {
            return null;
        }

        return $this->context
            ->getValidator()
            ->inContext($this->context)
            ->validate($value, $constraint->constraints);
    }

    /**
     * @return ExpressionLanguage
     */
    private function getExpressionLanguage(): ExpressionLanguage
    {
        if (null === $this->expressionLanguage) {
            if (!class_exists('Symfony\Component\ExpressionLanguage\ExpressionLanguage')) {
                throw new LogicException('Unable to use expressions as the Symfony ExpressionLanguage component is not installed.');
            }
            $this->expressionLanguage = new ExpressionLanguage();
        }

        return $this->expressionLanguage;
    }
}

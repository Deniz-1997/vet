<?php

namespace App\Filter;

use Doctrine\ORM\Query\Expr;

/**
 * Trait FilterConditionHelperTrait
 */
trait FilterConditionHelperTrait
{
    public function createExpression($columnWithoutType, $filterValue, $operator = null)
    {
        $operator = $operator ?? '=';

        $expr = null;
        $exprBuilder = new Expr();
        if ($filterValue === 'null'){
            $filterValue = null;
        }
        switch ($operator) {
            case '=':
                if (is_null($filterValue)) {
                    $exprBuilder->isNull($columnWithoutType);
                }else{
                    if (is_array($filterValue)) {
                        $expr = $exprBuilder->in($columnWithoutType, $filterValue);
                    } else {
                        $expr = $exprBuilder->eq($columnWithoutType, is_string($filterValue) ? $exprBuilder->literal($filterValue) : $filterValue);
                    }
                }
                break;
            case '!':
                if (!is_array($filterValue)) {
                    if (is_bool($filterValue)) {
                        switch ($filterValue) {
                            case true:
                                $expr = $exprBuilder->lt($columnWithoutType, $filterValue);
                                break;
                            case false:
                                $expr = $exprBuilder->gt($columnWithoutType, $filterValue);
                                break;
                        }
                    } else {
                        $expr = $exprBuilder->neq($columnWithoutType, is_string($filterValue) ? $exprBuilder->literal($filterValue) : $filterValue);
                    }
                } else {
                    foreach ($filterValue as $val) {
                        $expr = $exprBuilder = $this->createExpression($columnWithoutType, $val, $operator);
                    }
                }
                break;
            case '!=':
                if (is_array($filterValue)) {
                    $expr = $exprBuilder->notIn($columnWithoutType, $filterValue);
                } else {
                    $expr = $exprBuilder->gt($columnWithoutType, $filterValue);
                }
                break;
            case '>=':
                if (is_array($filterValue)) {
                    $expr = $exprBuilder->notIn($columnWithoutType, $filterValue);
                } else {
                    $expr = $exprBuilder->gte($columnWithoutType, $filterValue);
                }
                break;
            case '<=':
                if (is_array($filterValue)) {
                    $expr = $exprBuilder->notIn($columnWithoutType, $filterValue);
                } else {
                    $expr = $exprBuilder->lte($columnWithoutType, $filterValue);
                }
                break;
            case '>':
                if (is_array($filterValue)) {
                    $expr = $exprBuilder->notIn($columnWithoutType, $filterValue);
                } else {
                    $expr = $exprBuilder->gt($columnWithoutType, $filterValue);
                }
                break;
            case '<':
                if (!is_array($filterValue)) {
                    $expr = $exprBuilder->lt($columnWithoutType, $filterValue);
                } else {
                    foreach ($filterValue as $val) {
                        $expr = $this->createExpression($columnWithoutType, $val, $operator);
                    }
                }
                break;
        }

        return $expr;
    }
}

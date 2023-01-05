<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class IsNumberAndSeriesCorrect
 * @Annotation
 */
class IsNumberAndSeriesCorrect extends Constraint
{
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

}

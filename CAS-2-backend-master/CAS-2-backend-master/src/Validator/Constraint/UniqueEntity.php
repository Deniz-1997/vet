<?php

namespace App\Validator\Constraint;

use App\Validator\UniqueEntityValidator;

/**
 * Class UniqueEntity
 * @package App\Validator\Constraint
 *
 * @Annotation
 * @Target({"CLASS", "ANNOTATION"})
 */
class UniqueEntity extends \Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity
{
    public $service = UniqueEntityValidator::class;
}

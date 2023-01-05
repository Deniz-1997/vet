<?php

namespace App\Packages\DBAL\Types;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\Annotation\EnumAnnotation;
use App\Packages\DBAL\Types\Enum;


/**
 * Class AnimalGenderEnum
 * @EnumAnnotation
 * @package App\Packages\DBAL\Types
 */
class AnimalGenderEnum extends Enum
{
    /**
     * @var string
     * @Groups({"expose"})
     */
    protected string $enumClass = AnimalGenderEnum::class;

    public const MALE = 'male';
    public const FEMALE = 'female';

    /**
     * @var array
     */
    protected static $choices = [
        self::MALE => 'enum.animal_gender.male',
        self::FEMALE => 'enum.animal_gender.female',
    ];
}

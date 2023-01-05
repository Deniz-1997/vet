<?php

namespace App\Enum;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\Annotation\EnumAnnotation;
use App\Packages\DBAL\Types\Enum;

/**
 * @EnumAnnotation()
 * Class WildAnimalFileTypeEnum
 */
class WildAnimalFileTypeEnum extends Enum
{
    /**
     * @var string
     * @Groups({"expose"})
     */
    protected string $enumClass = self::class;

    public const PHOTO = 'PHOTO';
    public const DEATH_PHOTO = 'DEATH_PHOTO';

    /**
     * @var array Значения для перевода на язык системы
     */
    protected static $choices = [
        self::PHOTO => 'wild_animal.file_type_enum.photo',
        self::DEATH_PHOTO => 'wild_animal.file_type_enum.death_photo'
    ];
}

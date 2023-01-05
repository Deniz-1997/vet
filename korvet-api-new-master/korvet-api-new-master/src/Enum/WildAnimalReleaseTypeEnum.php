<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 29.07.19
 * Time: 12:10
 */

namespace App\Enum;

use App\Packages\Annotation\EnumAnnotation;
use App\Packages\DBAL\Types\Enum;

/**
 * @EnumAnnotation()
 * Class WildAnimalReleaseTypeEnum
 */
class WildAnimalReleaseTypeEnum extends Enum
{
    protected string $enumClass = self::class;

    public const SHELTER = 'SHELTER';
    public const STREET = 'STREET';
    public const NEW_OWNERS = 'NEW_OWNERS';
    public const EUTHANASIA = 'EUTHANASIA';
    public const NATURAL_DEATH = 'NATURAL_DEATH';
    public const OLD_OWNERS = 'OLD_OWNER';

    protected static $choices = [
        self::SHELTER => 'wild_animal_release_type.shelter',
        self::STREET => 'wild_animal_release_type.street',
        self::NEW_OWNERS => 'wild_animal_release_type.new_owners',
        self::EUTHANASIA => 'wild_animal_release_type.euthanasia',
        self::NATURAL_DEATH => 'wild_animal_release_type.natural_death',
        self::OLD_OWNERS => 'wild_animal_release_type.old_owners'
    ];
}

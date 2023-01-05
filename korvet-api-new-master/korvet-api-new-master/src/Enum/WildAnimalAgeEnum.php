<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 28.07.19
 * Time: 16:34
 */

namespace App\Enum;

use App\Packages\Annotation\EnumAnnotation;
use App\Packages\DBAL\Types\Enum;

/**
 * Class WildAnimalAgeEnum
 * @EnumAnnotation()
 */
class WildAnimalAgeEnum extends Enum
{
    protected string $enumClass = self::class;

    public const UNDER_ONE_YEAR = 'UNDER_ONE_YEAR';
    public const ONE_YEAR = 'ONE_YEAR';
    public const TWO_YEARS = 'TWO_YEARS';
    public const THREE_YEARS = 'THREE_YEARS';
    public const FOUR_YEARS = 'FOUR_YEARS';
    public const FIVE_YEARS = 'FIVE_YEARS';
    public const GREATER_THAN_FIVE_YEARS = 'GREATER_THAN_FIVE_YEARS';

    /**
     * @var array Значения для перевода на язык системы
     */
    protected static $choices = [
        self::UNDER_ONE_YEAR => 'wild_animal.age.under_one_year',
        self::ONE_YEAR => 'wild_animal.age.one_year',
        self::TWO_YEARS => 'wild_animal.age.two_years',
        self::THREE_YEARS => 'wild_animal.age.three_years',
        self::FOUR_YEARS => 'wild_animal.age.four_years',
        self::FIVE_YEARS => 'wild_animal.age.five_years',
        self::GREATER_THAN_FIVE_YEARS => 'wild_animal.age.greater_than_five_years',
    ];
}

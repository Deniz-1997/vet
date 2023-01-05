<?php

namespace App\Packages\DBAL\Types;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\Annotation\EnumAnnotation;
use App\Packages\DBAL\Types\Enum;

/**
 * Class VatRateEnum
 * @EnumAnnotation
 * @package App\Packages\DBAL\Types
 */
class VatRateEnum extends Enum
{
    /**
     * @var string
     * @Groups({"expose"})
     */
    protected string $enumClass = VatRateEnum::class;

    public const NONE = 'NONE';
    public const VAT_0 = 'VAT_0';
    public const VAT_10 = 'VAT_10';
    public const VAT_18 = 'VAT_18';
    public const VAT_110 = 'VAT_110';
    public const VAT_118 = 'VAT_118';
    public const VAT_20 = 'VAT_20';
    public const VAT_120 = 'VAT_120';

    /**
     * @var array
     */
    protected static $choices = [
        self::NONE => 'enum.vat_rate.none',
        self::VAT_0 => 'enum.vat_rate.vat_0',
        self::VAT_10 => 'enum.vat_rate.vat_10',
        self::VAT_18 => 'enum.vat_rate.vat_18',
        self::VAT_110 => 'enum.vat_rate.vat_110',
        self::VAT_118 => 'enum.vat_rate.vat_118',
        self::VAT_20 => 'enum.vat_rate.vat_20',
        self::VAT_120 => 'enum.vat_rate.vat_120',
    ];

    /**
     * @return array
     */
    public static function choices(): array
    {
        return self::$choices;
    }
}

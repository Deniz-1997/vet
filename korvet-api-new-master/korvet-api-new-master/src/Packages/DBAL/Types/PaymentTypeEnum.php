<?php

namespace App\Packages\DBAL\Types;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\Annotation\EnumAnnotation;
use App\Packages\DBAL\Types\Enum;
use App\Traits\ChoicesFromEnvTrait;

/**
 * Class PaymentTypeEnum
 * @EnumAnnotation
 * @package App\Packages\DBAL\Types
 */
class PaymentTypeEnum extends Enum
{
    use ChoicesFromEnvTrait;

    private const ENV_VAR = 'PAYMENT_TYPE_ENUM';

    /**
     * @var string
     * @Groups({"expose"})
     */
    protected string $enumClass = PaymentTypeEnum::class;

    public const CASH = 'CASH';
    public const ELECTRONICALLY = 'ELECTRONICALLY';
    public const PREPAID = 'PREPAID';
    public const CREDIT = 'CREDIT';
    public const OTHER = 'OTHER';

    /**
     * @var array
     */
    protected static $choices = [
        self::NULLABLE => 'enum.payment_type.nullable',
        self::CASH => 'enum.payment_type.cash',
        self::ELECTRONICALLY => 'enum.payment_type.electronicaly',
        self::PREPAID => 'enum.payment_type.prepaid',
        self::CREDIT => 'enum.payment_type.credit',
        self::OTHER => 'enum.payment_type.other',
    ];

}

<?php

namespace App\Packages\DBAL\Types;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\Annotation\EnumAnnotation;
use App\Packages\DBAL\Types\Enum;
use App\Traits\ChoicesFromEnvTrait;


/**
 * Class PaymentMethodEnum
 * @EnumAnnotation
 * @package App\Packages\DBAL\Types
 */
class PaymentMethodEnum extends Enum
{
    use ChoicesFromEnvTrait;

    private const ENV_VAR = 'PAYMENT_METHOD_ENUM';

    /**
     * @var string
     * @Groups({"expose"})
     */
    protected string $enumClass = PaymentMethodEnum::class;

    public const FULL_PREPAYMENT = 'FULL_PREPAYMENT';
    public const PREPAYMENT = 'PREPAYMENT';
    public const ADVANCE = 'ADVANCE';
    public const FULL_PAYMENT = 'FULL_PAYMENT';
    public const PARTIAL_PAYMENT = 'PARTIAL_PAYMENT';
    public const CREDIT = 'CREDIT';
    public const CREDIT_PAYMENT = 'CREDIT_PAYMENT';

    /**
     * @var array
     */
    protected static $choices = [
        self::FULL_PREPAYMENT => 'enum.payment_method_type.full_prepayment',
        self::PREPAYMENT => 'enum.payment_method_type.prepayment',
        self::ADVANCE => 'enum.payment_method_type.advance',
        self::FULL_PAYMENT => 'enum.payment_method_type.full_paiment',
        self::PARTIAL_PAYMENT => 'enum.payment_method_type.partial_payment',
        self::CREDIT => 'enum.payment_method_type.credit',
        self::CREDIT_PAYMENT => 'enum.payment_method_type.credit_payment',
    ];

}

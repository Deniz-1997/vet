<?php

namespace App\Packages\DBAL\Types;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\Annotation\EnumAnnotation;
use App\Packages\DBAL\Types\Enum;

/**
 * Class PaymentStateEnum
 * @EnumAnnotation
 * @package App\Packages\DBAL\Types
 */
class PaymentStateEnum extends Enum
{
    /**
     * @var string
     * @Groups({"expose"})
     */
    protected string $enumClass = PaymentStateEnum::class;

    public const NOT_PAID = 'NOT_PAID';
    public const PAID = 'PAID';
   

    /**
     * @var array
     */
    protected static $choices = [
        self::NOT_PAID => 'enum.payment_state.not_paid',
        self::PAID => 'enum.payment_state.paid',
    ];

    /**
     * @return array
     */
    public static function choices(): array
    {
        return self::$choices;
    }
}

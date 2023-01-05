<?php

namespace App\Packages\DBAL\Types;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\Annotation\EnumAnnotation;
use App\Packages\DBAL\Types\Enum;


/**
 * Class CashReceiptTypeEnum
 * @EnumAnnotation
 * @package App\Packages\DBAL\Types
 */
class CashReceiptTypeEnum extends Enum
{
    /**
     * @var string
     * @Groups({"expose"})
     */
    protected string $enumClass = CashReceiptTypeEnum::class;

    public const SELL = 'SELL';
    public const BUY = 'BUY';
    public const SELL_RETURN = 'SELL_RETURN';
    public const BUY_RETURN = 'BUY_RETURN';
    public const SELL_CORRECTION = 'SELL_CORRECTION';
    public const BUY_CORRECTION = 'BUY_CORRECTION';
    public const SELL_RETURN_CORRECTION = 'SELL_RETURN_CORRECTION';
    public const BUY_RETURN_CORRECTION = 'BUY_RETURN_CORRECTION';

    /**
     * @var array
     */
    protected static $choices = [
        self::SELL => 'enum.cash_receipt_type.sell',
        self::BUY => 'enum.cash_receipt_type.buy',
        self::SELL_RETURN => 'enum.cash_receipt_type.sell_return',
        self::BUY_RETURN => 'enum.cash_receipt_type.buy_return',
        self::SELL_CORRECTION => 'enum.cash_receipt_type.sell_correction',
        self::BUY_CORRECTION => 'enum.cash_receipt_type.buy_correction',
        self::SELL_RETURN_CORRECTION => 'enum.cash_receipt_type.sell_return_correction',
        self::BUY_RETURN_CORRECTION => 'enum.cash_receipt_type.buy_return_correction',
    ];

}

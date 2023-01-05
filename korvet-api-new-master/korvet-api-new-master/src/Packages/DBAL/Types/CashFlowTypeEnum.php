<?php

namespace App\Packages\DBAL\Types;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\Annotation\EnumAnnotation;
use App\Packages\DBAL\Types\Enum;


/**
 * Class CashFlowTypeEnum
 * @EnumAnnotation
 * @package App\Packages\DBAL\Types
 */
class CashFlowTypeEnum extends Enum
{
    /**
     * @var string
     * @Groups({"expose"})
     */
    protected string $enumClass = CashFlowTypeEnum::class;

    public const CASH_IN = 'CASH_IN';
    public const CASH_OUT = 'CASH_OUT';

    /**
     * @var array
     */
    protected static $choices = [
        self::CASH_IN => 'enum.cash_flow.cash_in',
        self::CASH_OUT => 'enum.cash_flow.cash_out',
    ];

}

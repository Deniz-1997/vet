<?php

namespace App\Packages\DBAL\Types;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\Annotation\EnumAnnotation;
use App\Packages\DBAL\Types\Enum;


/**
 * Class FiscalReceiptStateEnum
 * @EnumAnnotation
 * @package App\Packages\DBAL\Types
 */
class FiscalReceiptStateEnum extends Enum
{
    /**
     * @var string
     * @Groups({"expose"})
     */
    protected string $enumClass = FiscalReceiptStateEnum::class;

    public const NEW = 'NEW';
    public const PRINTING = 'PRINTING';
    public const DONE = 'DONE';
    public const ERROR = 'ERROR';

    /**
     * @var array
     */
    protected static $choices = [
        self::NEW => 'enum.fiscal_receipt_type.new',
        self::PRINTING => 'enum.fiscal_receipt_type.printing',
        self::DONE => 'enum.fiscal_receipt_type.done',
        self::ERROR => 'enum.fiscal_receipt_type.error',
    ];
}

<?php

namespace App\Packages\DBAL\Types;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\Annotation\EnumAnnotation;
use App\Packages\DBAL\Types\Enum;


/**
 * Class ReceiptDeliveryTypeEnum
 * @EnumAnnotation
 * @package App\Packages\DBAL\Types
 */
class ReceiptDeliveryTypeEnum extends Enum
{
    /**
     * @var string
     * @Groups({"expose"})
     */
    protected string $enumClass = ReceiptDeliveryTypeEnum::class;

    public const PRINT = 'PRINT';
    public const EMAIL = 'EMAIL';
    public const PHONE = 'PHONE';

    /**
     * @var array
     */
    protected static $choices = [
        self::PRINT => 'enum.receipt_delivery_type.print',
        self::EMAIL => 'enum.receipt_delivery_type.email',
        self::PHONE => 'enum.receipt_delivery_type.phone',
    ];
}

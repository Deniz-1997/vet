<?php


namespace App\Enum;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\Annotation\EnumAnnotation;
use App\Packages\DBAL\Types\Enum;

/**
 * Class DocumentOperationTypeEnum
 * @EnumAnnotation
 */
class DocumentOperationTypeEnum extends Enum
{
    /**
     * @var string
     * @Groups({"expose"})
     */
    protected string $enumClass = self::class;

    public const IMPORT = 'IMPORT';
    public const EXPENSE = 'EXPENSE';
    public const TRANSFER = 'TRANSFER';
    public const APPOINTMENT = 'APPOINTMENT';
    public const LEAVING = 'LEAVING';
    public const SHOP = 'SHOP';
    public const INVENTORY = 'INVENTORY';
    public const LABORATORY = 'LABORATORY';

    /**
     * @var array Значения для перевода на язык системы
     */
    protected static $choices = [
        self::IMPORT => 'enum.document.operation.import',
        self::EXPENSE => 'enum.document.operation.expense',
        self::TRANSFER => 'enum.document.operation.transfer',
        self::APPOINTMENT => 'enum.document.operation.appointment',
        self::LEAVING => 'enum.document.operation.leaving',
        self::SHOP => 'enum.document.operation.shop',
        self::INVENTORY => 'enum.document.operation.inventory',
        self::LABORATORY => 'enum.document.operation.laboratory',
    ];
}

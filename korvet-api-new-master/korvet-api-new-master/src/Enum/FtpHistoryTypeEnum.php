<?php

namespace App\Enum;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\DBAL\Types\Enum;
use App\Packages\Annotation\EnumAnnotation;

/**
 * @EnumAnnotation()
 * Class FtpHistoryTypeEnum
 */

class FtpHistoryTypeEnum extends Enum
{
    /**
     * @var string
     * @Groups({"expose"})
     */
    protected string $enumClass = FtpHistoryTypeEnum::class;

    public const IMPORT_STOCK = 'IMPORT_STOCK';
    public const IMPORT_STOCK_MOVE = 'IMPORT_STOCK_MOVE';
    public const IMPORT_SERVICE = 'IMPORT_SERVICE';
    public const EXPORT = 'EXPORT';

    /**
     * @var array
     */
    protected static $choices = [
        self::IMPORT_STOCK => 'enum.ftp_history.import_stock',
        self::IMPORT_STOCK_MOVE => 'enum.ftp_history.import_stock_move',
        self::IMPORT_SERVICE => 'enum.ftp_history.import_service',
        self::EXPORT => 'enum.ftp_history.export',
    ];
}

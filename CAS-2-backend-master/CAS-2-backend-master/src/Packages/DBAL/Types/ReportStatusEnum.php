<?php

namespace App\Packages\DBAL\Types;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\Annotation\EnumAnnotation;
use App\Packages\DBAL\Types\Enum;


/**
 * Class ReportStatusEnum
 * @EnumAnnotation
 * @package App\Packages\DBAL\Types
 */
class ReportStatusEnum extends Enum
{
    /**
     * @var string
     * @Groups({"expose"})
     */
    protected string $enumClass = ReportStatusEnum::class;

    public const NEW = 'new';
    public const SENT = 'sent';
    public const RETURNED = 'returned';
    public const DONE = 'done';

    /**
     * @var array
     */
    protected static $choices = [
        self::NEW => 'enum.report_status.new',
        self::SENT => 'enum.report_status.sent',
        self::RETURNED => 'enum.report_status.returned',
        self::DONE => 'enum.report_status.done',
    ];
}

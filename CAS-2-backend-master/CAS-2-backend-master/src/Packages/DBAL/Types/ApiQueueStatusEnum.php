<?php

namespace App\Packages\DBAL\Types;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\Annotation\EnumAnnotation;
use App\Packages\DBAL\Types\Enum;


/**
 * Class ApiQueueStatusEnum
 * @EnumAnnotation
 * @package App\Packages\DBAL\Types
 */
class ApiQueueStatusEnum extends Enum
{
    /**
     * @var string
     * @Groups({"expose"})
     */
    protected string $enumClass = ApiQueueStatusEnum::class;

    public const SAVED = 'saved';
    public const UPLOADED = 'uploaded';
    public const PENDING = 'pending';
    public const FINISHED_WITH_ERRORS = 'finished_with_errors';
    public const FINISHED = 'finished';

    /**
     * @var array
     */
    protected static $choices = [
        self::SAVED => 'enum.queue_status.saved',
        self::UPLOADED => 'enum.queue_status.uploaded',
        self::PENDING => 'enum.queue_status.pending',
        self::FINISHED_WITH_ERRORS => 'enum.queue_status.finished_with_errors',
        self::FINISHED => 'enum.queue_status.finished',
    ];
}

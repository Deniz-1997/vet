<?php

namespace App\Packages\DBAL\Types;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\Annotation\EnumAnnotation;
use App\Packages\DBAL\Types\Enum;


/**
 * Class VaccinationUploadStatusEnum
 * @EnumAnnotation
 * @package App\Packages\DBAL\Types
 */
class VaccinationUploadStatusEnum extends Enum
{
    /**
     * @var string
     * @Groups({"expose"})
     */
    protected string $enumClass = VaccinationUploadStatusEnum::class;

    public const STATUS_UPLOADED = 'uploaded';
    public const STATUS_PENDING = 'pending';
    public const STATUS_FINISHED_WITH_ERRORS = 'with_errors';
    public const STATUS_FINISHED = 'finished';
    public const STATUS_FORMING_RESPONSE = 'forming_response';
    public const STATUS_RESPONSE_FORMED = 'response_formed';
    public const STATUS_RESPONSE_UPLOADED = 'response_uploaded';
    public const STATUS_SYS_ERROR = 'sys_error';

    /**
     * @var array
     */
    protected static $choices = [
        self::STATUS_UPLOADED => 'enum.vaccination_upload_status.uploaded',
        self::STATUS_PENDING => 'enum.vaccination_upload_status.pending',
        self::STATUS_FINISHED_WITH_ERRORS => 'enum.vaccination_upload_status.finished_with_errors',
        self::STATUS_FINISHED => 'enum.vaccination_upload_status.finished',
        self::STATUS_FORMING_RESPONSE => 'enum.vaccination_upload_status.forming_response',
        self::STATUS_RESPONSE_FORMED => 'enum.vaccination_upload_status.response_formed',
        self::STATUS_RESPONSE_UPLOADED => 'enum.vaccination_upload_status.response_uploaded',
        self::STATUS_SYS_ERROR => 'enum.vaccination_upload_status.sys_error',
    ];
}

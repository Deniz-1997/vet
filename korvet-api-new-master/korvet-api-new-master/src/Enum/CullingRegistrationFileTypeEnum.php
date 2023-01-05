<?php

namespace App\Enum;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\Annotation\EnumAnnotation;
use App\Packages\DBAL\Types\Enum;

/**
 * @EnumAnnotation()
 * Class CullingRegistrationFileTypeEnum
 */
class CullingRegistrationFileTypeEnum extends Enum
{
    /**
     * @var string
     * @Groups({"expose"})
     */
    protected string $enumClass = self::class;

    public const PHOTO = 'PHOTO';
    public const VACCINATION = 'VACCINATION';
    public const STERILIZATION = 'STERILIZATION';
    public const TAG = 'TAG';
    public const RELEASE = 'RELEASE';
    public const QUARANTINE = 'QUARANTINE';

    /**
     * @var array Значения для перевода на язык системы
     */
    protected static $choices = [
        self::PHOTO => 'culling_registration.file_type_enum.photo',
        self::VACCINATION => 'culling_registration.file_type_enum.vaccination',
        self::STERILIZATION => 'culling_registration.file_type_enum.sterilization',
        self::TAG => 'culling_registration.file_type_enum.tag',
        self::RELEASE => 'culling_registration.file_type_enum.release',
        self::QUARANTINE => 'culling_registration.file_type_enum.quarantine'
    ];
}

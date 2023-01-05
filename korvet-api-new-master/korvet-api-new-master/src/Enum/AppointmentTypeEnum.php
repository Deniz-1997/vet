<?php

namespace App\Enum;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\DBAL\Types\Enum;

/**
 * Class AppointmentTypeEnum
 */
class AppointmentTypeEnum extends Enum
{

    /**
     * @var string
     * @Groups({"expose"})
     */
    protected string $enumClass = AppointmentTypeEnum::class;

    public const PRIMARY = 'PRIMARY';
    public const SECONDARY = 'SECONDARY';
    public const EVSD = 'EVSD';

    /**
     * @var array
     */
    protected static $choices = [
        self::PRIMARY => 'enum.appointment_type.primary',
        self::SECONDARY => 'enum.appointment_type.secondary',
        self::EVSD => 'enum.appointment_type.evsd',
    ];
}

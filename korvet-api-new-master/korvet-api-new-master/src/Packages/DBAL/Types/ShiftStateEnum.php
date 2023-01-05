<?php

namespace App\Packages\DBAL\Types;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\Annotation\EnumAnnotation;
use App\Packages\DBAL\Types\Enum;


/**
 * Class ShiftStateEnum
 * @EnumAnnotation
 * @package App\Packages\DBAL\Types
 */
class ShiftStateEnum extends Enum
{
    /**
     * @var string
     * @Groups({"expose"})
     */
    protected string $enumClass = ShiftStateEnum::class;

    public const NEW = 'NEW';
    public const OPEN = 'OPEN';
    public const CLOSE = 'CLOSE';

    /**
     * @var array
     */
    protected static $choices = [
        self::NEW => 'enum.shift_state.new',
        self::OPEN => 'enum.shift_state.open',
        self::CLOSE => 'enum.shift_state.close',
    ];

}

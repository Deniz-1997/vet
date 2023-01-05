<?php

namespace App\Packages\DBAL\Types;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\Annotation\EnumAnnotation;
use App\Packages\DBAL\Types\Enum;


/**
 * Class CorrectionTypeEnum
 * @EnumAnnotation
 * @package App\Packages\DBAL\Types
 */
class CorrectionTypeEnum extends Enum
{
    /**
     * @var string
     * @Groups({"expose"})
     */
    protected string $enumClass = CorrectionTypeEnum::class;

    public const SELF = 'SELF';
    public const INSTRUCTION = 'INSTRUCTION';

    /**
     * @var array
     */
    protected static $choices = [
        self::SELF => 'enum.correction_type.self',
        self::INSTRUCTION => 'enum.correction_type.instruction',
    ];

}

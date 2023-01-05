<?php

namespace App\Packages\DBAL\Types;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\DBAL\Types\Enum;
use App\Packages\Annotation\EnumAnnotation;


/**
 * Class TaxationTypeEnum
 * @EnumAnnotation
 * @package App\Packages\DBAL\Types
 */
class TaxationTypeEnum extends Enum
{
    /**
     * @var string
     * @Groups({"expose"})
     */
    protected string $enumClass = TaxationTypeEnum::class;

    public const OSN = 'OSN';
    public const USN_INCOME = 'USN_INCOME';
    public const USN_OUTCOME = 'USN_OUTCOME';
    public const ENVD = 'ENVD';
    public const ESN = 'ESN';
    public const PATENT = 'PATENT';

    /**
     * @var array
     */
    protected static $choices = [
        self::OSN => 'enum.taxation_type.osn',
        self::USN_INCOME => 'enum.taxation_type.usn_income',
        self::USN_OUTCOME => 'enum.taxation_type.usn_outcome',
        self::ENVD => 'enum.taxation_type.envd',
        self::ESN => 'enum.taxation_type.esn',
        self::PATENT => 'enum.taxation_type.patent',
    ];
}

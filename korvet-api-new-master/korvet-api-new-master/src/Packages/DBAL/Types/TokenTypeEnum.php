<?php

namespace App\Packages\DBAL\Types;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\Annotation\EnumAnnotation;
use App\Packages\DBAL\Types\Enum;


/**
 * Class TokenTypeEnum
 * @EnumAnnotation
 * @package App\Packages\DBAL\Types
 */
class TokenTypeEnum extends Enum
{
    /**
     * @var string
     * @Groups({"expose"})
     */
    protected string $enumClass = TokenTypeEnum::class;

    const BEARER = 'bearer';

    /**
     * @var array
     */
    protected static $choices = [
        self::BEARER => 'bearer'
    ];
}

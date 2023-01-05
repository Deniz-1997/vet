<?php

namespace App\Packages\DBAL\Types;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\Annotation\EnumAnnotation;
use App\Packages\DBAL\Types\Enum;


/**
 * Class ActionTypeEnum
 * @EnumAnnotation
 * @package App\Packages\DBAL\Types
 */
class ActionTypeEnum extends Enum
{
    /**
     * @var string
     * @Groups({"expose"})
     */
    protected string $enumClass = ActionTypeEnum::class;

    const NONE = 'NONE';
    const URL = 'URL';
    const ENTITY_LIST_URL = 'ENTITY_LIST_URL';
    const ENTITY = 'ENTITY';

    /**
     * @var array
     */
    protected static $choices = [
        self::NONE => 'enum.action_type.none',
        self::URL => 'enum.action_type.url',
        self::ENTITY_LIST_URL => 'enum.action_type.entity_url_list',
        self::ENTITY => 'enum.action_type.entity',
    ];
}

<?php

namespace App\Enum;

use App\Packages\DBAL\Types\Enum;
use App\Packages\Annotation\EnumAnnotation;

/**
 * @EnumAnnotation()
 * Class FieldTypeEnum
 */
final class FieldTypeEnum extends Enum
{
    public const TEXT = 'TEXT';
    public const NUMBER = 'NUMBER';
    public const LIST = 'LIST';
    public const DATE_TIME = 'DATE_TIME';
    public const SWITCHER = 'SWITCHER';
    public const REFERENCE = 'REFERENCE';
    public const TEXTAREA = 'TEXTAREA';
    protected string $enumClass = self::class;

    /**
     * @var array Значения для перевода на язык системы
     */
    protected static $choices = [
        self::TEXT => 'enum.field_type.text',
        self::NUMBER => 'enum.field_type.number',
        self::LIST => 'enum.field_type.list',
        self::DATE_TIME => 'enum.field_type.date_time',
        self::SWITCHER => 'enum.field_type.switcher',
        self::REFERENCE => 'enum.field_type.reference',
        self::TEXTAREA => 'enum.field_type.textarea',
    ];
}

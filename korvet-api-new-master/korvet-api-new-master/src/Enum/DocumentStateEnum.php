<?php

namespace App\Enum;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\Annotation\EnumAnnotation;
use App\Packages\DBAL\Types\Enum;

/**
 * Class DocumentStateEnum
 * @EnumAnnotation
 */
class DocumentStateEnum extends Enum
{
    /**
     * @var string
     * @Groups({"expose"})
     */
    protected string $enumClass = self::class;

    public const DRAFT = 'DRAFT';
    public const REGISTERED = 'REGISTERED';
    public const ERROR = 'ERROR';
    public const ADJUSTMENT = 'ADJUSTMENT';

    /**
     * @var array Значения для перевода на язык системы
     */
    protected static $choices = [
        self::DRAFT => 'document_state.draft',
        self::REGISTERED => 'document_state.registered',
        self::ERROR => 'document_state.error',
        self::ADJUSTMENT => 'document_state.adjustment',
    ];
}

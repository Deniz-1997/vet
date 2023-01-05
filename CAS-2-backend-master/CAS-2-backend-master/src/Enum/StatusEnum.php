<?php


namespace App\Enum;


use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\Annotation\EnumAnnotation;
use App\Packages\DBAL\Types\Enum;

/**
 * Class StatusEnum
 * @EnumAnnotation()
 */
class StatusEnum extends Enum
{
    /**
     * @var string
     * @Groups({"expose"})
     */
    protected string $enumClass = self::class;

    public const DRAFT = 'DRAFT';
    public const REGISTERED = 'REGISTERED';
    public const ERROR = 'ERROR';

    /**
     * @var array
     */
    protected static $choices = [
        self::DRAFT => 'enum.status.draft',
        self::REGISTERED => 'enum.status.registered',
        self::ERROR => 'enum.status.error',
    ];
}

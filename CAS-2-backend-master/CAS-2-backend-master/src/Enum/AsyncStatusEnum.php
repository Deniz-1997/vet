<?php


namespace App\Enum;

use App\Packages\Annotation\EnumAnnotation;

/**
 * Class AsyncResponseStatusEnum
 * @EnumAnnotation()
 * @package App\Enum
 */
class AsyncStatusEnum extends BaseEnum
{
    protected $enumClass = self::class;

    public const CREATED = 'CREATED';

    public const WAIT = 'WAIT';

    public const DONE = 'DONE';

    public const ERROR = 'ERROR';

    /**
     * @return array Перевод значений на язык системы
     */
    protected static $choices = [
        self::CREATED => 'enum.created',
        self::WAIT => 'enum.wait',
        self::DONE => 'enum.done',
        self::ERROR => 'enum.error',
    ];

    public static function choices(): array
    {
        // TODO: Implement choices() method.
    }
}

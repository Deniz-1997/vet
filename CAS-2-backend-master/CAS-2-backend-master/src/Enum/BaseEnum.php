<?php

namespace App\Enum;

use App\Packages\Annotation\EnumAnnotation;
use App\Traits\EnumTrait;
use App\Interfaces\EnumInterface;
use OpenApi\Annotations as SWG;

/**
 * @EnumAnnotation()
 * Class BaseEnum
 */
abstract class BaseEnum implements EnumInterface
{
    use EnumTrait;

    const NULLABLE = 'NULL';

    /**
     * @var string|null
     * @SWG\Property(type="string", description="Идентификатор значения В_ВЕРХНЕМ_РЕГИСТРЕ")
     */
    public ?string $code;

    /**
     * @var string
     * @SWG\Property(type="string", description="Представление значения")
     */
    public string $title;

    /**
     * @param string|null $code
     *
     * @return self|null
     */
    public static function getItem(?string $code): ?self
    {
        if (static::hasCode($code)) {
            return (new static)->setCode($code);
        }

        return null;
    }
}

<?php

namespace App\Packages\DBAL\Types;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\Annotation\EnumAnnotation;
use App\Packages\DBAL\Types\Enum;
use App\Traits\ChoicesFromEnvTrait;

/**
 * Class ProductCodeTypeEnum
 * @EnumAnnotation
 * @package App\Packages\DBAL\Types
 */
class ProductCodeTypeEnum extends Enum
{
    use ChoicesFromEnvTrait;

    /**
     * @var string
     * @Groups({"expose"})
     */
    protected string $enumClass = ProductCodeTypeEnum::class;
    private const ENV_VAR = 'PRODUCT_CODE_TYPE_ENUM';

    public const MEDICINES = 'MEDICINES';
    public const FURS = 'FURS';
    public const SHOES = 'SHOES';
    public const TOBACCO = 'TOBACCO';

    /**
     * @var array
     */
    protected static $choices = [
        self::MEDICINES => 'enum.product_code_type.medicines',
        self::FURS => 'enum.product_code_type.furs',
        self::SHOES => 'enum.product_code_type.shoes',
        self::TOBACCO => 'enum.product_code_type.tobacco',
    ];
}

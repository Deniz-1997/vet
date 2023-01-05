<?php

namespace App\Packages\DBAL\Types;

use Acelaya\Doctrine\Exception\InvalidArgumentException;
use Acelaya\Doctrine\Type\PhpEnumType;
use App\Interfaces\EnumInterface;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use OpenApi\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\EnumTrait;
use App\Enum\EnumException;

/**
 * Class Enum
 */
class Enum extends PhpEnumType implements EnumInterface
{
    use EnumTrait;

    const NULLABLE = 'NULL';

    /**
     * @var string|null
     * @Groups({"default"})
     *
     * @SWG\Property(
     *   property="",
     *   type="string",
     *   title="",
     *   format="",
     *   description="Идентификатор значения В\_ВЕРХНЕМ\_РЕГИСТРЕ"
     * )
     */
    public ?string $code=null;

    /**
     * @var ?string
     * @Groups({"default"})
     *
     * @SWG\Property(
     *   property="",
     *   type="string",
     *   title="",
     *   format="",
     *   description="Представление значения"
     * )
     */
    public ?string $title;

    /**
     * @param string|null      $value
     * @param AbstractPlatform $platform
     *
     * @return mixed|Enum
     * @throws DBALException
     * @throws EnumException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): Enum
    {
        /** @var Enum $type */
        $type = clone Type::getType($this->enumClass);
        if ($value === null) {
            $type->code = $type->title = null;

            return $type;
        }

        // If the enumeration provides a casting method, apply it
        if (method_exists($this->enumClass, 'castValueIn')) {
            /** @var callable $castValueIn */
            $castValueIn = [$this->enumClass, 'castValueIn'];
            $value = $castValueIn($value);
        }

        // Check if the value is valid for this enumeration
        /** @var callable $isValidCallable */
        $isValidCallable = [$this->enumClass, 'isValid'];
        $isValid = $isValidCallable($value);
        if (!$isValid) {
            throw self::createEnumException($value, array_keys(self::choices()), static::class);
        }

        if ($data = json_decode($value, true)) {
            $type->code = $data['code'] ?? null;
            $type->title = $data['title'] ?? null;
        } else {
            $type->code = $value;
        }

        return $type;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        // If the enumeration provides a casting method, apply it
        if (method_exists($this->enumClass, 'castValueOut')) {
            /** @var callable $castValueOut */
            $castValueOut = [$this->enumClass, 'castValueOut'];

            return $castValueOut($value);
        }

        if ($data = json_decode($value, true)) {
            $value = strtoupper($data['code']);
        }

        if ($value === self::NULLABLE) {
            $value = null;
        }

        // Otherwise, cast to string
        return (string)$value;
    }

    /**
     * Default type string
     *
     * @param array            $fieldDeclaration
     * @param AbstractPlatform $platform
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * @param string|null $value
     *
     * @return array|string|null
     */
    public static function castValueIn(?string $value)
    {
        if ($data = json_decode($value, true)) {
            $code = strtoupper($data['code']);

            return $code === self::NULLABLE ? null : $code;
        }
        if (is_string($value)) {
            return $value === self::NULLABLE ? null : $value;
        }
    }

    /**
     * @param EnumInterface|string|null $value
     *
     * @return EnumInterface|string
     * @throws EnumException
     */
    public static function castValueOut($value)
    {
        if (is_string($value) && $data = json_decode($value, true)) {
            $code = $data['code'];
            if (self::hasCode($code)) {
                return $code === self::NULLABLE ? null : $code;
            }
        }
        if ($value instanceof EnumInterface && self::hasCode($value->code)) {
            /** @var Enum $value */
            return $value->code === self::NULLABLE ? null : $value->code;
        }
        if (is_string($value) && self::hasCode($value)) {
            return $value === self::NULLABLE ? null : $value;
        }

        throw self::createEnumException($value, self::choices(), static::class);
    }

    /**
     * @param string|null $code
     *
     * @return static|null
     * @throws DBALException
     */
    static public function getItem(?string $code): ?self
    {
        if (static::hasCode($code)) {
            /** @var static $obj */
            $obj = static::getType(static::class);

            return $obj->setCode($code);
        } else {
            return null;
        }
    }
}

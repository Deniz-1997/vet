<?php

namespace App\Traits;

use App\Interfaces\EnumInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Enum\EnumException;

/**
 * Trait EnumTrait
 */
trait EnumTrait
{
    /**
     * @return array
     */
    public static function choices(): array
    {
        $nullableChoice = [self::NULLABLE => 'enum.nullable.value'];
        if (isset(static::$choices[self::NULLABLE])) {
            unset(static::$choices[self::NULLABLE]);
        }
        static::$choices = array_merge_recursive($nullableChoice, static::$choices);

        return static::$choices;
    }

    /**
     * @param object|string $value
     * @Groups({"expose"})
     *
     * @return bool
     */
    public static function isValid($value)
    {
        $compareValue = $value;
        if ($value === null) {
            $compareValue = self::NULLABLE;
        }
        if ($compareValue instanceof EnumInterface) {
            return self::hasCode($compareValue->code);
        }
        $data = json_decode($compareValue, true);
        if (isset($data['code'])) {
            return self::hasCode($data['code']);
        }

        return self::hasCode($compareValue);
    }

    /**
     * @return array
     */
    public static function toArray()
    {
        return array_flip(self::choices());
    }

    /**
     * @Groups({"expose"})
     * @return bool
     */
    public static function isNullable(): bool
    {
        return defined(static::class . '::NULLABLE');
    }

    /**
     * @param $code |null
     *
     * @return bool
     */
    public static function hasCode(?string $code = null): ?bool
    {
        $code = !$code ? self::NULLABLE : $code;

        return $code ? array_key_exists($code, self::choices()) : $code;
    }

    /**
     * @Groups({"expose"})
     * @param string|null $code
     *
     * @return string
     */
    public static function getLabelCode(?string $code): ?string
    {
        if ($code === null || $code === '') {
            $code = self::NULLABLE;
        }
        if (self::hasCode($code)) {
            return self::choices()[$code];
        }

        return null;
    }

    /**
     * Converter this to string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->code ?? '';
    }

    /**
     * @return string
     */
    public function getName():string
    {
        return $this->enumClass;
    }

    /**
     * @param string|null $code
     *
     * @return static
     */
    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @param string $title
     *
     * @return static
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $value
     * @param array  $allowed
     * @param string $className
     *
     * @return EnumException
     */
    public static function createEnumException($value, $allowed, $className): EnumException
    {
        if ($value instanceof EnumInterface) {
            $value = $value->code;
        } elseif (is_array($value)) {
            $value = $value['code'] ?? $value;
        } else {
            $value = (string)$value;
        }

        $ex = new EnumException('error.enum_item.not_found', Response::HTTP_BAD_REQUEST);
        $ex->setAllowed($allowed);
        $ex->setValue($value);
        $ex->setClassName($className);

        return $ex;
    }

    public function isNoValue()
    {
        return $this->code === '' || $this->code === self::NULLABLE || $this->code === null;
    }
}

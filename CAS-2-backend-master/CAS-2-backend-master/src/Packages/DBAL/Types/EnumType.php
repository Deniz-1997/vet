<?php

namespace App\Packages\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class EnumType extends AbstractEnumType
{
    /**
     * {@inheritdoc}
     * @throws \InvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        if (!isset(static::$choices[$value])) {
            throw new \InvalidArgumentException(\sprintf('Invalid value "%s" for ENUM "%s".', $value, $this->getName()));
        }

        return json_encode(['code' => $value]);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (!isset(static::$choices[$value])) {
            return $value;
        }

        // Check whether choice list is using integers as values
        $choice = static::$choices[$value];
        $choices = \array_flip(static::$choices);
        if (\is_int($choices[$choice])) {
            return (int)$value;
        }

        return ['code' => $value];
    }
}

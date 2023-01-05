<?php

namespace App\Packages\Utils;

use ReflectionClass;
use ReflectionException;

/**
 * Class PropertyAccessor
 */
class PropertyAccessor
{
    /**
     * @param $obj
     * @param $propertyName
     * @param $value
     * @throws ReflectionException
     */
    public static function setValueForce($obj, $propertyName, $value)
    {
        $reflectionClass = new ReflectionClass($obj);
        $reflectionProperty = $reflectionClass->getProperty($propertyName);

        if (!$reflectionProperty->isPublic()) {
            $reflectionProperty->setAccessible(true);
        }

        $reflectionProperty->setValue($obj, $value);
    }

    /**
     * @param $obj
     * @param $propertyName
     * @return mixed
     * @throws ReflectionException
     */
    public static function getValueForce($obj, $propertyName)
    {
        $reflectionClass = new ReflectionClass($obj);
        $reflectionProperty = $reflectionClass->getProperty($propertyName);

        if (!$reflectionProperty->isPublic()) {
            $reflectionProperty->setAccessible(true);
        }

        return $reflectionProperty->getValue($obj);
    }
}

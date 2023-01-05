<?php

namespace App\Filter\Type;

use Symfony\Component\PropertyInfo\Type;

/**
 * Class TypeInfo
 */
class TypeInfo
{
    /** @var string */
    private $class;

    /** @var string */
    private $property;

    /** @var Type */
    private $type;

    /**
     * TypeInfo constructor.
     * @param string $class
     * @param string $property
     * @param Type $type
     */
    public function __construct(string $class  = "", string $property = "", ?Type $type = null)
    {
        $this->class = $class;
        $this->property = $property;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * @return Type
     */
    public function getType(): Type
    {
        return $this->type;
    }
}

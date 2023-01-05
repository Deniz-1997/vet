<?php

namespace App\Model;

use Symfony\Component\PropertyInfo\Type;

/**
 * Class Model
 * @package App\Model
 */
final class Model
{
    private $type;

    private $groups;

    private $options;

    /**
     * @param Type          $type
     * @param string[]|null $groups
     * @param array $context
     */
    public function __construct(Type $type, array $groups = null, array $context = [])
    {
        $this->context = $context;
    }

    /**
     * @return Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string[]|null
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @return mixed[]|null
     */
    public function getOptions()
    {
        return $this->options;
    }

    private array $context;


    /**
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * @param $field
     * @return mixed
     */
    public function getContextFor($field)
    {
        return !empty($this->context[$field]) ? $this->context[$field] : null;
    }

    /**
     * @param string|integer $field
     * @param mixed $value
     */
    public function addContext($field, $value)
    {
        $this->context[$field] = $value;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        $previousHash = md5(serialize([$this->type, $this->groups]));
        return md5($previousHash.__CLASS__.json_encode($this->context));
    }
}

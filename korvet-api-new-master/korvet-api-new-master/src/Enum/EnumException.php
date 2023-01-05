<?php


namespace App\Enum;

/**
 * Class EnumException
 */
class EnumException extends \Exception
{
    /**
     * @var string|null
     */
    private $value;
    /**
     * @var array
     */
    private $allowed;
    /**
     * @var string
     */
    private $className;

    /**
     * @param string|null $value
     */
    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    /**
     * @param array $allowedValue
     */
    public function setAllowed($allowedValue): void
    {
        $this->allowed = $allowedValue;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @return array
     */
    public function getAllowed(): array
    {
        return $this->allowed;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className
     */
    public function setClassName(string $className): void
    {
        $this->className = $className;
    }
}

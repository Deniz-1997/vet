<?php

namespace App\Exception;

use Symfony\Component\Serializer\Exception\ExceptionInterface;

class DenormalizeException extends \Exception implements ExceptionInterface
{
    /**
     * @var string
     */
    private $field;
    
    /**
     * DenormalizeException constructor.
     *
     * @param string          $field
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        string $field,
        string $message,
        int $code = 0,
        \Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
        $this->field = $field;
    }
    
    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }
}

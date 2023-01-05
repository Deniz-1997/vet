<?php

namespace App\Exception;

use Exception;

/**
 * Class ApiQueueException
 */
class ApiQueueException extends Exception
{
    /**
     * @var string
     */
    protected $field;

    /**
     * @param string $field
     * @param string $message
     */
    public function __construct(string $field, string $message = 'UNKNOWN_ERROR') {
        $this->field = $field;
        parent::__construct($message);
    }
    /**
     * @return  string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param  string  $field
     * @return  self
     */
    public function setField(string $field)
    {
        $this->field = $field;
        return $this;
    }

    public function getExceptionArray() {
        $error = [
            "field"=>str_replace(['[',']'], '', $this->field),
            "error" =>$this->getMessage()
        ];
        return $error;
    }
}

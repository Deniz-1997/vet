<?php

namespace App\Exception;

/**
 * Class AmqpConsumerEntityIsEmpty
 */
class AmqpConsumerEntityIsEmptyException extends \Exception
{
    public $message = 'amqp.exception.entity.is_null';
}

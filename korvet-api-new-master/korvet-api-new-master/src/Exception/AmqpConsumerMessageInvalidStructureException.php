<?php

namespace App\Exception;

/**
 * Class AmqpConsumerMessageInvalidStructureException
 */
class AmqpConsumerMessageInvalidStructureException extends \Exception
{
    public $message = 'amqp.exception.message.invalid_structure';
}

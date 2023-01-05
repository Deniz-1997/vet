<?php

namespace App\Exception;

use App\Exception\ApiException;

/**
 * Class AmqpEmptyAnnotationException
 */
class AmqpEmptyBatchAnnotationException extends ApiException
{
    public $message = 'error.amqp.entity.empty.batch_annotation';
}

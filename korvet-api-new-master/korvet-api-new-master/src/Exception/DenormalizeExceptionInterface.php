<?php

namespace App\Exception;

use Symfony\Component\Serializer\Exception\ExceptionInterface;

interface DenormalizeExceptionInterface extends ExceptionInterface
{
    /**
     * @return array
     */
    public function getDenormalizeExceptions(): array;
}

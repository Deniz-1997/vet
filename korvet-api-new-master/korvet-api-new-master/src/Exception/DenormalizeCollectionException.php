<?php

namespace App\Exception;


use \Symfony\Component\HttpFoundation\Response;

/**
 * Class DenormalizeCollectionException
 */
class DenormalizeCollectionException extends \Exception implements DenormalizeExceptionInterface
{
    /**
     * @var array
     */
    private $denormalizeExceptions;

    public function __construct(array $denormalizeExceptions, int $code = null)
    {
        parent::__construct('', $code ?? Response::HTTP_INTERNAL_SERVER_ERROR);
        $this->denormalizeExceptions = $denormalizeExceptions;
    }

    /**
     * @return array
     */
    public function getDenormalizeExceptions(): array
    {
        return $this->denormalizeExceptions;
    }
}

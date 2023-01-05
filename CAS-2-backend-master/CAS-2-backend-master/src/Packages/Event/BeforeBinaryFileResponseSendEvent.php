<?php

namespace App\Packages\Event;

use Symfony\Contracts\EventDispatcher\Event;
use App\Packages\Response\BinaryFileResponse;

class BeforeBinaryFileResponseSendEvent extends Event
{
    const NAME = 'webslon.api_library.binary_response.before_send';
    /**
     * @var BinaryFileResponse
     */
    private $response;

    /**
     * PreBaseResponseSendEvent constructor.
     * @param BinaryFileResponse $response
     */
    public function __construct(BinaryFileResponse $response)
    {
        $this->response = $response;
    }

    /**
     * @return BinaryFileResponse
     */
    public function getResponse(): BinaryFileResponse
    {
        return $this->response;
    }
}

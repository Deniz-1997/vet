<?php

namespace App\Packages\Event;

use Symfony\Contracts\EventDispatcher\Event;
use App\Packages\Response\BaseResponse;

class BeforeBaseResponseSendEvent extends Event
{
    const NAME = 'webslon.api_library.base_response.before_send';
    /**
     * @var BaseResponse
     */
    private $response;

    /**
     * PreBaseResponseSendEvent constructor.
     * @param BaseResponse $response
     */
    public function __construct(BaseResponse $response)
    {
        $this->response = $response;
    }

    /**
     * @return BaseResponse
     */
    public function getResponse(): BaseResponse
    {
        return $this->response;
    }
}

<?php

namespace App\Packages\Event;

use App\Packages\Response\BaseResponse;

class AfterBaseResponseSendEvent
{
    const NAME = 'webslon.api_library.base_response.after_send';
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

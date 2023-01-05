<?php

namespace App\Packages\Response;

use App\Interfaces\ApiResponseInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse as FileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use App\Packages\Event\BeforeBinaryFileResponseSendEvent;
use App\Exception\ApiException;
use OpenApi\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;

/**
 * Response.
 */
class BinaryFileResponse extends BaseResponse implements ApiResponseInterface
{
    /**
     * @return FileResponse
     * @throws ApiException
     */
    public function send(): FileResponse
    {
        if (!\is_array($this->getHeaders())) {
            $this->setHeaders([]);
        }
        if ($this->isStatus() === true && !$this->getHttpResponseCode()) {
            $this->setHttpResponseCode(Response::HTTP_OK);
        }

        $beforeEvent = new BeforeBinaryFileResponseSendEvent($this);
        $this->getEventDispatcher()->dispatch($beforeEvent);

        if (!is_string($this->getResponse()) || !is_file($this->getResponse())) {
            throw new ApiException(
                'file was not created, please contact developers',
                500
            );
        }

        return new FileResponse(
            $this->getResponse(),
            $this->gethttpResponseCode() ?? Response::HTTP_INTERNAL_SERVER_ERROR,
            $this->getHeaders()
        );
    }
}

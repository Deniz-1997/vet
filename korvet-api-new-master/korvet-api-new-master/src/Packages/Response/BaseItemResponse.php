<?php

namespace App\Packages\Response;

use Symfony\Component\HttpFoundation\Response as SilexResponse;
use OpenApi\Annotations as SWG;
use App\Exception\ApiException;

/**
 * Response.
 */
class BaseItemResponse
{
    /**
     * @SWG\Property(type="boolean")
     */
    public $status;

    /**
     * @var BaseItemChildResponse
     * @SWG\Property
     */
    public BaseItemChildResponse $response;
}

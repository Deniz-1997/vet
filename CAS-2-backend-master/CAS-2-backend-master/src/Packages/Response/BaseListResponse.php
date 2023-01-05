<?php

namespace App\Packages\Response;

use Symfony\Component\HttpFoundation\Response as SilexResponse;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SkipWhenEmpty;
use OpenApi\Annotations as SWG;
use App\Exception\ApiException;

/**
 * Response.
 *
 * @ExclusionPolicy("all")
 */
class BaseListResponse
{
    /**
     * @var boolean
     *
     * @SWG\Property(type="boolean")
     */
    public bool $status;

    /**
     * @var BaseListItemsContainResponse
     *
     * @SWG\Property()
     */
    public BaseListItemsContainResponse $response;
}

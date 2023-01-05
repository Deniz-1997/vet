<?php


namespace App\Packages\Response\Async;


use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use App\Packages\Response\BaseResponse;

class AsyncResponse extends BaseResponse
{
    /**
     * @var boolean
     *
     * @SWG\Property(type="boolean", default="true", description="Признак успешного ответа сервиса")
     */
    public $status;

    /**
     * @var AsyncResponseBody
     *
     * @SWG\Property(type="object", ref=@Model(type=AsyncResponseBody::class), description="Результат успешной работы сервиса, в случае status=true.")
     */
    public $response;
}

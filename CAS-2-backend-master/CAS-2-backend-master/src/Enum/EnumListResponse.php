<?php

namespace App\Enum;

use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * Class EnumListResponse
 */
class EnumListResponse
{
    /**
     * @var boolean
     * @SWG\Property(type="boolean", description="Признак успешного ответа сервиса")
     */
    public bool $status = true;

    /**
     * @var integer
     * @SWG\Property(type="integer")
     */
    public int $httpResponseCode = Response::HTTP_OK;

    /**
     * @var array|null
     * @SWG\Property(type="array", description="Массив ошибок, в случае status=false", @SWG\Items(ref=@Model(type=ApiException::class)))
     */
    public ?array $errors;

    /**
     * @var EnumItems
     * @SWG\Property(type="array",  @SWG\Items(ref=@Model(type=EnumItems::class)), description="Многомерный массив с характеристиками перечислений")
     */
    public EnumItems $response;

    /**
     * @var string
     * @SWG\Property(type="string", description="Идентификатор запроса для систем логирования")
     */
    public string $requestId;
}

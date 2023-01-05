<?php

namespace App\Packages\Response;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class MessageQueueResponse
 */
class MessageQueueResponse
{
    /**
     * @var bool
     * @SWG\Property(type="boolean", description="Статус выполнения запроса")
     */
    public bool $status;

    /**
     * @var array|null
     * @SWG\Property(type="array", description="Массив ошибок, в случае status=false", @SWG\Items(ref=@Model(type=ApiException::class)))
     */
    public ?array $errors;

    /**
     * @var string|null
     * @SWG\Property(type="string", description="Уникальный идентификатор запроса")
     */
    public ?string $requestId;

    /**
     * @var array<MessageListResponse>
     * @Groups({"default"})
     * @SWG\Property(type="object", ref=@Model(type=MessageListResponse::class), description="Сообщения из очереди")
     */
    public array $response;

    /**
     * @var int
     * @SWG\Property(type="integer", description="Http код ответа")
     */
    public int $httpRequestCode;
}

<?php

namespace App\Model;

use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class AmqpMessageItemModel
 */
class AmqpMessageItemModel
{
    /**
     * @var integer
     * @Groups({"default"})
     * @SWG\Property(type="integer", description="Вес сообщения в байтах")
     */
    public int $payload_bytes;
    /**
     * @var bool
     * @Groups({"default"})
     * @SWG\Property(type="boolean", description="Повторная отправка")
     */
    public bool $redelivered;
    /**
     * @var string
     * @Groups({"default"})
     * @SWG\Property(type="string", description="Наименование обменника")
     */
    public string $exchange;

    /**
     * @var string
     * @Groups({"default"})
     * @SWG\Property(type="string", description="Ключ маршрутизации сообщения")
     */
    public string $routing_key;
    /**
     * @var integer
     * @Groups({"default"})
     * @SWG\Property(type="integer", description="Кол-во сообщений в очереди")
     */
    public int $message_count;
    /**
     * @var array
     * @Groups({"default"})
     * @SWG\Property(type="", description="Свойства сообщения")
     */
    public array $properties;

    /**
     * @var array
     * @Groups({"default"})
     * @SWG\Property(type="", description="Тело сообщения")
     */
    public array $payload;
}

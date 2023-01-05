<?php

namespace App\Packages\Response;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;

/**
 * Class MessageListResponse
 */
class MessageListResponse
{
    /**
     * @var integer
     * @SWG\Property(type="integer", description="Кол-во сообщений в очереди")
     */
    public int $totalCount;

    /**
     * @var integer
     * @SWG\Property(type="integer", description="Количество выведенных сообщений")
     */
    public int $countItems;

    /**
     * @var integer
     * @SWG\Property(type="array", @SWG\Items(ref=@Model(type=AmqpMessageItemModel::class)), description="Массив сообщений из запрошенной очереди")
     */
    public int $items;
}

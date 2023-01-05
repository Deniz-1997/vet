<?php

namespace App\Model;

use App\Entity\HistoryEntity;
use OpenApi\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * Class HistoryList
 */
class HistoryList
{
    /**
     * @var array<HistoryEntity>
     * @SWG\Property(description="Массив записей истории", type="array", @SWG\Items(ref=@Model(type=HistoryEntity::class)))
     */
    public array $items;

    /**
     * @var integer
     * @SWG\Property(description="Количество найденных записей, с учетом фильтра", type="integer")
     */
    public int $totalCount = 0;
}

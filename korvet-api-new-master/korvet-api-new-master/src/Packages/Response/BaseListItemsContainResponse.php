<?php

namespace App\Packages\Response;

use JMS\Serializer\Annotation as Serializer;
use OpenApi\Annotations as SWG;

class BaseListItemsContainResponse
{
    /**
     * @var BaseListItemResponse[]
     * @SWG\Property()
     * @Serializer\Expose()
     */
    public array $items;

    /**
     * @var int
     * @SWG\Property(type="integer")
     * @Serializer\Expose()
     */
    public int $totalCount;
}

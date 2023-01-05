<?php

namespace App\Enum;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use App\Packages\Response\BaseItemChildResponse;

/**
 * Class EnumItems
 */
class EnumItems extends BaseItemChildResponse
{
    /**
     * @var string
     * @SWG\Property(type="string", description="Короткое наименование класса", example="OrderHistoryReturnReasonTypeEnum")
     */
    public string $shortClassName;

    /**
     * @var string
     * @SWG\Property(type="string", description="Полное наименование класса с namespace", example="App\DBAL\\Types\\OrderHistoryReturnReasonTypeEnum")
     */
    public string $fullClassName;

    /**
     * @var array
     * @SWG\Property(description="Массив enum значений", type="array", @SWG\Items(ref=@Model(type=Item::class)))
     */
    public array $items;
}

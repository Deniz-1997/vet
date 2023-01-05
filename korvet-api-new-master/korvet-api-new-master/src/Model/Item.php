<?php

namespace App\Model;

use OpenApi\Annotations as SWG;

/**
 * Class Item
 */
class Item
{
    /**
     * @var string
     * @SWG\Property(type="string", description="Кодовое обозначение", example="STOCK_INCORRECT")
     */
    public string $id;

    /**
     * @var string
     * @SWG\Property(type="string", description="Перевод на языке системы", example="Некорректный сток")
     */
    public string $name;
}

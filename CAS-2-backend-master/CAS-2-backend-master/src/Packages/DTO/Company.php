<?php

namespace App\Packages\DTO;

use OpenApi\Annotations as SWG;

class Company
{
    /**
     * @var string
     * @SWG\Property(type="string", description="Наименование")
     */
    public string $name;
    /**
     * @var string
     * @SWG\Property(type="string", description="Адрес")
     */
    public string $address;
    /**
     * @var string
     * @SWG\Property(type="string", description="Телефон")
     */
    public string $phone;
    /**
     * @var string[]
     * @SWG\Property(type="array", items=@SWG\Items(type="string"), description="Часы работы")
     */
    public array $hours;
}

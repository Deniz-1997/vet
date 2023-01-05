<?php


namespace App\Packages\DTO;


use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;

class UserAdditionalFields
{
    /**
     * @var string
     * @SWG\Property(type="string", description="Личный мобильный")
     */
    public string $phone;
    /**
     * @var string[]
     * @SWG\Property(type="array", items=@SWG\Items(type="string"), description="Часы работы")
     */
    private array $hours;
    /**
     * @var Company
     * @SWG\Property(ref=@Model(type=Company::class), description="Данные о компании")
     */
    public Company $company;
    /**
     * @var string
     * @SWG\Property(type="string", description="Должность")
     */
    public string $position;
    /**
     * @var string
     * @SWG\Property(type="string", description="Название (идентификатор) текущей темы оформления сайта")
     */
    public string $theme;
    /**
     * @var string
     * @SWG\Property(type="string", description="Ссылка на фото пользователя")
     */
    public string $photoSrc;
    /**
     * @var string
     * @SWG\Property(type="string", description="ИНН пользователя")
     */
    public string $inn;
}

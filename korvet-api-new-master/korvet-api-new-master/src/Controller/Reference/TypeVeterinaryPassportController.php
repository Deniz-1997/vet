<?php

namespace App\Controller\Reference;

use App\Entity\Reference\VeterinaryPassportType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;

/**
 * Class TypeVeterinaryPassportController
 * @Route("/api/type-vet-passport")
 * @Resource(
 *     description="Main desc",
 *     tags={"VeterinaryPassportType"},
 *     summariesMap={
 *          "list": "Получить список типов ветеринарных паспортов",
 *          "get": "Получить тип ветеринарного паспорта",
 *          "post": "Создать тип ветеринарного паспорта",
 *          "patch": "Обновить тип ветеринарного паспорта",
 *          "delete": "Удалить тип ветеринарного паспорта",
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список типов ветеринарных паспортов",
 *          "get": "Возвращает тип ветеринарного паспорта",
 *          "post": "Создает тип ветеринарного паспорта",
 *          "patch": "Обновляет тип ветеринарного паспорта",
 *          "delete": "Удаляет тип ветеринарного паспорта",
 *     }
 * )
 */
class TypeVeterinaryPassportController extends AbstractController
{
    const ENTITY_CLASS = VeterinaryPassportType::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;

    /**
     * @return array
     */
    public function getSerializationContextOptions() : array
    {
        return [
            'getList'    => ['groups' => ['default']],
            'addItem'    => ['groups' => ['api.type-vet-passport']],
            'getItem'    => ['groups' => ['api.type-vet-passport']],
            'patchItem'  => ['groups' => ['api.type-vet-passport']],
            'updateItem' => ['groups' => ['api.type-vet-passport']],
        ];
    }
}

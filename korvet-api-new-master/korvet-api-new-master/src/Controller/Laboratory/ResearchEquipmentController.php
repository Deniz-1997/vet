<?php

namespace App\Controller\Laboratory;

use App\Entity\Laboratory\ResearchEquipment;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ResearchEquipmentController
 * @Route("/api/laboratory/research-equipment")
 * @Resource(
 *     description="Main desc",
 *     tags={"Laboratory"},
 *     summariesMap={
 *          "list": "Получить список аппаратуры для исследования",
 *          "get": "Получить аппаратуру для исследования",
 *          "post": "Создать аппаратуру для исследования",
 *          "delete": "Удалить аппаратуру для исследования",
 *          "patch": "Обновить аппаратуру для исследования"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список аппаратуры для исследования",
 *          "get": "Возвращает аппаратуру для исследования",
 *          "post": "Создает аппаратуру для исследования",
 *          "delete": "Удаляет существующую аппаратуру для исследования",
 *          "patch": "Обновляет существующую аппаратуру для исследования"
 *     }
 * )
 */
class ResearchEquipmentController extends AbstractController
{
    public const ENTITY_CLASS = ResearchEquipment::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}

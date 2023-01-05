<?php

namespace App\Controller\Laboratory;

use App\Entity\Laboratory\Laboratory;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class LaboratoryController
 * @Route("/api/laboratory/laboratory")
 * @Resource(
 *     description="Main desc",
 *     tags={"Laboratory"},
 *     summariesMap={
 *          "list": "Получить список лабораторий",
 *          "get": "Получить лабораторию",
 *          "post": "Создать лабораторию",
 *          "delete": "Удалить лабораторию",
 *          "patch": "Обновить лабораторию"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список лабораторий",
 *          "get": "Возвращает лабораторию",
 *          "post": "Создает лабораторию",
 *          "delete": "Удаляет существующую лабораторию",
 *          "patch": "Обновляет существующую лабораторию"
 *     }
 * )
 */
class LaboratoryController extends AbstractController
{
    public const ENTITY_CLASS = Laboratory::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}

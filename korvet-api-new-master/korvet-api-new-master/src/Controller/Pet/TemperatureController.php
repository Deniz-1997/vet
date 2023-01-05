<?php

namespace App\Controller\Pet;

use App\Entity\Pet\Temperature;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class TemperatureController
 * @package App\Controller\Pet
 * @Route("/api/pet/temperature")
 * @Resource(
 *     description="Main desc",
 *     tags={"Pet"},
 *     summariesMap={
 *          "list": "Получить список замеров температуры животного",
 *          "get": "Получить замер температуры животного",
 *          "post": "Создать замер температуры животного",
 *          "delete": "Удалить замер температуры животного",
 *          "patch": "Обновить замер температуры животного"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список замеров температуры животного",
 *          "get": "Возвращает замер температуры животного",
 *          "post": "Создает новый замер температуры животного",
 *          "delete": "Удаляет существующий замер температуры животного",
 *          "patch": "Обновляет существующий замер температуры животного"
 *     }
 * )
 */
class TemperatureController extends AbstractController
{
    public const ENTITY_CLASS = Temperature::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}

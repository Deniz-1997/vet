<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\DynamicEntityClassControllerInterface;
use App\Entity\Shift;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use App\Controller\CRUD\DeleteItemTrait;

/**
 * Class ShiftController
 * @Route("/api/shift")
 * @Resource(
 *     description="Кассовая смена",
 *     tags={"Shift"},
 *     summariesMap={
 *          "list": "Получить список кассовых смен",
 *          "get": "Получить кассовую смену",
 *          "post": "Создать кассовую смену",
 *          "delete": "Удалить кассовую смену",
 *          "patch": "Обновить кассовую смену"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список кассовых смен",
 *          "get": "Возвращает кассовую смену по идентификатору",
 *          "post": "Создает новую кассовую смену",
 *          "delete": "Удаляет существующую кассовую смену",
 *          "patch": "Обновляет существующую кассовую смену"
 *     }
 * )
 */
class ShiftController extends AbstractController
{
    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;

    const ENTITY_CLASS = Shift::class;
}

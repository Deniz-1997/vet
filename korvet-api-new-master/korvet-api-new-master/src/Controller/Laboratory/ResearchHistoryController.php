<?php

namespace App\Controller\Laboratory;

use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Laboratory\ResearchHistory;

/**
 * Class ResearchHistoryController
 * @Route("/api/laboratory/research-history")
 * @Resource(
 *     description="Main desc",
 *     tags={"ResearchDocument"},
 *     summariesMap={
 *          "list": "Получить список изменений исследования",
 *          "get": "Получить изменение исследования",
 *          "post": "Создать изменение исследования",
 *          "delete": "Удалить изменение исследования",
 *          "patch": "Обновить изменение исследования"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список изменений исследования",
 *          "get": "Возвращает изменение исследования",
 *          "post": "Создает изменение исследования",
 *          "delete": "Удаляет существующую изменение исследования",
 *          "patch": "Обновляет существующую изменение исследования"
 *     }
 * )
 */
class ResearchHistoryController extends AbstractController
{
    public const ENTITY_CLASS = ResearchHistory::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}

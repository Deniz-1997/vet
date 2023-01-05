<?php

namespace App\Controller\Laboratory;

use App\Entity\Laboratory\ResearchPriority;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ResearchPriorityController
 * @Route("/api/laboratory/research-priority")
 * @Resource(
 *     description="Main desc",
 *     tags={"ResearchPriority"},
 *     summariesMap={
 *          "list": "Получить список приоритетов исследований",
 *          "get": "Получить приоритет исследования",
 *          "post": "Создать приоритет исследования",
 *          "delete": "Удалить приоритет исследования",
 *          "patch": "Обновить приоритет исследования"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список приоритетов исследований",
 *          "get": "Возвращает приоритет исследования",
 *          "post": "Создает приоритет исследования",
 *          "delete": "Удаляет существующий приоритет исследования",
 *          "patch": "Обновляет существующий приоритет исследования"
 *     }
 * )
 */
class ResearchPriorityController extends AbstractController
{
    public const ENTITY_CLASS = ResearchPriority::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}

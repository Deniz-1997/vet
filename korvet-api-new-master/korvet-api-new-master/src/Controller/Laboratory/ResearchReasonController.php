<?php

namespace App\Controller\Laboratory;

use App\Entity\Laboratory\ResearchReason;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ResearchReasonController
 * @Route("/api/laboratory/research-reason")
 * @Resource(
 *     description="Main desc",
 *     tags={"ResearchReason"},
 *     summariesMap={
 *          "list": "Получить список причин исследований",
 *          "get": "Получить причину исследования",
 *          "post": "Создать причину исследования",
 *          "delete": "Удалить причину исследования",
 *          "patch": "Обновить причину исследования"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список причин исследований",
 *          "get": "Возвращает причину исследования",
 *          "post": "Создает причину исследования",
 *          "delete": "Удаляет существующую причину исследования",
 *          "patch": "Обновляет существующую причину исследования"
 *     }
 * )
 */
class ResearchReasonController extends AbstractController
{
    public const ENTITY_CLASS = ResearchReason::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}

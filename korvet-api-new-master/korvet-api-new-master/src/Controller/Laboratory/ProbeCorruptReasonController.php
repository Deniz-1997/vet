<?php

namespace App\Controller\Laboratory;

use App\Entity\Laboratory\ProbeCorruptReason;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ProbeCorruptReasonController
 * @Route("/api/laboratory/corrupt-reason")
 * @Resource(
 *     description="Main desc",
 *     tags={"Laboratory"},
 *     summariesMap={
 *          "list": "Получить список причин брака пробы",
 *          "get": "Получить причину брака пробы",
 *          "post": "Создать причину брака пробы",
 *          "delete": "Удалить причину брака пробы",
 *          "patch": "Обновить причину брака пробы"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список причин брака пробы",
 *          "get": "Возвращает причину брака пробы",
 *          "post": "Создает причину брака пробы",
 *          "delete": "Удаляет существующую причину брака пробы",
 *          "patch": "Обновляет существующую причину брака пробы"
 *     }
 * )
 */
class ProbeCorruptReasonController extends AbstractController
{
    public const ENTITY_CLASS = ProbeCorruptReason::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}

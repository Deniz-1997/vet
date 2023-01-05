<?php

namespace App\Controller\Reference;

use App\Entity\Reference\SterilizationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;

/**
 * Class SterilizationTypeController
 * @Route("/api/reference/sterilization-type")
 * @Resource(
 *     tags={"SterilizationType"},
 *     summariesMap={
 *          "list": "Получить список видов стерилизации",
 *          "get": "Получить вид стерилизации",
 *          "post": "Создать вид стерилизации",
 *          "delete": "Удалить вид стерилизации",
 *          "patch": "Обновить вид стерилизации"
 *     },
 *     descriptionsMap={
 *          "list": "Получить список видов стерилизации",
 *          "get": "Получить вид стерилизации",
 *          "post": "Создать вид стерилизации",
 *          "delete": "Удалить вид стерилизации",
 *          "patch": "Обновить вид стерилизации"
 *     },
 * )
 */
class SterilizationTypeController extends AbstractController
{
    public const ENTITY_CLASS = SterilizationType::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;

}

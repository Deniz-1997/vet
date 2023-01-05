<?php

namespace App\Controller\Reference;

use App\Entity\Reference\VaccinationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;

/**
 * Class VaccinationTypeController
 * @Route("/api/reference/vaccination-type")
 * @Resource(
 *     tags={"VaccinationType"},
 *     summariesMap={
 *          "list": "Получить список вакцин",
 *          "get": "Получить вакцину",
 *          "post": "Создать вакцину",
 *          "delete": "Удалить вакцину",
 *          "patch": "Обновить вакцину"
 *     },
 *     descriptionsMap={
 *          "list": "Получить список вакцин",
 *          "get": "Получить вакцину",
 *          "post": "Создать вакцину",
 *          "delete": "Удалить вакцину",
 *          "patch": "Обновить вакцину"
 *     },
 * )
 */
class VaccinationTypeController extends AbstractController
{
    public const ENTITY_CLASS = VaccinationType::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}

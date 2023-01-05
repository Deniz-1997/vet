<?php

namespace App\Controller\Reference;

use App\Entity\Reference\Organization;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class OrganizationController
 * @package App\Controller/Reference
 * @Route("/api/reference/organization")
 * @Resource(
 *     description="Main desc",
 *     tags={"Organization"},
 *     summariesMap={
 *          "list": "Получить список организаций",
 *          "get": "Получить организацию",
 *          "post": "Создать организацию",
 *          "delete": "Удалить организацию",
 *          "patch": "Обновить организацию"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список организаций",
 *          "get": "Возвращает организацию по идентификатору",
 *          "post": "Создает новую организацию",
 *          "delete": "Удаляет существующую организацию",
 *          "patch": "Обновляет существующую организацию"
 *     }
 * )
 */
class OrganizationController extends AbstractController
{
    public const ENTITY_CLASS = Organization::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}

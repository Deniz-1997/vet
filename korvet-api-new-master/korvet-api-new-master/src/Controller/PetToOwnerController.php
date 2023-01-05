<?php
/**
 * Created by PhpStorm.
 * User: viktorkrasnov
 * Date: 24.08.17
 * Time: 3:47
 */

namespace App\Controller;

use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use App\Entity\Pet\PetToOwner;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class PetToOwnerController
 * @package App\Controller
 * @Route("/api/pet-to-owner")
 * @Resource(
 *     description="Main desc",
 *     tags={"PetToOwner"},
 *     summariesMap={
 *          "list": "Получить список привязок животных к владельцам",
 *          "get": "Получить привязку животного к владельцу",
 *          "post": "Создать привязку животного к владельцу",
 *          "delete": "Удалить привязку животного к владельцу",
 *          "patch": "Обновить привязку животного к владельцу"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список привязок животных к владельцам",
 *          "get": "Возвращает привязку животного к владельцу по идентификатору",
 *          "post": "Создает новую привязку животного к владельцу",
 *          "delete": "Удаляет существующую привязку животного к владельцу",
 *          "patch": "Обновляет существующую привязку животного к владельцу"
 *     }
 * )
 */
class PetToOwnerController extends AbstractController
{
    public const ENTITY_CLASS = PetToOwner::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}

<?php

namespace App\Controller;

use App\Entity\WildAnimalFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;

/**
 * Class WildAnimalFileController
 * @package App\Controller
 * @Route("/api/wild-animal-file")
 * @Resource(
 *     tags={"WildAnimal"},
 *     summariesMap={
 *          "list": "Получить список файлов",
 *          "get": "Получить файл",
 *          "post": "Создать файл",
 *          "delete": "Удалить файл"
 *     },
 *     descriptionsMap={
 *          "list": "Получить список список файлов",
 *          "get": "Возвращает список файлов по идентификатору",
 *          "post": "Создать файл",
 *          "delete": "Удалить файл"
 *     },
 * )
 */
class WildAnimalFileController extends AbstractController
{
    public const ENTITY_CLASS = WildAnimalFile::class;

    use GetListTrait, GetItemTrait, AddItemTrait, DeleteItemTrait;
}

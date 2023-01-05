<?php

namespace App\Controller;

use App\Entity\Reference\Owner\Activity as ActivityEntity;
use App\Entity\Owner\FileOwner;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class FileOwnerController
 *
 * @Route("/api/owner/file")
 * @Resource(
 *     description="Main desc",
 *     tags={"FileOwner"},
 *     summariesMap={
 *          "list": "Получить список файлов владельцев",
 *          "get": "Получить файл владельца",
 *          "post": "Создать файл владельца",
 *          "delete": "Удалить файл владельца",
 *          "patch": "Обновить файл владельца"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список файлов владельцев",
 *          "get": "Возвращает файл владельца",
 *          "post": "Создает новый файл владельца",
 *          "delete": "Удаляет существующий файл владельца",
 *          "patch": "Обновляет существующий файл владельца"
 *     }
 * )
 */
class FileOwnerController extends AbstractController
{
    public const ENTITY_CLASS = FileOwner::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}

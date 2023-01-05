<?php

namespace App\Controller\Reference;

use App\Entity\Reference\FileType;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class FileTypeController.php
 * @package App\Controller\Reference
 * @Route("/api/reference/file-type")
 * @Resource(
 *     description="Main desc",
 *     tags={"FileType"},
 *     summariesMap={
 *          "list": "Получить список типов файлов",
 *          "get": "Получить тип файлов",
 *          "post": "Создать тип файлов",
 *          "delete": "Удалить тип файлов",
 *          "patch": "Обновить тип файлов"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список типов файлов",
 *          "get": "Возвращает тип файлов по идентификатору",
 *          "post": "Создает новый тип файлов",
 *          "delete": "Удаляет существующий тип файлов",
 *          "patch": "Обновляет существующий тип файлов"
 *     }
 * )
 */
class FileTypeController extends AbstractController
{
    public const ENTITY_CLASS = FileType::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}

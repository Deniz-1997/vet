<?php


namespace App\Controller;


use App\Entity\ImportExportFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
/**
 * Class ImportExportFileController
 * @package App\Controller
 * @Route("/api/import-export-file")
 * @Resource(
 *     tags={"ImportExportFile"},
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
class ImportExportFileController extends AbstractController
{
    public const ENTITY_CLASS = ImportExportFile::class;

    use GetListTrait, GetItemTrait, AddItemTrait, DeleteItemTrait;
}

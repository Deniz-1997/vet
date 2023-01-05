<?php
/**
 * Created by PhpStorm.
 * User: viktorkrasnov
 * Date: 24.08.17
 * Time: 3:47
 */

namespace App\Controller;

use App\Entity\File;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class FileController
 * @Route("/api/file")
 * @Resource(
 *     description="Main desc",
 *     tags={"File"},
 *     summariesMap={
 *          "list": "Получить список документов",
 *          "get": "Получить документ",
 *          "post": "Создать документ",
 *          "delete": "Удалить документ",
 *          "patch": "Обновить документ"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список документов",
 *          "get": "Возвращает документ по идентификатору",
 *          "post": "Создает новый документ",
 *          "delete": "Удаляет существующий документ",
 *          "patch": "Обновляет существующий документ"
 *     }
 * )
 */
class FileController extends Controller
{
    public const ENTITY_CLASS = File::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}

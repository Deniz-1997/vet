<?php


namespace App\Controller;

use App\Entity\UserSchedule;
use App\Controller\ApiController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\Batch\AddBatchTrait;
use App\Controller\CRUD\Batch\DeleteBatchTrait;
use App\Controller\CRUD\Batch\UpdateBatchTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use App\Packages\Annotation\Resource;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class UserScheduleController
 * @package App\Controller
 * @Route("/api/user-schedule")
 * @Resource(
 *     description="Main desc",
 *     tags={"UserSchedule"},
 *     summariesMap={
 *          "list": "Получить список смен пользователей",
 *          "get": "Получить смену пользователея",
 *          "post": "Создать смену пользователея",
 *          "delete": "Удалить смену пользователея",
 *          "patch": "Обновить смену пользователея",
 *          "batch-post": "Пакетное добавление смен пользователя",
 *          "batch-delete": "Пакетное удаление смен пользователя",
 *          "batch-patch": "Пакетное обновление смен пользователя",
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список смен пользователей",
 *          "get": "Возвращает смену пользователея по идентификатору",
 *          "post": "Создает новую смену пользователя",
 *          "delete": "Удаляет существующую смену пользователя",
 *          "patch": "Обновляет существующую смену пользователя",
 *          "batch-post": "Создает несколько смен пользователя",
 *          "batch-delete": "Удаляет несколько смен пользователя",
 *          "batch-patch": "Обновляет несколько смен пользователя",
 *     }
 * )
 */
class UserScheduleController extends ApiController
{
    public const ENTITY_CLASS = UserSchedule::class;

    use AddBatchTrait, UpdateBatchTrait, DeleteBatchTrait;
    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}

<?php
/**
 * Created by PhpStorm.
 * User: viktorkrasnov
 * Date: 24.08.17
 * Time: 3:47
 */

namespace App\Controller;

use App\Entity\Pet\Pet;
use App\Packages\Annotation\Resource;
use App\Controller\ApiController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use App\Controller\CRUD\Batch\AddBatchTrait;
use App\Controller\CRUD\Batch\UpdateBatchTrait;
use App\Controller\CRUD\Batch\DeleteBatchTrait;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PetController
 * @package App\Controller
 * @Route("/api/pet")
 * @Resource(
 *     description="Main desc",
 *     tags={"Pet"},
 *     summariesMap={
 *          "list": "Получить список животных",
 *          "get": "Получить животное",
 *          "post": "Создать животное",
 *          "delete": "Удалить животное",
 *          "patch": "Обновить животное",
 *          "batch-post": "Пакетное добавление животных",
 *          "batch-delete": "Пакетное удаление животных",
 *          "batch-patch": "Пакетное обновление животных",
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список животных",
 *          "get": "Возвращает животное по идентификатору",
 *          "post": "Создает новое животное",
 *          "delete": "Удаляет существующее животное",
 *          "patch": "Обновляет существующее животное",
 *          "batch-post": "Создает несколько животных",
 *          "batch-delete": "Удаляет несколько животных",
 *          "batch-patch": "Обновляет несколько животных",
 *     }
 * )
 */
class PetController extends ApiController
{
    public const ENTITY_CLASS = Pet::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
    use AddBatchTrait, UpdateBatchTrait, DeleteBatchTrait;

    /**
     * @return array
     */
    public function getSerializationContextOptions() : array
    {
        return [
            'getList'    => ['groups' => ['api.pet', 'api.owner']],
            'addItem'    => ['groups' => ['api.pet', 'api.owner']],
            'getItem'    => ['groups' => ['api.pet', 'api.owner']],
            'patchItem'  => ['groups' => ['api.pet', 'api.owner']],
            'updateItem' => ['groups' => ['api.pet', 'api.owner']],
        ];
    }
}

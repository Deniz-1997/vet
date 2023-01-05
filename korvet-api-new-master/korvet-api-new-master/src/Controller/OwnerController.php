<?php
/**
 * Created by PhpStorm.
 * User: viktorkrasnov
 * Date: 24.08.17
 * Time: 3:47
 */

namespace App\Controller;

use App\Entity\Owner;
use App\Packages\Annotation\Resource;
use App\Controller\ApiController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OwnerController
 * @package App\Controller
 * @Route("/api/owner")
 * @Resource(
 *     description="Main desc",
 *     tags={"Owner"},
 *     summariesMap={
 *          "list": "Получить список владельцев",
 *          "get": "Получить владельца",
 *          "post": "Создать владельца",
 *          "delete": "Удалить владельца",
 *          "patch": "Обновить владельца"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список владельцев животных",
 *          "get": "Возвращает владельца животных по идентификатору",
 *          "post": "Создает нового владельца животных",
 *          "delete": "Удаляет существующего владельца животных",
 *          "patch": "Обновляет существующего владельца животных"
 *     }
 * )
 */
class OwnerController extends ApiController
{
    public const ENTITY_CLASS = Owner::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;

    /**
     * @return array
     */
    public function getSerializationContextOptions() : array
    {
        return [
            'getList'    => ['groups' => ['api.owner', 'api.pet']],
            'addItem'    => ['groups' => ['api.owner', 'api.pet']],
            'getItem'    => ['groups' => ['api.owner', 'api.pet']],
            'patchItem'  => ['groups' => ['api.owner', 'api.pet']],
            'updateItem' => ['groups' => ['api.owner', 'api.pet']],
        ];
    }
}

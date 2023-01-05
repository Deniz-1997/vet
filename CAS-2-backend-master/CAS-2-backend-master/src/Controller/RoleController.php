<?php

namespace App\Controller;

use App\Entity\Security\Role;
use App\Repository\Security\RoleRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\ApiController;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\PatchItemTrait;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Packages\Annotation\Response as WebslonResponse;

/**
 * @Route("/api/role")
 *
 * @Resource(
 *     description="Main desc",
 *     tags={"Role"},
 *     summariesMap={
 *          "list": "Получить список ролей",
 *          "get": "Получить роль",
 *          "post": "Создать роль",
 *          "delete": "Удалить роль",
 *          "patch": "Обновить роль"
 *     },
 *     responsesMap={
 *          "list": {
 *              @WebslonResponse(response="422")
 *          }
 *     },
 *     descriptionsMap={
 *          "post": "
    Пример для создания роли:

    {
       ""name"":""Роль для суперадминистраторов"",
       ""code"":""super_admin""
    }
           "
 *     }
 * )
 */
class RoleController extends ApiController
{
    use GetListTrait, AddItemTrait, PatchItemTrait, GetItemTrait, DeleteItemTrait;

    const ENTITY_CLASS = Role::class;

    /**
     * @return array
     */
    public function getSerializationContextOptions() : array
    {
        return [
            'addItem'  => ['groups' => ['default', 'api.v1.group.list']],
            'getItem'  => ['groups' => ['default', 'api.v1.group.list', 'api.v1.role.one']],
            'getList'  => ['groups' => ['default', 'api.v1.group.list', 'api.v1.role.one']],
            'patchItem'  => ['groups' => ['default', 'api.v1.group.list', 'api.v1.group.one', 'api.v1.role.one']],
            'updateItem' => ['groups' => ['default', 'api.v1.group.list', 'api.v1.group.one', 'api.v1.role.one']],
        ];
    }
}

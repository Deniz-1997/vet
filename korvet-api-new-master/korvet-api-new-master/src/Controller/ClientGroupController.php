<?php

namespace App\Controller;

use App\Entity\Security\ClientGroup;
use App\Entity\Security\Group;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\ApiController;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\PatchItemTrait;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\GetListTrait;
use Symfony\Component\HttpFoundation\Response;
use App\Packages\Response\BaseResponse;
use OpenApi\Annotations as SWG;

/**
 * @Route("/api/client-group")
 *
 * @Resource(
 *     description="Main desc",
 *     tags={"ClientGroup"},
 *     summariesMap={
 *          "list": "Получить список групп для приложений",
 *          "get": "Получить группу для приложений",
 *          "post": "Добавить группу для приложений",
 *          "delete": "Удалить группу для приложений",
 *          "patch": "Обновить группу для приложений"
 *     }
 * )
 */
class ClientGroupController extends ApiController
{
    use GetListTrait, AddItemTrait, PatchItemTrait, GetItemTrait, DeleteItemTrait;
    const ENTITY_CLASS = ClientGroup::class;

    /**
     * @param ClientGroup  $group
     * @param BaseResponse $response
     *
     * @return Response
     *
     * @SWG\Get(
     *     description="Вывести все роли определенной группы приложений",
     *     summary="Получить все роли группы приложений",
     *     @SWG\Response(
     *         response=200,
     *         @SWG\JsonContent(),
     *         description="Успешный ответ сервиса"
     *     ),
     *     @SWG\Parameter(
     *          in="path",
     *          name="id",
     *          description="Идентификатор группы",
     *          @SWG\Schema(
     *              type="string"
     *          ),
     *          required=true
     *     )
     * )
     *
     * @Route("/{id}/role/", methods={"GET"})
     */
    public function getByGroupAction(ClientGroup $group, BaseResponse $response)
    {
        return $response->statusOk()->setResponse($group->getRoles())->setSerializationContext($this->getSerializationContext('getByGroup'))->send();
    }

    /**
     * @return array
     */
    public function getSerializationContextOptions() : array
    {
        return [
            'addItem'     => ['groups' => ['default', 'api.v1.group.list', 'api.v1.group.one']],
            'getItem'     => ['groups' => ['default', 'api.v1.group.list', 'api.v1.group.one']],
            'getList'     => ['groups' => ['default', 'api.v1.group.list']],
            'getByGroup'  => ['groups' => ['default', 'api.v1.group.list']],
            'patchItem'  => ['groups' => ['default', 'api.v1.group.list', 'api.v1.group.one']],
            'updateItem' => ['groups' => ['default', 'api.v1.group.list', 'api.v1.group.one']],
        ];
    }
}

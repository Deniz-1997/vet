<?php

namespace App\Controller;

use App\Entity\Security\Group;
use App\Repository\Security\GroupRepository;
use Doctrine\ORM\QueryBuilder;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;
use App\Packages\Annotation\Resource;
use App\Controller\ApiController;
use App\Interfaces\ApiControllerInterface;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\PatchItemTrait;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\GetListTrait;
use Symfony\Component\HttpFoundation\Response;
use App\Service\CRUD\GetListService;
use App\Exception\ApiException;
use App\Packages\Response\BaseResponse;
use OpenApi\Annotations as SWG;

/**
 * @Route("/api/group")
 *
 * @Resource(
 *     description="Main desc",
 *     tags={"Group"},
 *     summariesMap={
 *          "list": "Получить список групп",
 *          "get": "Получить группу",
 *          "post": "Добавить группу",
 *          "delete": "Удалить группу",
 *          "patch": "Обновить группу"
 *     },
 *     descriptionsMap={
 *          "post": "
    Пример для создания группы со списком ролей:

    {
        ""roles"": [{""id"": 2}, {""id"": 1}],
        ""name"": ""string""
    }
   ",
 *          "patch": "
    Пример для обновления группы со списком ролей:

    {
        ""roles"": [{""id"": 2}, {""id"": 1}],
        ""name"": ""string""
    }
"
 * }
 * )
 */
class GroupController extends ApiController implements ApiControllerInterface
{
    use GetListTrait, AddItemTrait, PatchItemTrait, GetItemTrait, DeleteItemTrait;
    const ENTITY_CLASS = Group::class;

    /**
     * @param Group        $group
     * @param BaseResponse $response
     *
     * @return Response
     *
     * @SWG\Get(
     *     description="Вывести все роли определенной группы пользователей",
     *     summary="Получить все роли группы пользователей",
     *     @SWG\Response(
     *         response=200,
     *         description="Успешный ответ сервиса",
     *         @SWG\JsonContent()
     *     ),
     *     @SWG\Parameter(
     *          in="path",
     *          name="id",
     *         @SWG\JsonContent(),
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
    public function getByGroupAction(Group $group, BaseResponse $response): Response
    {
        return $response->statusOk()->setSerializationContext($this->getSerializationContext('getRoles'))->setResponse($group->getRoles())->send();
    }

    /**
     * @return array
     */
    public function getSerializationContextOptions() : array
    {
        return [
            'addItem'    => ['groups' => ['api.v1.group.one']],
            'getItem'    => ['groups' => ['api.v1.group.one']],
            'getList'    => ['groups' => ['api.v1.group.list']],
            'patchItem'  => ['groups' => ['api.v1.group.one']],
            'updateItem' => ['groups' => ['api.v1.group.one']],
            'getRoles'   => ['groups' => ['api.v1.group.roles']]
        ];
    }
}

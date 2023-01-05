<?php

namespace App\Controller\CRUD;

use OpenApi\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Operation;
use App\Interfaces\ApiControllerInterface;
use App\Service\CRUD\GetItemService;
use App\Service\SerializationContextFetcher;
use App\Exception\ApiException;
use App\Packages\Response\Async\AsyncResponse;

/**
 * Trait GetItemTrait
 */
trait GetItemTrait
{
    /**
     * @Route("/{id}/", requirements={"id": "[a-z0-9\-\w+]{1,}"}, methods={"GET"})
     * @Operation("get")
     * @SWG\Get(
     *     @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="Идентификатор сущности",
     *          @SWG\Schema(
     *              type="string"
     *          )
     *     ),
     *     @SWG\Parameter(
     *          name="fields",
     *          in="query",
     *          @SWG\Schema(
     *              type="string"
     *          ),
     *          description="Json-объект с списком полей необходимых для получения"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         @SWG\JsonContent(),
     *         description="Успешный ответ сервиса, в результате приходят
     *     данные запрошенного объекта",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     ),
     *     @SWG\Response(
     *         response=202,
     *         @SWG\JsonContent(),
     *         description="Запрошенный объект не найден, но запрос на его создание принят в обработку.",
     *         @Model(type=AsyncResponse::class)
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         @SWG\JsonContent(),
     *         description="Entity not found",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         @SWG\JsonContent(),
     *         description="Ошибка выполнения операции",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     )
     * )
     * @param string         $id
     * @param Request        $request
     * @param GetItemService $service
     *
     * @return Response
     * @throws \InvalidArgumentException
     * @throws ApiException
     */
    public function getItemAction(string $id, Request $request, GetItemService $service, SerializationContextFetcher $serializationContextFetcher): Response
    {
        if ($this instanceof ApiControllerInterface) {
            $serializationContext = $this->getSerializationContext('getItem');
        } else {
            $serializationContext = [];
        }

        $entityClass = $this instanceof DynamicEntityClassControllerInterface ? $this->getEntityClass() : self::ENTITY_CLASS;

        $isDefinedDtoClass = defined(get_class($this).'::DTO_CLASS');
        if ($groups = $serializationContextFetcher->getSerializationGroups('detail', $isDefinedDtoClass ? self::DTO_CLASS : $entityClass)) {
            if (!isset($serializationContext['groups'])) {
                $serializationContext['groups'] = [];
            }

            $serializationContext['groups'] = array_merge($serializationContext['groups'], $groups);
        }

        if ($isDefinedDtoClass) {
            $service->setDtoClass(self::DTO_CLASS);
        }

        $result = $service->getItem($id, $entityClass, $request->get('fields'), $serializationContext);
        
        return $result->send();
    }
}

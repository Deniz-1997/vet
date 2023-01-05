<?php

namespace App\Controller\CRUD;

use Symfony\Component\HttpFoundation\Request;
use OpenApi\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Operation;
use App\Interfaces\ApiControllerInterface;
use App\Service\CRUD\ReplaceItemService;
use App\Service\SerializationContextFetcher;
use App\Service\ValidationService;
use App\Exception\ApiException;

/**
 * Trait ReplaceItemTrait
 */
trait ReplaceItemTrait
{
    /**
     * @Route("/{id}/replace", requirements={"id": "[a-z0-9\-\w+]{1,}"}, methods={"PUT"})
     * @Operation("put")
     * @SWG\Put(
     *     description="Замена сущности",
     *     summary="Замена сущности",
     *     @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="Идентификатор заменяемой сущности",
     *          @SWG\Schema(
     *              type="string"
     *          )
     *     ),
     *     @SWG\Parameter(
     *          name="entity",
     *          in="body",
     *          description="Json-объект с данными заменяемой сущности, НЕ допустима передача сущности частично, только полностью"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         @SWG\JsonContent(),
     *         description="Успешный ответ сервиса, в результате приходят данные обновленного объекта",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         @SWG\JsonContent(),
     *         description="Ошибка операции замены",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     )
     * )
     *
     * @param string            $id
     * @param Request           $request
     * @param ReplaceItemService $service
     *
     * @return Response
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws ApiException
     * @throws \Exception
     */
    public function replaceItemAction(string $id, Request $request, ReplaceItemService $service, SerializationContextFetcher $serializationContextFetcher): Response
    {
        if ($this instanceof ApiControllerInterface) {
            $serializationContext = $this->getSerializationContext('patchItem');
        } else {
            $serializationContext = [];
        }

        $entityClass = $this instanceof DynamicEntityClassControllerInterface ? $this->getEntityClass() : self::ENTITY_CLASS;

        $isDefinedDtoClass = defined(get_class($this) . '::DTO_CLASS');
        if ($groups = $serializationContextFetcher->getSerializationGroups('patch', $isDefinedDtoClass ? self::DTO_CLASS : $entityClass)) {
            if (!isset($serializationContext['groups'])) {
                $serializationContext['groups'] = [];
            }

            $serializationContext['groups'] = array_merge($serializationContext['groups'], $groups);
        }

        $outputSerializationContext = $serializationContext;
        $outputSerializationContext['groups'] = $serializationContext['groups'] ?? [];
        $outputSerializationContext['groups'][] = 'detail';

        if ($isDefinedDtoClass) {
            $service->setDtoClass(self::DTO_CLASS);
        }

        $result = $service->replace($id, $request->getContent(), $entityClass, [ValidationService::GROUP_UPDATE_PUT_REPLACE, ValidationService::GROUP_DEFAULT], $outputSerializationContext, $serializationContext);

        return $result->send();
    }
}

<?php

namespace App\Controller\CRUD;

use Symfony\Component\HttpFoundation\Request;
use OpenApi\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Operation;
use App\Interfaces\ApiControllerInterface;
use App\Service\CRUD\UpdateItemService;
use App\Service\SerializationContextFetcher;
use App\Service\ValidationService;
use App\Exception\ApiException;

/**
 * Trait UpdateItemTrait
 */
trait PatchItemTrait
{
    /**
     * @Route("/{id}/", requirements={"id": "[a-z0-9\-\w+]{1,}"}, methods={"PATCH"})
     * @Operation("patch")
     * @SWG\Patch(
     *     @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="Идентификатор обновляемой сущности",
     *          @SWG\Schema(
     *              type="string"
     *          )
     *     ),
     *     @SWG\Parameter(
     *          name="entity",
     *          in="body",
     *          description="Json-объект с данными обновляемой сущности, допустима передача сущности частично"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         @SWG\JsonContent(),
     *         description="Успешный ответ сервиса, в результате приходят
     *     данные обновленного объекта",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         @SWG\JsonContent(),
     *         description="Ошибка операции обновления",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     )
     * )
     *
     * @param string            $id
     * @param Request           $request
     * @param UpdateItemService $service
     *
     * @return Response
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws ApiException
     * @throws \Exception
     */
    public function patchItemAction(string $id, Request $request, UpdateItemService $service, SerializationContextFetcher $serializationContextFetcher): Response
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

        $result = $service->update($id, $request->getContent(), $entityClass, [ValidationService::GROUP_UPDATE, ValidationService::GROUP_DEFAULT], $outputSerializationContext, $serializationContext);

        return $result->send();
    }
}

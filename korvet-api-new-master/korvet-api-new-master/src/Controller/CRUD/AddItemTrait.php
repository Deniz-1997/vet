<?php

namespace App\Controller\CRUD;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Packages\Annotation\Operation;
use App\Interfaces\ApiControllerInterface;
use App\Service\CRUD\AddItemService;
use App\Service\SerializationContextFetcher;
use App\Service\ValidationService;
use App\Exception\ApiException;

/**
 * Trait AddItemTrait
 */
trait AddItemTrait
{
    /**
     * @Route("/", methods={"POST"})
     * @Operation("post")
     * @SWG\Post(
     *     @SWG\Parameter(
     *          name="entity",
     *          in="body",
     *          description="Json-объект с данными добавляемой сущности"
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         @SWG\JsonContent(),
     *         description="Успешный ответ сервиса, в результате приходят данные добаленного объекта",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         @SWG\JsonContent(),
     *         description="Ошибка выполнения операции",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     )
     * )
     *  examples={
     *              "application/json":{
     *                  "status": true,
     *                  "response": {"id": 1},
     *                  "errors": null
     *              }
     *         },
     *         examples={
     *              "application/json":{
     *                  "status": false,
     *                  "response": null,
     *                  "errors": {0: {"message": "Текст ошибки", "stringCode":
     *     "ERROR_STRING_CODE", "relatedField": null}}
     *              }
     *         },
     * @param Request $request
     * @param AddItemService $service
     * @param SerializationContextFetcher $serializationContextFetcher
     * @return Response
     * @throws \Exception
     */
    public function addItemAction(Request $request, AddItemService $service, SerializationContextFetcher $serializationContextFetcher): Response
    {
        if ($this instanceof ApiControllerInterface) {
            $serializationContext = $this->getSerializationContext('addItem');
        } else {
            $serializationContext = [];
        }

        $entityClass = $this instanceof DynamicEntityClassControllerInterface ? $this->getEntityClass() : self::ENTITY_CLASS;

        $isDefinedDtoClass = defined(get_class($this) . '::DTO_CLASS');
        $class = $isDefinedDtoClass ? self::DTO_CLASS : $entityClass;
        if ($groups = $serializationContextFetcher->getSerializationGroups('post', $class)) {
            if (!isset($serializationContext['groups'])) {
                $serializationContext['groups'] = [];
            }

            $serializationContext['groups'] = array_merge($serializationContext['groups'], $groups);
        }

        if ($isDefinedDtoClass) {
            $service->setDtoClass(self::DTO_CLASS);
        }

        $result = $service->add($request->getContent(), $entityClass, [ValidationService::GROUP_DEFAULT, ValidationService::GROUP_CREATE], $serializationContext, $serializationContext);

        return $result->send();
    }
}

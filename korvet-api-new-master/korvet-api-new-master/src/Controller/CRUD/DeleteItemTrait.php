<?php

namespace App\Controller\CRUD;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Packages\Annotation\Operation;
use App\Service\CRUD\DeleteItemService;
use App\Service\SerializationContextFetcher;
use App\Exception\ApiException;

/**
 * Trait DeleteItemTrait
 */
trait DeleteItemTrait
{
    /**
     * @Route("/{id}/", requirements={"id": "[a-z0-9\-\w+]{1,}"}, methods={"DELETE"})
     * @Operation("delete")
     * @SWG\Delete(
     *     @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="Идентификатор удаляемой сущности",
     *          @SWG\Schema(
     *              type="string"
     *          )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         @SWG\JsonContent(),
     *         description="Сущность успешно удалена",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         @SWG\JsonContent(),
     *         description="Ошибка выполнения операции",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     )
     * )
     * @param string $id
     * @param DeleteItemService $service
     * @param SerializationContextFetcher $serializationContextFetcher
     *
     * @return Response
     * @throws ApiException
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteItemAction(string $id, DeleteItemService $service, SerializationContextFetcher $serializationContextFetcher): Response
    {
        $serializationContext = [];

        $entityClass = $this instanceof DynamicEntityClassControllerInterface ? $this->getEntityClass() : self::ENTITY_CLASS;

        if ($groups = $serializationContextFetcher->getSerializationGroups('delete', $entityClass)) {
            $serializationContext['groups'] = $groups;
        }

        $result = $service->deleteItem($id, $entityClass, $serializationContext);

        return $result->send();
    }
}

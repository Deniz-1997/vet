<?php

namespace App\Service\CRUD;

use App\Interfaces\CRUD\DeleteItemInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Packages\EventDispatcher\EventRequest;
use App\Exception\ApiException;
use App\Packages\Response\BaseResponse as ApiResponse;

/**
 * Class DeleteItemService
 */
class DeleteItemService extends AbstractService implements DeleteItemInterface
{
    /**
     * @param string $id
     * @param string $object
     * @param array $serializationContext
     *
     * @return ApiResponse
     * @throws ApiException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function deleteItem(string $id, string $object, array $serializationContext): ApiResponse
    {
        $eventName = $this->getEventName($object, Request::METHOD_DELETE);
        $beforeEvent = $this->generateEvent(['id' => $id, 'object' => $object], [EventRequest::BEFORE_PROCESS . ucfirst($eventName)]);
        $this->assertEvent($beforeEvent);

        if ($response = $beforeEvent->getResponse()) {
            return $this->getDependencies()->getResponse()->setResponse($response);
        }
        $entity = $this->dependencies->getOm()->getRepository($object)->find($id);
        if (!$entity instanceof $object) {
            throw new ApiException("Cannot find entity {$object} with id = {$id}", 'Error_400', null, Response::HTTP_BAD_REQUEST);
        }
        $this->deleteEntity($entity);
        $this->generateEvent($entity, [EventRequest::AFTER_PROCESS . ucfirst($eventName)]);

        return $this->dependencies->getResponse()->setSerializationContext($serializationContext);
    }
}

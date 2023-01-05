<?php

namespace App\Service\CRUD;

use App\Interfaces\CRUD\PatchItemInterface;
use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Packages\EventDispatcher\EventRequest;
use App\Exception\ApiException;
use App\Packages\Response\BaseResponse as ApiResponse;
use App\Packages\Response\BaseResponse;
use \App\Service\HandlerException\Validation\ValidationException;

/**
 * Class PatchItemService
 */
class PatchItemService extends AbstractService implements PatchItemInterface
{
    /**
     * @param string $id
     * @param string $data
     * @param string $objectName
     * @param string|null $validationGroup
     *
     * @return BaseResponse
     * @throws ApiException
     * @throws ValidationException
     * @throws Exception
     */
    public function patch(string $id, string $data, string $objectName, string $validationGroup = null): ApiResponse
    {
        // for log
        $this->dependencies->originalData = [
            'id' => $id,
            'objectName' => $objectName,
            'data' => $data,
            'method' => __METHOD__
        ];
        /** @var EventRequest $beforeEvent */
        $eventName = $this->getEventName($objectName, Request::METHOD_PATCH);
        $beforeEvent = $this->generateEvent(['id' => $id, 'data' => $data, 'objectName' => $objectName], [EventRequest::BEFORE_PROCESS . ucfirst($eventName)]);
        $this->assertEvent($beforeEvent);

        if (($response = $beforeEvent->getResponse())) {
            return $this->getDependencies()->getResponse()->setResponse($response);
        }
        $objectToPopulate = $this->dependencies->getOm()->getRepository($objectName)->findOneBy(['id' => $id]);
        if (!$objectToPopulate) {
            throw new ApiException("Object not found: {$objectName} with id = {$id}", 'Error_404', null, Response::HTTP_NOT_FOUND);
        }
        if (!$objectToPopulate instanceof $objectName) {
            throw new ApiException("Error object_to_populate: {$objectName} with id = {$id}", 'Error_400', null, Response::HTTP_BAD_REQUEST);
        }

        $entity = $this->dependencies->getSerializer()->deserialize($data,
            $objectName, 'json', [
                'object_to_populate' => $objectToPopulate
            ]);

        $this->generateEvent($entity, [EventRequest::BEFORE_PROCESS_ENTITY . ucfirst($eventName)]);

        $this->dependencies->getValidator()->validate($entity, null, $validationGroup);
        $response = $this->saveEntity($entity);
        $this->generateEvent($entity, [EventRequest::AFTER_PROCESS . ucfirst($eventName)]);

        return $response
            ->setHttpResponseCode(Response::HTTP_OK);
    }

    /**
     * @param object $entity
     * @param string $objectName
     * @param string|null $validationGroup
     *
     * @return BaseResponse
     * @throws ApiException
     * @throws ValidationException
     * @throws Exception
     */
    public function patchByObject(object $entity, string $objectName, string $validationGroup = null): ApiResponse
    {
        $eventName = $this->getEventName($objectName, Request::METHOD_PATCH);
        /** @var EventRequest $beforeEvent */
        $beforeEvent = $this->generateEvent($entity, [EventRequest::BEFORE_PROCESS . ucfirst($eventName)]);
        $this->assertEvent($beforeEvent);

        if (($response = $beforeEvent->getResponse()) instanceof ApiResponse) {
            return $response;
        }
        if (!$entity instanceof $objectName) {
            throw new ApiException("Entity is not valid", 'Error_400', null, Response::HTTP_BAD_REQUEST);
        }

        $this->dependencies->getValidator()->validate($entity, null, $validationGroup);
        if (property_exists($entity, 'updatedAt') && method_exists($entity, 'setUpdatedAt')) {
            $entity->setUpdatedAt(new DateTime());
        }

        $response = $this->saveEntity($entity);
        $this->generateEvent($entity, [EventRequest::AFTER_PROCESS . ucfirst($eventName)]);

        return $response
            ->setHttpResponseCode(Response::HTTP_OK);
    }
}

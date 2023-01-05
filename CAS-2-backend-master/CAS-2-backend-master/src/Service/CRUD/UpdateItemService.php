<?php

namespace App\Service\CRUD;

use App\Interfaces\CRUD\UpdateItemInterface;
use App\Interfaces\JsonDataEntityInterface;
use DateTime;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Packages\EventDispatcher\EventRequest;
use App\Packages\EventDispatcher\UpdateItemEvent;
use App\Exception\ApiException;
use App\Packages\Response\BaseResponse as ApiResponse;
use App\Packages\Response\BaseResponse;
use \App\Service\HandlerException\Validation\ValidationException;

/**
 * Class UpdateItemService
 */
class UpdateItemService extends AbstractService implements UpdateItemInterface
{
    /**
     * @param string $id
     * @param string $data
     * @param string $objectName
     * @param null   $validationGroup
     * @param array  $outputSerializationContext
     * @param array  $inputSerializationContext
     *
     * @return BaseResponse
     * @throws ApiException
     * @throws ValidationException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(string $id, string $data, string $objectName, $validationGroup = null, array $outputSerializationContext = [], array $inputSerializationContext = [], string $attributes = null): ApiResponse
    {
        // for log
        $this->dependencies->originalData = [
            'objectName' => $objectName,
            'id' => $id,
            'data' => $data,
            'method' => __METHOD__
        ];

        if ($validationGroup && !is_array($validationGroup)) {
            $validationGroup = [$validationGroup];
        }
        $eventName = $this->getEventName($objectName, Request::METHOD_PUT);
        $eventData = ['id' => $id, 'content' => $data, 'objectName' => $objectName, 'serializationContext' => $outputSerializationContext];
        /** @var EventRequest $beforeEvent */
        $beforeEvent = $this->generateEvent($eventData, [EventRequest::BEFORE_PROCESS . ucfirst($eventName)]);
        $this->assertEvent($beforeEvent);

        if ($response = $beforeEvent->getResponse()) {
            return $this->getDependencies()->getResponse()->setResponse($response);
        }
        if (!$entity = $this->dependencies->getOm()->getRepository($objectName)->find($id)) {
            throw new ApiException("Object not found: {$objectName} with id = {$id}", 'Error_404', null, Response::HTTP_NOT_FOUND);
        }
        if (!$entity instanceof $objectName) {
            throw new ApiException("Error object_to_populate: {$objectName} with id = {$id}", 'Error_400', null, Response::HTTP_BAD_REQUEST);
        }
        // change request data
        $data = $beforeEvent->getData();
        if (is_array($data) && isset($data['content'])) {
            $data = $data['content'];
        }

        if ($dtoClass = $this->getDtoClass()) {
            if (!$entity instanceof JsonDataEntityInterface) {
                throw new RuntimeException(sprintf('Class %s must implement %s', $entity, JsonDataEntityInterface::class));
            }

            $dtoObject = $this->dependencies->getDtoFactory()->getDTO($dtoClass);

            if ($jsonData = $entity->getJsonData()) {
                $json = json_encode($jsonData);
                $this->dependencies->getSerializer()->deserialize($json, $dtoClass, 'json', ['object_to_populate' => $dtoObject]);
            }

            $this->dependencies->getSerializer()->deserialize($data, $dtoClass, 'json', ['object_to_populate' => $dtoObject]);
            $this->dependencies->getValidator()->validate($dtoObject, null, $validationGroup);
            $entity = $dtoObject->createEntity();

            $json = $this->dependencies->getSerializer()->serialize($dtoObject, 'json', ['object_to_populate' => $entity]);
            $entity->setJsonData(json_decode($json, true));
        } else {
            $inputSerializationContext = array_merge($inputSerializationContext, ['object_to_populate' => $entity]);
            $entity = $this->dependencies->getSerializer()->deserialize($data, $objectName, 'json', $inputSerializationContext);
            $this->dependencies->getValidator()->validate($entity, null, $validationGroup);
        }
        $this->generateEvent($entity, [EventRequest::BEFORE_PROCESS_ENTITY . ucfirst($eventName)]);

        if (property_exists($entity, 'updatedAt') && method_exists($entity, 'setUpdatedAt')) {
            $entity->setUpdatedAt(new DateTime());
        }

        if (isset($dtoObject)) {
            $this->dependencies->saveEntity($entity);
            $response = $this->dependencies->getResponse()
                ->setResponse($dtoObject);
        } else {
            $response = $this->saveEntity($entity);
        }

        $this->generateEvent($entity, [EventRequest::AFTER_PROCESS . ucfirst($eventName)]);
        $this->dependencies->getDispatcher()->dispatch(new UpdateItemEvent($entity), UpdateItemEvent::NAME);

        $response
            ->setSerializationContext($outputSerializationContext)
            ->setHttpResponseCode(Response::HTTP_OK);

        if ($attributes !== null) {
            $att_array = json_decode(str_replace("'", '"', $attributes), true);
            if (is_array($att_array)) {
                $response->setAttributes($att_array);
            }
        }

        return $response;
    }

    /**
     * @param object $entity
     * @param string $objectName
     * @param string|null $validationGroup
     * @param array $outputSerializationContext
     * @return BaseResponse
     * @throws ApiException
     * @throws ValidationException
     */
    public function updateByObject(object $entity, string $objectName, string $validationGroup = null, array $outputSerializationContext = []): ApiResponse
    {
        $eventName = $this->getEventName($objectName, Request::METHOD_PATCH);
        /** @var EventRequest $beforeEvent */
        $beforeEvent = $this->generateEvent($entity, [EventRequest::BEFORE_PROCESS . ucfirst($eventName)]);
        $this->assertEvent($beforeEvent);

        if ($response = $beforeEvent->getResponse()) {
            return $response;
        }
        if (!$entity instanceof $objectName) {
            throw new ApiException('Entity is not valid', 'Error_400', null, Response::HTTP_BAD_REQUEST);
        }
        if (property_exists($entity, 'updatedAt') && method_exists($entity, 'setUpdatedAt')) {
            $entity->setUpdatedAt(new DateTime());
        }
        $this->dependencies->getValidator()->validate($entity, null, $validationGroup);

        $response = $this->saveEntity($entity);
        $this->generateEvent($entity, [EventRequest::AFTER_PROCESS . ucfirst($eventName)]);

        return $response
            ->setSerializationContext($outputSerializationContext)
            ->setHttpResponseCode(Response::HTTP_OK);
    }
}

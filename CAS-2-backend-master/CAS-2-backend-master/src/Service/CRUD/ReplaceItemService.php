<?php

namespace App\Service\CRUD;

use App\Interfaces\CRUD\ReplaceItemInterface;
use App\Interfaces\JsonDataEntityInterface;
use DateTime;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Packages\EventDispatcher\EventRequest;
use App\Packages\EventDispatcher\ReplaceItemEvent;
use App\Exception\ApiException;
use App\Packages\Response\BaseResponse;
use App\Packages\Response\BaseResponse as ApiResponse;
use App\Service\HandlerException\Validation\ValidationException;

/**
 * Class ReplaceItemService
 */
class ReplaceItemService extends AbstractService implements ReplaceItemInterface
{
    /**
     * @param string $id
     * @param string $data
     * @param string $objectName
     * @param array|string|null $validationGroup
     * @param array $outputSerializationContext
     * @param array $inputSerializationContext
     *
     * @return BaseResponse
     * @throws ApiException
     * @throws ValidationException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function replace(string $id, string $data, string $objectName, $validationGroup = null, array $outputSerializationContext = [], array $inputSerializationContext = []): ApiResponse
    {
        // for log
        $this->dependencies->originalData = [
            'id' => $id,
            'objectName' => $objectName,
            'data' => $data,
            'method' => __METHOD__
        ];
        if ($validationGroup && !is_array($validationGroup)) {
            $validationGroup = [$validationGroup];
        }
        $eventName = $this->getEventName($objectName, Request::METHOD_PUT.'.REPLACE');
        $eventData = ['id' => $id, 'content' => $data, 'objectName' => $objectName, 'serializationContext' => $outputSerializationContext];
        /** @var EventRequest $beforeEvent */
        $beforeEvent = $this->generateEvent($eventData, [EventRequest::BEFORE_PROCESS . ucfirst($eventName)]);
        $this->assertEvent($beforeEvent);
        if ($response = $beforeEvent->getResponse()) {
            return $this->getDependencies()->getResponse()->setResponse($response);
        }
        $isNew = false;
        if(!$entity = $this->getDependencies()->getOm()->getRepository($objectName)->find($id)){
            $entity = new $objectName();
            $isNew = true;
        }
        $entityDb = clone $entity;
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
            $this->dependencies->getSerializer()->deserialize($data, $dtoClass, 'json', ['object_to_populate' => $dtoObject]);
            if (!$isNew) {
                $this->controlId($dtoObject, $entityDb);
            }
            $entity = $dtoObject->createEntity();
            $json = $this->dependencies->getSerializer()->serialize($dtoObject, 'json', ['object_to_populate' => $entity]);
            $entity->setJsonData(json_decode($json, true));
            $this->dependencies->getValidator()->validate($dtoObject, null, $validationGroup);
        } else {
            $inputSerializationContext = array_merge($inputSerializationContext, ['object_to_populate' => $entity]);
            $entity = $this->dependencies->getSerializer()->deserialize($data, $objectName, 'json', $inputSerializationContext);
            $this->dependencies->getValidator()->validate($entity, null, $validationGroup);
        }
        if (property_exists($entity, 'updatedAt') && method_exists($entity, 'setUpdatedAt')) {
            $entity->setUpdatedAt(new DateTime());
        }
        $this->getDependencies()->getOm()->beginTransaction();
        try{
            if (isset($dtoObject)) {
                $this->dependencies->saveEntity($entity);
                $this->getDependencies()->getOm()->commit();
                $response = $this->dependencies->getResponse()
                    ->setResponse($dtoObject);
            } else {
                $response = $this->saveEntity($entity);
            }
        } catch (Exception $exception){
            $this->getDependencies()->getOm()->rollback();
            throw $exception;
        }
        $this->generateEvent($entity, [EventRequest::AFTER_PROCESS . ucfirst($eventName)]);
        $this->dependencies->getDispatcher()->dispatch(ReplaceItemEvent::NAME, new ReplaceItemEvent($entity));

        return $response
            ->setSerializationContext($outputSerializationContext)
            ->setHttpResponseCode(Response::HTTP_OK);
    }

    /**
     * @param object $dtoObject
     * @param object $entityDb
     */
    private function controlId(object $dtoObject, object $entityDb)
    {
        if (method_exists($dtoObject, 'getId') && method_exists($dtoObject, 'setId') && method_exists($entityDb, 'getId')) {
            $dtoObject->setId($entityDb->getId());
        } else {
            $dtoObject->id = $entityDb->getId();
        }
        if (property_exists(get_class($entityDb),'puzId')) {
            if (method_exists($dtoObject, 'getPuzId') && method_exists($dtoObject, 'setPuzId') && method_exists($entityDb, 'getPuzId')) {
                $dtoObject->setPuzId($entityDb->getPuzId());
            } else {
                $dtoObject->puzId = $entityDb->getPuzId();
            }
        }
    }
}

<?php

namespace App\Service\CRUD;

use App\Interfaces\CRUD\AddItemInterface;
use App\Interfaces\JsonDataEntityInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Packages\EventDispatcher\AddItemEvent;
use App\Packages\EventDispatcher\EventRequest;
use App\Service\ValidationService;
use App\Exception\ApiException;
use App\Packages\Response\BaseResponse as ApiResponse;
use App\Service\HandlerException\Validation\ValidationException;

/**
 * Class AddItemService
 */
class AddItemService extends AbstractService implements AddItemInterface
{
    /**
     * @var object
     */
    private object $createdEntity;

    /**
     * @param string $data
     * @param string $objectName
     * @param null   $validationGroup
     * @param array  $inputSerializationContext
     * @param array  $outputSerializationContext
     *
     * @return ApiResponse
     * @throws \Exception
     */
    public function add(string $data, string $objectName, $validationGroup = null, array $inputSerializationContext = [], array $outputSerializationContext = [], string $attributes = null): ApiResponse
    {
        // for log
        $this->dependencies->originalData = [
            'objectName' => $objectName,
            'data' => $data,
            'method' => __METHOD__
        ];
        if ($validationGroup && is_string($validationGroup)) {
            $validationGroup = [ValidationService::GROUP_DEFAULT, $validationGroup];
        }
        $eventName = $this->getEventName($objectName, Request::METHOD_POST);
        /** @var EventRequest $beforeEvent */
        $beforeEvent = $this->generateEvent($data, [EventRequest::BEFORE_PROCESS . ucfirst($eventName)]);
        $this->assertEvent($beforeEvent);
        if ($response = $beforeEvent->getResponse()) {
            return $response;
        }

        $beforeEventData = $beforeEvent->getData();
        if ($beforeEventData) {
            $data = is_array($beforeEventData) ? json_encode($beforeEventData) : $beforeEventData;
        }

        if ($dtoClass = $this->getDtoClass()) {
            $dto = $this->dependencies->getDtoFactory()->getDTO($dtoClass);
            $inputSerializationContext['object_to_populate'] = $dto;

            $this->dependencies->getSerializer()->deserialize($data, $dtoClass, 'json', $inputSerializationContext);
            $this->dependencies->getValidator()->validate($dto, null, $validationGroup);

            /** @var JsonDataEntityInterface $entity */
            $entity = $dto->createEntity();
            if (!$entity instanceof JsonDataEntityInterface) {
                throw new \RuntimeException(sprintf(
                    'Entity %s must be implement %s', get_class($entity), JsonDataEntityInterface::class
                ));
            }
        } else {
            $entity = $this->dependencies->getSerializer()->deserialize($data, $objectName, 'json', $inputSerializationContext);
            $this->dependencies->getValidator()->validate($entity, null, $validationGroup);
        }

        if (property_exists($entity, 'createdAt') && method_exists($entity, 'setCreatedAt')) {
            $entity->setCreatedAt(new \DateTime());
        }

        $this->generateEvent($entity, [EventRequest::BEFORE_PROCESS_ENTITY . ucfirst($eventName)]);

        if (isset($dto)) {
            $this->dependencies->saveEntity($entity);
            // init jsonData
            $serialized = $this->dependencies->getSerializer()->serialize($dto, 'json');
            $entity->setJsonData(json_decode($serialized, true));

            // set property from jsonData AND set property Dto from entity (ID and Relation ID)
            $dto->loadEntity($entity);

            // save jsonData
            $serialized = $this->dependencies->getSerializer()->serialize($dto, 'json');
            $entity->setJsonData(json_decode($serialized, true));

            $this->dependencies->saveEntity($entity);

            $response = $this->dependencies
                ->getResponse()
                ->setResponse($dto)
                ->setHttpResponseCode(Response::HTTP_CREATED)
                ->setSerializationContext($outputSerializationContext);
        } else {
            $response = $this->saveEntity($entity)
                ->setHttpResponseCode(Response::HTTP_CREATED)
                ->setSerializationContext($outputSerializationContext);
        }

        $this->setCreatedEntity($entity);
        $this->generateEvent($entity, [EventRequest::AFTER_PROCESS . ucfirst($eventName)]);
        $this->dependencies->getDispatcher()->dispatch(new AddItemEvent($entity), AddItemEvent::NAME);

        if ($attributes !== null) {
            $att_array = json_decode(str_replace("'", '"', $attributes), true);
            if (is_array($att_array)) {
                $response->setAttributes($att_array);
            }
        }
        
        return $response;
    }

    /**
     * @param object $data
     * @param string $objectName
     * @param null $validationGroup
     * @param bool $isThrowValidation
     *
     * @return ApiResponse
     * @throws ApiException
     * @throws ValidationException
     * @throws \Exception
     */
    public function addByObject($data, string $objectName, $validationGroup = null, $isThrowValidation = true): ApiResponse
    {
        $eventName = $this->getEventName($objectName);
        /** @var EventRequest $beforeEvent */
        $beforeEvent = $this->generateEvent($data, [EventRequest::BEFORE_PROCESS . $eventName]);
        $this->assertEvent($beforeEvent);
        if ($response = $beforeEvent->getResponse()) {
            return $this->getDependencies()->getResponse()->setResponse($response);
        }

        if (!$data instanceof $objectName) {
            throw new BadRequestHttpException('Entity no valid');
        }
        $this->dependencies->getValidator()->validate($data, null, $validationGroup, $isThrowValidation);

        $response = $this->saveEntity($data)
            ->setHttpResponseCode(Response::HTTP_CREATED);

        $this->generateEvent($data, [EventRequest::AFTER_PROCESS . ucfirst($eventName)]);

        return $response;
    }

    /**
     * @param object $createdEntity
     */
    public function setCreatedEntity(object $createdEntity)
    {
        $this->createdEntity = $createdEntity;
    }

    /**
     * @return object
     */
    public function getCreatedEntity(): object
    {
        return $this->createdEntity;
    }
}

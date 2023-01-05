<?php

namespace App\Service\CRUD;

use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Response;
use App\Packages\EventDispatcher\EventRequest;
use App\Packages\EventDispatcher\GetItemEvent;
use App\Exception\ApiException;
use App\Packages\Response\BaseResponse as ApiResponse;
use function is_array;

/**
 * Class GetItemService
 */
class GetItemService extends AbstractService
{
    /**
     * @param string $id
     * @param string $object
     * @param string|null $attributes
     * @param array $serializationContext
     * @return ApiResponse
     * @throws ApiException
     * @throws NonUniqueResultException
     */
    public function getItem(string $id, string $object, string $attributes = null, array $serializationContext = []): ApiResponse
    {
        $eventName = $this->getEventName($object, 'getItem');
        $data = ['id' => $id, 'object' => $object, 'attributes' => $attributes, 'serializationContext' => $serializationContext];
        /** @var EventRequest $beforeEvent */
        $beforeEvent = $this->generateEvent($data, [EventRequest::BEFORE_PROCESS . ucfirst($eventName)]);
        $this->assertEvent($beforeEvent);
        if ($response = $beforeEvent->getResponse()) {
            $response = $this->getDependencies()->getResponse()
                ->setResponse($response);
            if ($beforeEvent->getHttpCode()) {
                $response->setHttpResponseCode($beforeEvent->getHttpCode());
            }
            return $response;
        }

        $entity = $this->getEntity($id, $object);
        if (
            !$entity instanceof $object
            || (property_exists($entity, 'deleted') && $entity->isDeleted() === true)
        ) {
            $this->createException('error.message_not_found_entity.with_id', [], Response::HTTP_NOT_FOUND, [
                '{objectName}' => $this->translator->trans(substr(strrchr($object, "\\"), 1)),
                '{id}' => $id,
            ], null, 'classes');
        }

        if ($dtoClass = $this->getDtoClass()) {
            $dto = $this->dependencies->getDtoFactory()->getDTO($dtoClass);
            $dto->loadEntity($entity);
            $entity = $dto;
        }

        $getItemEvent = new GetItemEvent($entity, $object, $serializationContext);
        $this->getDependencies()->getDispatcher()->dispatch($getItemEvent, GetItemEvent::NAME);

        if ($preparedResponse = $getItemEvent->getResponse()) {
            return $this->getDependencies()->getResponse()->setResponse($preparedResponse);
        }

        $entity = $getItemEvent->getItem();

        $response = $this->dependencies->getResponse()
            ->setSerializationContext($serializationContext)
            ->setResponse($entity);


        if ($attributes !== null) {
            $att_array = json_decode(str_replace("'", '"', $attributes), true);
            if (is_array($att_array)) {
                $response->setAttributes($att_array);
            }
        }
        $this->generateEvent($entity, [EventRequest::AFTER_PROCESS . ucfirst($eventName)]);

        return $response;
    }
}

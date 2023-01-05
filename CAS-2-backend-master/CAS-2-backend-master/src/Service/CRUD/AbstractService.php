<?php

namespace App\Service\CRUD;

use App\Packages\EventDispatcher\EventRequest;
use App\Exception\ApiException;
use App\Filter\DeletedFilter;
use App\Interfaces\ApiServiceInterface;
use App\Interfaces\DependenciesInterface;
use App\Packages\Response\BaseResponse;
use App\Service\DependenciesService;
use App\Traits\CreateExceptionTranslationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Translation\Translator;

/**
 * Class AbstractCrudService
 */
abstract class AbstractService implements ApiServiceInterface
{
    use CreateExceptionTranslationTrait;

    const FORMAT_JSON = 'json';

    /**@var DependenciesInterface|DependenciesService */
    protected $dependencies;

    /** @var Request */
    protected Request $request;

    /** @var Translator */
    protected Translator $translator;

    /** @var DeletedFilter */
    protected DeletedFilter $deletedFilter;

    /** @var string|null */
    protected ?string $dtoClass = null;

    /**
     * AbstractService constructor.
     *
     * @param DependenciesInterface $dependencies
     * @param DeletedFilter $deletedFilter
     */
    public function __construct(DependenciesInterface $dependencies, DeletedFilter $deletedFilter)
    {
        $this->dependencies = $dependencies;
        $this->deletedFilter = $deletedFilter;
        $this->translator = $this->dependencies->getTranslator();
        $this->request = $this->getDependencies()->getRequest();
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param object $entity
     *
     * @return BaseResponse
     * @throws Exception
     */
    public function saveEntity(object $entity): BaseResponse
    {
        if (!is_object($entity)) {
            throw new UnprocessableEntityHttpException('No valid entity');
        }
        $entityClass = $this->dependencies->getOm()->getClassMetadata(get_class($entity))->rootEntityName;
        $eventName = $this->getEventName($entityClass);
        $this->generateEvent($entity, [EventRequest::BEFORE_SAVE_ENTITY . ucfirst($eventName)]);
        $this->dependencies->saveEntity($entity);
        $this->generateEvent($entity, [EventRequest::AFTER_SAVE_ENTITY . ucfirst($eventName)]);

        return $this->dependencies->getResponse()
            ->setResponse($entity);
    }

    /**
     * @param $entity
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function deleteEntity($entity)
    {
        $entityClass = $this->dependencies->getOm()->getClassMetadata(get_class($entity))->rootEntityName;
        $eventName = $this->getEventName($entityClass);
        $this->generateEvent($entity, [EventRequest::BEFORE_DELETE_ENTITY . ucfirst($eventName)]);
        $em = $this->getDependencies()->getOm();
        if (property_exists($entity, 'deleted')) {
            $entity->setDeleted(true);
            $em->persist($entity);
        } else {
            $em->remove($entity);
        }
        $em->flush();
        $this->generateEvent($entity, [EventRequest::AFTER_DELETE_ENTITY . ucfirst($eventName)]);
    }

    /**
     * @param object $entity
     * @return BaseResponse
     * @throws Exception
     */
    public function mergeEntity(object $entity): BaseResponse
    {
        if (!is_object($entity)) {
            throw new UnprocessableEntityHttpException('No valid entity');
        }

        $entity = $this->dependencies->mergeEntity($entity);

        $response = $this->dependencies->getResponse();

        $response->setResponse($entity);

        return $this->dependencies->getResponse();
    }

    /**
     * @param mixed $data
     * @param array $eventsList
     * @param array $attrs
     *
     * @return mixed|EventRequest
     */
    public function generateEvent($data, array $eventsList = [], $attrs = []): EventRequest
    {
        $event = new EventRequest($this, $data);
        if (!empty($attrs)) {
            foreach ($attrs as $prop => $value) {
                if (property_exists(get_class($event), $prop)) {
                    $event->{$prop} = $value;
                }
            }
        }
        foreach ($eventsList as $eventName) {
            $event->name = $eventName;
            $this->dependencies->getDispatcher()->dispatch($eventName, $event);
        }

        return $event;
    }

    /**
     * @param string $objectName
     * @param null $method
     * @return string
     */
    public function getEventName(string $objectName, $method = null): string
    {
        return str_replace('\\', '', $objectName) . ucfirst(strtolower($method ?? ($this->request ? $this->request->getMethod() : '')));
    }

    /**
     * @param EventRequest $eventRequest
     *
     * @throws ApiException
     */
    protected function assertEvent(EventRequest $eventRequest)
    {
        if (!$eventRequest->isValid) {
            throw $this->errorException();
        }
    }

    /**
     * @param string $message
     *
     * @return ApiException
     */
    protected function errorException($message = 'Someting when wrong'): ApiException
    {
        return new ApiException($message);
    }

    /**
     * @param integer $id
     * @param string $entityName
     *
     * @return null|object
     * @throws NonUniqueResultException
     */
    public function getEntity(string $id, string $entityName): ?object
    {
        $repository = $this->dependencies->getOm()->getRepository($entityName);

        if ($this->deletedFilter->support($entityName, [])) {
            $arr = [];
            $qb = $repository->createQueryBuilder('e')
                ->where('e.id = :id')
                ->setParameter('id', $id);
            $this->deletedFilter->handle($qb, $entityName, 'e', $arr);

            return $qb->getQuery()->getOneOrNullResult();
        }

        //sometimes it's necessary to override find-method. Look at lm-catalog
        return $repository->find($id);

    }

    /**
     * @return DependenciesInterface
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }

    /**
     * @return string|null
     */
    public function getDtoClass(): ?string
    {
        return $this->dtoClass;
    }

    /**
     * @param string $dtoClass
     */
    public function setDtoClass(string $dtoClass)
    {
        $this->dtoClass = $dtoClass;
    }
}

<?php

namespace App\Service;

use App\Packages\DTO\DTOFactory;
use App\Interfaces\DependenciesInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;
use App\Exception\ApiException;
use App\Packages\Response\BaseResponse as ApiResponse;

/**
 * Class DependenciesService
 */
class DependenciesService implements DependenciesInterface
{
    /** @var EntityManagerInterface|ObjectManager */
    private $om;

    /** @var EntityManagerInterface|ObjectManager */
    private $oldOm;

    /** @var SerializerInterface */
    private SerializerInterface $serializer;

    /** @var ApiResponse */
    private ApiResponse $response;

    /** @var ValidationService */
    private ValidationService $validator;

    /** @var EventDispatcherInterface */
    private EventDispatcherInterface $dispatcher;

    /** @var RequestStack */
    private RequestStack $request;

    /** @var LoggerInterface */
    private LoggerInterface $logger;

    /** @var TranslatorInterface|Translator*/
    private $translator;

    /** @var DTOFactory|null */
    private ?DTOFactory $dtoFactory;

    /** @var array - arguments. for send to log */
    public array $originalData;

    public bool $isOldDB = false;

    /**
     * DependenciesService constructor.
     *
     * @param EntityManagerInterface   $om
     * @param SerializerInterface      $serializer
     * @param ApiResponse              $response
     * @param ValidationService        $validator
     * @param EventDispatcherInterface $dispatcher
     * @param RequestStack             $request
     * @param LoggerInterface          $logger
     * @param Translator               $translator
     * @param DTOFactory|null          $dtoFactory
     */
    public function __construct(
        EntityManagerInterface $om,
        EntityManagerInterface $oldOm,
                                SerializerInterface $serializer, ApiResponse $response, ValidationService $validator,
                                EventDispatcherInterface $dispatcher, RequestStack $request, LoggerInterface $logger, Translator $translator, ?DTOFactory $dtoFactory) {
        $this->om = $om;
        $this->oldOm = $oldOm;
        $this->serializer = $serializer;
        $this->response = $response;
        $this->validator = $validator;
        $this->dispatcher = $dispatcher;
        $this->request = $request;
        $this->logger = $logger;
        $this->translator = $translator;
        $this->dtoFactory = $dtoFactory;
    }
    
    /**
     * @return ObjectManager | EntityManagerInterface
     */
    public function getOm()
    {
        return !$this->getIsOldBD()? $this->om : $this->oldOm;
    }

    /**
     * @return SerializerInterface
     */
    public function getSerializer(): SerializerInterface
    {
        return $this->serializer;
    }
    
    /**
     * @return ApiResponse
     */
    public function getResponse(): ApiResponse
    {
        return $this->response;
    }
    
    /**
     * @return ValidationService
     */
    public function getValidator(): ValidationService
    {
        return $this->validator;
    }
    
    /**
     * @return EventDispatcherInterface
     */
    public function getDispatcher(): EventDispatcherInterface
    {
        return $this->dispatcher;
    }
    
    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request->getCurrentRequest();
    }
    
    /**
     * @return Translator
     */
    public function getTranslator(): Translator
    {
        return $this->translator;
    }

    /**
     * @param object $entity
     * @return void
     * @throws ApiException
     * @example
     *          for($entities as $index => $entity) {
     *              $this->saveEntity($entity, false); // persist (NOT flush - not insert/update to database)
     *              if($lastEntity) {
     *                  $this->saveEntity($entity, true); // // persist AND flush
     *              }
     *          }
     */
    public function saveEntity(object $entity)
    {
        try{
            // add entity to unit of work
            $this->getOm()->persist($entity);
            $this->getOm()->flush();
        }catch (UniqueConstraintViolationException $exception) {
            if (!$this->getOm()->isOpen()) {
                $this->om = $this->getOm()->create($this->getOm()->getConnection(), $this->getOm()->getConfiguration(), $this->getOm()->getEventManager());
            }
            $this->getLogger()->debug($exception->getMessage(), [
                'originalData' => $this->originalData,
                'method' => __METHOD__,
                'entity' => get_class($entity),
                'typeException' => get_class($exception),
            ]);
            $ex = (new ApiException($exception->getMessage(), 'Error_500', null, Response::HTTP_INTERNAL_SERVER_ERROR))
                ->setErrorTrace($exception->getTraceAsString());
            $this->originalData = [];
            throw $ex;
        }
    }

    /**
     * @param $entity
     */
    public function removeEntity($entity)
    {
        $this->getOm()->remove($entity);
        $this->getOm()->flush();
    }
    
    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @return DTOFactory
     */
    public function getDtoFactory(): DTOFactory
    {
        return $this->dtoFactory;
    }

    public function getIsOldBD(): bool{
        return $this->isOldDB;
    }

    public function setIsOldBD(bool $val): void{
        $this->isOldDB = $val;
    }
}

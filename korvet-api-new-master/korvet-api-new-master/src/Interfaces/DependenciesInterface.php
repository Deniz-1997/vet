<?php

namespace App\Interfaces;

use App\Packages\DTO\DTOFactory;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Translation\Translator;
use App\Packages\Response\BaseResponse as ApiResponse;

/**
 * Interface DependenciesInterface
 */
interface DependenciesInterface
{
    /**
     * @return EntityManager|EntityManagerInterface
     */
    public function getOm();

    /**
     * @return SerializerInterface
     */
    public function getSerializer(): SerializerInterface;

    /**
     * @return ApiResponse
     */
    public function getResponse(): ApiResponse;

    /**
     * @return ValidationService
     */
    public function getValidator(): ValidationService;

    /**
     * @return EventDispatcher
     */
    public function getDispatcher();

    /**
     * Method defined in trait SaveEntityTrait
     *
     * @param object $entity
     */
    public function saveEntity(object $entity);

    /**
     * @param mixed $entity
     *
     * @return mixed
     */
    public function removeEntity($entity);

    /**
     * @return RequestStack|Request
     */
    public function getRequest();

    /**
     * @return Translator
     */
    public function getTranslator(): Translator;

    /**
     * @return DTOFactory
     */
    public function getDtoFactory(): DTOFactory;
}

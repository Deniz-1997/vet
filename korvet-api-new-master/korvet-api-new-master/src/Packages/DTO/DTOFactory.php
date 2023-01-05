<?php

namespace App\Packages\DTO;

use App\Interfaces\DTOControllerInterface;
use Doctrine\Common\Annotations\Reader;
use ReflectionClass;
use ReflectionException;
use RuntimeException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Packages\Annotation\DTO;

/**
 * Class DTOFactory
 */
class DTOFactory
{
    /** @var ContainerInterface */
    private ContainerInterface $container;

    /** @var Reader */
    private Reader $annotationReader;

    /**
     * DTOFactory constructor.
     * @param ContainerInterface $container
     * @param Reader $annotationReader
     */
    public function __construct(ContainerInterface $container, Reader $annotationReader)
    {
        $this->container = $container;
        $this->annotationReader = $annotationReader;
    }

    /**
     * @param string $dtoClass
     * @return DTOControllerInterface
     */
    public function getDTO(string $dtoClass): DTOControllerInterface
    {
        if ($this->container->has($dtoClass)) {
            $object = clone $this->container->get($dtoClass);
        } else {
            $object = new $dtoClass();
        }

        if (!$object instanceof DTOControllerInterface) {
            throw new RuntimeException(sprintf('Class %s must implement %s', $dtoClass, DTOControllerInterface::class));
        }

        return $object;
    }

    /**
     * @param $dto
     * @return string|null
     * @throws ReflectionException
     */
    public function getEntityClassForDto($dto): ?string
    {
        $dtoMetadata = $this->getDtoAnnotation($dto);
        if (!$dtoMetadata) {
            return null;
        }

        return $dtoMetadata->entityClass;
    }

    /**
     * @param object|string $dto
     * @return DTO|object|null
     * @throws ReflectionException
     */
    private function getDtoAnnotation($dto)
    {
        $class = is_object($dto) ? get_class($dto) : $dto;

        return $this->annotationReader->getClassAnnotation(new ReflectionClass($class), DTO::class);
    }
}

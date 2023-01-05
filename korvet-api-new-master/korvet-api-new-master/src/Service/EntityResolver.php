<?php

namespace App\Service;

use App\Packages\Annotation\History;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use ReflectionException;

/**
 * Class EntityResolver
 */
class EntityResolver
{
    /**
     * @var AnnotationReader
     */
    private AnnotationReader $reader;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * EntityResolver constructor.
     *
     * @param AnnotationReader $reader
     * @param EntityManagerInterface $em
     */
    public function __construct(AnnotationReader $reader, EntityManagerInterface $em)
    {
        $this->reader = $reader;
        $this->em = $em;
    }

    /**
     * @param string $alias
     *
     * @return History|null
     * @throws ReflectionException
     */
    public function resolve(string $alias):?History
    {
        $historyAnnotation = null;
        foreach ($this->em->getMetadataFactory()->getAllMetadata() as $classMetadata) {
            if ($historyAnnotation = $this->reader->getClassAnnotation(new ReflectionClass($classMetadata->getName()), History::class)) {
                /** @var History $historyAnnotation */
                if ($historyAnnotation->alias === $alias) {
                    $historyAnnotation->entity = $classMetadata->getName();
                    break;
                }
            }
        }

        return $historyAnnotation;
    }

    /**
     * @param string $alias
     *
     * @return string|null
     * @throws ReflectionException
     */
    public function resolveEntity(string $alias):?string
    {
        $entityNamespace = null;
        foreach ($this->em->getMetadataFactory()->getAllMetadata() as $classMetadata) {
            if ($historyAnnotation = $this->reader->getClassAnnotation(new ReflectionClass($classMetadata->getName()), History::class)) {
                /** @var History $historyAnnotation */
                if ($historyAnnotation->alias === $alias) {
                    $entityNamespace = $classMetadata->getName();
                    break;
                }
            }
        }

        return $entityNamespace;
    }
    /**
     * @param object|string $entity
     *
     * @return History|object
     * @throws ReflectionException
     */
    public function getData($entity)
    {
        $entity = is_object($entity) ? get_class($entity) : $entity;

        return $this->reader->getClassAnnotation(new ReflectionClass($entity), History::class);
    }
}

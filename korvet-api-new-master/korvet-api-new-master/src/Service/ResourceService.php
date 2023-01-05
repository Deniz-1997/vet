<?php

namespace App\Service;

use App\Entity\Resource;
use App\Entity\Template;
use App\Entity\Theme;
use App\Repository\ResourceRepository;
use Doctrine\Persistence\ObjectManager;
use RuntimeException;
use function count;

class ResourceService
{
    /**
     * @var ObjectManager
     */
    private ObjectManager $objectManager;

    /**
     * @var ResourceRepository
     */
    private ResourceRepository $repository;

    /**
     * @var Theme|Template
     */
    private $entity;

    /**
     * @var string
     */
    private string $path;

    /**
     * ResourceService constructor.
     * @param ObjectManager $objectManager
     * @param ResourceRepository $repository
     * @param Resource $entity
     * @param string $path
     * @throws RuntimeException
     */
    public function __construct(ObjectManager $objectManager, ResourceRepository $repository, Resource $entity, string $path) {
        $this->path = $path;
        $this->entity = $entity;
        $this->repository = $repository;
        $this->objectManager = $objectManager;
    }

    /**
     *
     * @throws RuntimeException
     */
    public function check(): void
    {
        if (file_exists($this->path) === false) {
            throw new RuntimeException('Cannot find directory.');
        }

        if (is_readable($this->path) === false) {
            throw new RuntimeException('Cannot read directory.');
        }

        $resources = scandir($this->path, 0);
        unset($resources[0], $resources[1]);

        if (count($resources) === 0) {
            throw new RuntimeException('Directory is empty.');
        }

        foreach ($resources as $fileName) {
            // Получить сущность по имени файла
            $entity = $this->repository->getByFile($fileName);

            // Если сущность существует перейти к следующей
            if ($entity !== null) {
                continue;
            }

            $entity = clone $this->entity
                ->setName($fileName)
                ->setFile($fileName);

            $this->objectManager->persist($entity);
        }

        // Получить все сущности файлов которых нет в директории и отметить их удаленными
        $notExistingInDir = $this->repository->getEntitiesNotExistenceInDir($resources);

        foreach ($notExistingInDir as $entity) {
            /* @var $entity Theme|Template */
            $entity->setDeleted(true);

            $this->objectManager->persist($entity);
        }

        $this->objectManager->flush();
    }
}

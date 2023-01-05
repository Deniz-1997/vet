<?php

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Trait AutowiredRepositoryTrait.
 */
trait AutowiredRepositoryTrait
{
    /**
     * UserRepository constructor.
     *
     * @param EntityManagerInterface $registry
     */
    public function __construct(EntityManagerInterface $registry)
    {
        parent::__construct($registry, $registry->getClassMetadata(self::$entityClass));
    }
}

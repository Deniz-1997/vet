<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

abstract class ResourceRepository extends EntityRepository
{
    /**
     * @param string $file
     *
     * @return mixed
     */
    abstract public function getByFile(string $file);

    /**
     * @param array $files
     *
     * @return array
     */
    public function getEntitiesNotExistenceInDir(array $files): ?array
    {
        return $this->createQueryBuilder('email_theme')
            ->where('email_theme.file NOT IN (:files)')
            ->setParameter('files', $files)
            ->getQuery()
            ->getResult();
    }
}

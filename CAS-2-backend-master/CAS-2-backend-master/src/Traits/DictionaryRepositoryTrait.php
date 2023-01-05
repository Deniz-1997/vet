<?php

namespace App\Traits;


trait DictionaryRepositoryTrait
{
    public function findOneByName(string $value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere("upper(r.name) = upper('{$value}')")
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

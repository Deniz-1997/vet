<?php

namespace App\Traits;

/**
 * Trait IsolationEntityManagerTrait.
 */
trait IsolatedEntityManagerTrait
{
    /**
     * @param object $entity
     */
    private function flush(object $entity)
    {
        $this->_em->persist($entity);
        $this->_em->flush();
    }

    /**
     * @param object $entity
     */
    public function save(object $entity)
    {
        $this->flush($entity);
    }

    /**
     * @param object $entity
     */
    public function update(object $entity)
    {
        $this->flush($entity);
    }

    /**
     * @param $entity
     */
    public function remove($entity)
    {
        $this->_em->remove($entity);
        $this->_em->flush();
    }

    /**
     * @return mixed
     */
    public function removeAll()
    {
        return $this->createQueryBuilder('g')->delete()->getQuery()->execute();
    }
}

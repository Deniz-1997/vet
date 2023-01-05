<?php

namespace App\Repository\Security;

use App\Entity\Security\Group;
use App\Repository\AutowiredRepositoryTrait;
use App\Traits\IsolatedEntityManagerTrait;

/**
 * GroupRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GroupRepository extends \Doctrine\ORM\EntityRepository
{
    use IsolatedEntityManagerTrait;
    use AutowiredRepositoryTrait;

    protected static $entityClass = Group::class;
}

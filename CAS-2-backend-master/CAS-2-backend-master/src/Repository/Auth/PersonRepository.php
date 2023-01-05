<?php

namespace App\Repository\Auth;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\EntityOld\Auth\Person;

/**
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    /**
     * @throws \Exception
     */
    public function loadUserByUsername($username)
    {
        $user = $this->createQueryBuilder('u')
            ->where('lower(u.login) = lower(:username)')
            ->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult();

        if ($user === null) {
            $message = sprintf('Unable to find an active admin AppBundle:User object identified by "%s".', $username);
            throw new \Exception($message);
        }

        return $user;
    }

    /**
     */
    public function supportsClass($class): bool
    {
        return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
    }
}

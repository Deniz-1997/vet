<?php

namespace App\Repository\Shop;

use App\Entity\Shop\ShopOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use App\Entity\Shop\ShopProductItem;

/**
 * @method ShopOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShopOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShopOrder[]    findAll()
 * @method ShopOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShopOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShopOrder::class);
    }

     /**
     * @param \DateTime $from
     * @param \DateTime $to
     * @return mixed
     */
    public function findBetweenDates(\DateTime $from, \DateTime $to)
    {
        return $this->createQueryBuilder('s')
            ->leftJoin(ShopProductItem::class, 'pi', Join::WITH, 'pi.shopOrder = s.id')
            ->andWhere('pi.shopOrder IS NOT NULL')
            ->andWhere('s.date >= :from')
            ->andWhere('s.date <= :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->getQuery()
            ->getResult();
    }
}

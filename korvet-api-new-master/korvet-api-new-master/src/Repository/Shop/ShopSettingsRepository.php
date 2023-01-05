<?php

namespace App\Repository\Shop;

use App\Entity\Shop\ShopSettings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShopSettings|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShopSettings|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShopSettings[]    findAll()
 * @method ShopSettings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShopSettingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShopSettings::class);
    }
}

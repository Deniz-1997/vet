<?php

namespace App\Repository\Laboratory;

use App\Entity\Laboratory\ResearchDocument;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method ResearchDocument|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResearchDocument|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResearchDocument[]    findAll()
 * @method ResearchDocument[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResearchDocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResearchDocument::class);
    }
}

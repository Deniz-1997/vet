<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\Reference\Action;
use App\Packages\Fetcher\EntityFetcher;

class CalculateCountItemsCommand extends Command
{
    protected static $defaultName = 'webslon:cms:calculate-count-items';

    /** @var EntityFetcher */
    private $entityFetcher;

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * CalculateCountItemsCommand constructor.
     * @param EntityFetcher $entityFetcher
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityFetcher $entityFetcher, EntityManagerInterface $entityManager)
    {
        $this->entityFetcher = $entityFetcher;
        $this->entityManager = $entityManager;

        parent::__construct(self::$defaultName);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->entityFetcher->getEntities() as $entity) {
            $query = $this->entityManager->createQueryBuilder('e')
                ->from($entity->getClassName(), 'e')
                ->select('COUNT(e) as cnt');

            if (property_exists($entity->getClassName(), 'deleted')) {
                $query->andWhere('e.deleted = false');
            }

            $count = $query->getQuery()->getSingleScalarResult();

            $output->writeln(sprintf('Entity %s count %d', $entity->getClassName(), $count));


            $qb = $this->entityManager->createQueryBuilder('a');
            $qb
                ->update(Action::class, 'a')
                ->set('a.itemsCount', $count)
                ->where('a.itemsCountEnabled = true')
                ->andWhere('a.entityClass.className = '.$qb->expr()->literal($entity->getClassName()))
                ->getQuery()
                ->execute();
        }
        return Command::SUCCESS;
    }
}

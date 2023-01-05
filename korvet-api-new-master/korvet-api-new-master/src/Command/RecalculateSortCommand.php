<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RecalculateSortCommand extends Command
{
    protected static $defaultName = 'app:recalculate-sort-fields';

    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    /**
     * RecalculateSortCommand constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Recalculate sort fields');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Start sorting pet types');
        $this->entityManager->createQuery('UPDATE App\Entity\Reference\PetType p SET p.sort = 999')->execute();
        $data = $this->entityManager->createQuery('
            SELECT t.id, COUNT(t) as cnt FROM App\Entity\Pet\Pet p
                INNER JOIN p.breed b
                INNER JOIN b.type t
            WHERE p.deleted = false AND t.deleted = false
            GROUP BY t.id
            ORDER BY cnt DESC
        ')->setMaxResults(10)->getResult();

        $ids = array_map(function($item) { return $item['id']; }, $data);
        $sort = 100;
        foreach ($ids as $index => $id) {
            $sort += 10;
            $this->entityManager
                ->createQuery('UPDATE App\Entity\Reference\PetType p SET p.sort = :sort WHERE p.id = :id')
                ->setParameter('sort', $sort)
                ->setParameter('id', $id)
                ->execute();
        }
        $output->writeln('Done sorting pet types');

        $output->writeln('Start sorting pet breeds');
        $this->entityManager->createQuery('UPDATE App\Entity\Reference\Breed p SET p.sort = 999')->execute();
        $data = $this->entityManager->createQuery('
            SELECT b.id, COUNT(b) as cnt FROM App\Entity\Pet\Pet p
                INNER JOIN p.breed b
                INNER JOIN b.type t
            WHERE p.deleted = false AND b.deleted = false
            GROUP BY b.id
            ORDER BY cnt DESC
        ')->setMaxResults(10)->getResult();

        $ids = array_map(function($item) { return $item['id']; }, $data);
        $sort = 100;
        foreach ($ids as $index => $id) {
            $this->entityManager
                ->createQuery('UPDATE App\Entity\Reference\Breed p SET p.sort = :sort WHERE p.id = :id')
                ->setParameter('sort', $sort)
                ->setParameter('id', $id)
                ->execute();
            $sort += 10;
        }
        $output->writeln('Done sorting pet breeds');

        $output->writeln('Start sorting owner activity');
        $this->entityManager->createQuery('UPDATE App\Entity\Reference\Owner\Activity a SET a.sort = 999');
        $data = $this->entityManager->createQuery('
            SELECT a.id, COUNT(a) as cnt FROM App\Entity\Owner o
                INNER JOIN o.activities a
            WHERE a.deleted = false AND o.deleted = false
            GROUP BY a.id
            ORDER BY cnt DESC
        ')->setMaxResults(10)->getResult();

        $ids = array_map(function($item) { return $item['id']; }, $data);
        $sort = 100;
        foreach ($ids as $index => $id) {
            $this->entityManager
                ->createQuery('UPDATE App\Entity\Reference\Owner\Activity a SET a.sort = :sort WHERE a.id = :id')
                ->setParameter('sort', $sort)
                ->setParameter('id', $id)
                ->execute();
            $sort += 10;
        }
        $output->writeln('Done sorting owner activity');

        $output->writeln('Success');
        return Command::SUCCESS;
    }
}

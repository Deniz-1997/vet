<?php

namespace App\Command;

use App\Packages\Client\OAuthClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Reference\Subdivision;
use App\Packages\DBAL\Types\LegalFormsEnum;
use DateTime;
use App\Entity\Reference\SupervisedObjects;
use App\Entity\Reference\Station;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Exception;
use \Doctrine\DBAL\DBALException;

class LoadSubdivisionFromCAS extends Command
{
    use OutputStringCommandTrait;
    
    private bool $replace = false;
    protected static $defaultName = 'app:subdivision-entity-load';

    private EntityManagerInterface $casEntityManager;
    private EntityManagerInterface $defaultEntityManager;

    public function __construct(EntityManagerInterface $casEntityManager, EntityManagerInterface $defaultEntityManager)
    {
        $this->casEntityManager = $casEntityManager;
        $this->defaultEntityManager = $defaultEntityManager;
        parent::__construct(self::$defaultName);
    }

    protected function configure()
    {
        $this->setDescription('Load subdivision entity from CAS')
            ->addArgument('replace', InputArgument::OPTIONAL, 'Replace existed data?');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws DBALException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->replace = $input->getArgument('replace') == null ? false : true;

        if (!$output instanceof ConsoleOutputInterface) {
            throw new \LogicException('Эта команда принимает только экземпляр "ConsoleOutputInterface".');
        }

        $conn = $this->casEntityManager->getConnection();

        $requestCountEntities = 'select count(*) from structure.subdivision';
        $stmt = $conn->prepare($requestCountEntities);
        $total = $stmt->executeQuery()->fetchNumeric()[0];

        $requestEntities = 'select * from structure.subdivision';
        $stmt = $conn->prepare($requestEntities);
        $entities = $stmt->executeQuery()->fetchAllAssociative();

        $currentIteration = 1;
        $outputSection = $output->section();
        foreach ($entities as $subdivision) {
            $outputSection->overwrite($this->GetOutputString($total, $currentIteration));
            $this->AddSubdivision($subdivision);
            $currentIteration++;
        }
    }

    private function AddSubdivision($subdivision)
    {
        /** @var Subdivision*/
        $entity = $this->defaultEntityManager->getRepository(Subdivision::class)->findOneBy(['externalId' => $subdivision['id']]);
        if ($entity == null || $this->replace) {
            if ($entity == null) {
                $entity = new Subdivision();
            }
            $entity->setExternalId($subdivision['id']);
            $entity->setName($subdivision['name']);
            $entity->setIsPrivate($subdivision['is_private']);
            $entity->setDeleted(false);
            $station = $this->GetStation($subdivision['station']);
            if ($station == null) {
                return null;
            }
            $entity->setStation($station);
            try {
                $this->defaultEntityManager->persist($entity);
                $this->defaultEntityManager->flush();
            } catch (Exception $exception) {
                var_dump($subdivision);
                throw $exception;
            }
        }
        return $entity;
    }

    private function GetStation(string $stationId): ?Station
    {
        $station = $this->defaultEntityManager->getRepository(Station::class)->findOneBy(["externalId" => $stationId]);
        if ($station == null) {
            $casStationRequest = "select * from contractors.supervisory_authorities where id = '" . $stationId . "'";
            $conn = $this->casEntityManager->getConnection();
            $stmt = $conn->prepare($casStationRequest);
            $casStations = $stmt->executeQuery()->fetchAllAssociative();
            foreach ($casStations as $casStation) {
                /** @var Station*/
                $station = $this->defaultEntityManager->getRepository(Station::class)->findOneBy(['name' => $casStation['name']]);
                if ($station != null) {
                    $station->setExternalId($casStation['id']);
                    $this->defaultEntityManager->flush();
                }
                else {
                    $station = new Station();
                    $station->setName($casStation['name']);
                    $station->setExternalId($casStation['id']);
                    $station->setDeleted(false);
                    if (isset($casStation['parent_id'])) {
                        $parentStation = $this->GetStation($casStation['parent_id']);
                        if (isset($parentStation)) {
                            $station->setParent($parentStation);
                        }
                    }
                    $this->defaultEntityManager->persist($station);
                    $this->defaultEntityManager->flush();
                }
            }
        }
        return $station;
    }
}

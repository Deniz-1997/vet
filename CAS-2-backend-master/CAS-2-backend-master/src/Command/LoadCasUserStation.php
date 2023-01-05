<?php

namespace App\Command;

use App\Entity\Reference\Station;
use App\Service\CAS\GetStationService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User\User;
use Symfony\Component\Console\Output\ConsoleOutputInterface;


class LoadCasUserStation extends Command
{
    use OutputStringCommandTrait;

    protected static $defaultName = 'app:cas-user-station-load';

    private EntityManagerInterface $casEntityManager;
    private EntityManagerInterface $defaultEntityManager;
    private GetStationService $getStationService;

    public function __construct(EntityManagerInterface $casEntityManager,
                                EntityManagerInterface $defaultEntityManager,
                                GetStationService $getStationService)
    {
        $this->casEntityManager = $casEntityManager;
        $this->defaultEntityManager = $defaultEntityManager;
        $this->getStationService = $getStationService;
        parent::__construct(self::$defaultName);
    }

    protected function configure()
    {
        $this->setDescription('Load station  from CAS user')
            ->addArgument('replace', InputArgument::OPTIONAL, 'Replace existed data?');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$output instanceof ConsoleOutputInterface) {
            throw new \LogicException('Эта команда принимает только экземпляр "ConsoleOutputInterface".');
        }
        $conn = $this->defaultEntityManager->getConnection();
        $requestUserLk = 'select * from public.users';
        $stmt = $conn->prepare($requestUserLk);
        $users = $stmt->executeQuery()->fetchAllAssociative();
        $currentIteration = 1;
        $outputSection = $output->section();
        foreach ($users as $user) {
            $outputSection->overwrite($this->GetOutputString(count($users), $currentIteration));
            if ($user['external_id'] !== null && !is_numeric($user['external_id'])) {
                $conn = $this->casEntityManager->getConnection();
                $requestFosUser = "select person_id from auth.fos_user where id = '" . $user['external_id'] . "'";
                $stmt = $conn->prepare($requestFosUser);
                $usersFos = $stmt->executeQuery()->fetchAllAssociative();
                if (count($usersFos) > 0) {
                    $station = $this->getStationService->getUserStationById($usersFos[0]['person_id']);
                    if ($station !== null) {
                        /** @var User $userLk */
                        $userLk = $this->defaultEntityManager->getRepository(User::class)->findOneBy(['externalId' => $user['external_id']]);
                        foreach ($this->getStationService->getChildStation($station->getId()) as $stationChild) {
                            /** @var Station*/
                            $stationChildObject = $this->defaultEntityManager->getRepository(Station::class)->findOneBy(['id' => $stationChild['id']]);
                            $userLk->addStation($stationChildObject);
                        }
                        $userLk->addStation($station);
                        $this->defaultEntityManager->flush();
                    }
                }
            }
            $currentIteration++;
        }
    }

}

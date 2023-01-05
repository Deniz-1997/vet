<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use App\Service\CAS\LoadVaccinationService;
use Exception;
use App\Service\CasApi\CasCheckVaccinationStatusService;
use Symfony\Component\Console\Input\InputArgument;

class LoadVaccinationFromCAS extends Command
{
    use OutputStringCommandTrait;
    
    protected static $defaultName = 'app:import-vaccination';
    private int $days = 30;

    private EntityManagerInterface $casEntityManager;
    private EntityManagerInterface $defaultEntityManager;
    private LoadVaccinationService $loadVaccinationService;
    private CasCheckVaccinationStatusService $casCheckVaccinationStatusService;

    public function __construct(EntityManagerInterface $casEntityManager, EntityManagerInterface $defaultEntityManager, 
                                LoadVaccinationService $loadVaccinationService, CasCheckVaccinationStatusService $casCheckVaccinationStatusService)
    {
        $this->casEntityManager = $casEntityManager;
        $this->defaultEntityManager = $defaultEntityManager;
        $this->loadVaccinationService = $loadVaccinationService;
        $this->casCheckVaccinationStatusService = $casCheckVaccinationStatusService;
        parent::__construct(self::$defaultName);
    }

    protected function configure()
    {
        $this->setDescription('Load vaccination from CAS')
        ->addArgument('days', InputArgument::OPTIONAL, 'Количество дней');;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws DBALException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->days = $input->getArgument('days') == null ? $this->days : $input->getArgument('days');

        if (!$output instanceof ConsoleOutputInterface) {
            throw new \LogicException('Эта команда принимает только экземпляр "ConsoleOutputInterface".');
        }

        $this->casCheckVaccinationStatusService->checkData();

        $conn = $this->defaultEntityManager->getConnection();

        $requestExistedVaccinations = "SELECT external_id FROM reference.vaccination where created_at >= (select NOW() - interval '{$this->days} day') and doctor IS NOT NULL";
        $stmt = $conn->prepare($requestExistedVaccinations);
        $existedVaccinations = $stmt->executeQuery()->fetchAllAssociative();

        $ids = '';
        foreach ($existedVaccinations as $item){
            if (isset($item['external_id'])) {
                $ids.= "'{$item['external_id']}',";
            }
        }
        $conn = $this->casEntityManager->getConnection();
        $requestVaccinations = "SELECT id FROM vaccination.vaccination WHERE createdat >= (select NOW() - interval '{$this->days} day')";
        if (strlen($ids)>0) {
            $ids = substr($ids, 0, strlen($ids) - 1);
            $requestVaccinations .= " AND id NOT IN ({$ids})";
        }
        $stmt = $conn->prepare($requestVaccinations);
        $casVaccinations = $stmt->executeQuery()->fetchAllAssociative();

        $currentIteration = 1;
        $outputSection = $output->section();
        $errors=[];
        foreach ($casVaccinations as $vaccination) {
            $outputSection->overwrite($this->GetOutputString(count($casVaccinations), $currentIteration));
            try {
                $this->loadVaccinationService->GetVaccination($vaccination['id']);
            } catch (\Throwable $exception) {
                array_push($errors, $exception->getMessage());
            }
            $currentIteration++;
        }
        foreach($errors as $error){
            $outputSection->writeln($error);
        }
    }
}

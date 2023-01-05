<?php

namespace App\Command;

use Doctrine\DBAL\DBALException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use App\Service\CAS\LoadVaccinationService;

class LoadExcelVaccinationFromCAS extends Command
{
    use OutputStringCommandTrait;
    
    protected static $defaultName = 'app:import-excel-vaccination';

    private EntityManagerInterface $casEntityManager;
    private LoadVaccinationService $loadVaccinationService;

    public function __construct(EntityManagerInterface $casEntityManager, LoadVaccinationService $loadVaccinationService)
    {
        $this->casEntityManager = $casEntityManager;
        $this->loadVaccinationService = $loadVaccinationService;
        parent::__construct(self::$defaultName);
    }

    protected function configure()
    {
        $this->setDescription('Load import vaccination from CAS');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws DBALException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$output instanceof ConsoleOutputInterface) {
            throw new \LogicException('Эта команда принимает только экземпляр "ConsoleOutputInterface".');
        }

        $conn = $this->casEntityManager->getConnection();

        $requestCountExcelFiles = 'select count(*) from import.uploaded_vaccination_excel_file';
        $stmt = $conn->prepare($requestCountExcelFiles);
        $total = $stmt->executeQuery()->fetchNumeric()[0];

        $requestExcelFiles = 'select * from import.uploaded_vaccination_excel_file';
        $stmt = $conn->prepare($requestExcelFiles);
        $excelFiles = $stmt->executeQuery()->fetchAllAssociative();

        $currentIteration = 1;
        $outputSection = $output->section();
        foreach ($excelFiles as $excelFile) {
            $outputSection->overwrite($this->GetOutputString($total, $currentIteration));
            $this->loadVaccinationService->AddExcelFile($excelFile);
            $currentIteration++;
        }
    }
}

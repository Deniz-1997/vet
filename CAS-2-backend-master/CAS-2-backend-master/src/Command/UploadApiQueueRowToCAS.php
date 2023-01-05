<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use App\Entity\ApiData\ApiQueueRow;
use Exception;
use App\Service\CasApi\CasCheckVaccinationStatusService;
use Symfony\Component\Console\Input\InputArgument;
use App\Interfaces\CAS\CasExcelUploadInterface;
use App\Packages\DBAL\Types\ApiQueueStatusEnum;

class UploadApiQueueRowToCAS extends Command
{
    use OutputStringCommandTrait;

    protected static $defaultName = 'app:upload-queue-row';
    private int $days = 30;

    private EntityManagerInterface $casEntityManager;
    private CasExcelUploadInterface $casExcelUploadInterface;

    public function __construct(CasExcelUploadInterface $casExcelUploadInterface, EntityManagerInterface $defaultEntityManager)
    {
        $this->casExcelUploadInterface = $casExcelUploadInterface;
        $this->defaultEntityManager = $defaultEntityManager;
        parent::__construct(self::$defaultName);
    }

    protected function configure()
    {
        $this->setDescription('Upload api queue to CAS');
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

        $rows = $this->defaultEntityManager->getRepository(ApiQueueRow::class)->findBy(['status' => ApiQueueStatusEnum::getItem(ApiQueueStatusEnum::SAVED)]);

        $currentIteration = 1;
        $outputSection = $output->section();
        foreach ($rows as $row) {
            $outputSection->overwrite($this->GetOutputString(count($rows), $currentIteration));
            $this->casExcelUploadInterface->UploadApiQueueRow($row);
            $currentIteration++;
        }
    }
}

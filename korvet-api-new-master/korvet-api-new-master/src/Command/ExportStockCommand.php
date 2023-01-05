<?php

namespace App\Command;

use App\Entity\FtpHistory;
use App\Service\Export\ExportStockService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ExportStockCommand extends Command
{
    protected static $defaultName = 'app:exportStocks';

    /** @var ContainerInterface */
    private ContainerInterface $container;

    /** @var ExportStockService */
    private ExportStockService $exportStockService;

    /** @var EntityManagerInterface */
    private EntityManagerInterface $_entity;

    /**
     * ExportStockCommand constructor.
     * @param ContainerInterface $container
     * @param ExportStockService $exportStockService
     */
    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container, ExportStockService $exportStockService)
    {
        $this->_entity = $entityManager;
        $this->container = $container;
        $this->exportStockService = $exportStockService;

        parent::__construct(self::$defaultName);
    }


    protected function configure()
    {
        $this
            ->addArgument('type', InputArgument::REQUIRED, 'Типа клиники для экспорта. around - круглосуточные клиники. default - не круглосуточные')
            ->setDescription('Export Stocks to 1C FTP');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $type = $input->getArgument('type');

        if ($type !== "around" && $type !== "default") {
            throw new Exception('Некорректный тип');
        }

//        $ftpHistories = $this->_entity->getRepository(FtpHistory::class)->findBy([
//            'operationType' => FtpHistoryTypeEnum::getItem(FtpHistoryTypeEnum::EXPORT)
//        ], ['date' => 'DESC'], 1);
//
//        $last_date = $ftpHistories[0]->getDate();
//
//        $interval = $last_date->diff(new \DateTime());
//
//        $days = $interval->days;
//
//        $output->writeln("Days: $days");
//
//        $ftpHistories_ = [];
//
//        for($i = 0; $i < $days; $i++){
//            $date = $last_date->modify('+1 day');
//            $output->writeln('Get report:' . $date->format('Y-m-d'));
//        }

        $report = [];

        /** @var $ftpHistory FtpHistory */
        foreach ($this->exportStockService->exportStockByType($type, new DateTime()) as $ftpHistory) {
            $report[] = $ftpHistory->jsonSerialize();
        }

        $output->writeln(json_encode($report));
        return Command::SUCCESS;
    }
}

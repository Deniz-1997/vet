<?php

namespace App\Command\Load;

use App\Service\Import\ImportStockService;
use Doctrine\DBAL\Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LoadServiceFileCommand extends Command
{
    protected static $defaultName = 'app:load-service-file';

    /** @var ImportStockService */
    private ImportStockService $importStockService;

    /**
     * ParseNomenclatureFileCommand constructor.
     * @param ImportStockService $importStockService
     */
    public function __construct(ImportStockService $importStockService)
    {
        $this->importStockService = $importStockService;
        parent::__construct(self::$defaultName);
    }

    protected function configure()
    {
        $this
            ->setDescription('Load service file from ftp')
            ->addOption(
                'use-stub',
                null,
                InputOption::VALUE_NONE,
                'Использовать стаб'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Doctrine\DBAL\Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->importStockService->importService($input->getOption('use-stub'));
        return Command::SUCCESS;
    }
}


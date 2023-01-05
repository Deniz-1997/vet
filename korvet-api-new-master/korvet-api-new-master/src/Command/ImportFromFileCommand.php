<?php

namespace App\Command;

use App\Service\Import\ImportStockService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class ImportFromFileCommand
 */
class ImportFromFileCommand extends Command
{
    protected static $defaultName = 'app:import-from-file';

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
        $this->setDescription('Import from local file or ftp')
            ->addArgument('path', InputArgument::OPTIONAL, 'File path');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Doctrine\DBAL\Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath = $input->getArgument('path');
        $count = $this->importStockService->universalImport($filePath);
        $output->writeln('Count add items: '. $count);
        return Command::SUCCESS;
    }
}

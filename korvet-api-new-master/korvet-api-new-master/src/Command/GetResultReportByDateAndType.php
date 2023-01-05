<?php


namespace App\Command;


use App\Entity\ProductStock;
use App\Entity\Reference\Product;
use App\Service\ReportsTypeFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Exception\ApiException;

class GetResultReportByDateAndType extends Command
{
    protected static $defaultName = 'app:get-result-report';

    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    /**
     * UpdateGroupRolesCommand constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct(self::$defaultName);
    }

    protected function configure()
    {
        $this
            ->addArgument('type', InputArgument::REQUIRED, 'Тип отчета')
            ->addArgument('date', InputArgument::REQUIRED, 'Дата отчета')
            ->addArgument('ids', InputArgument::OPTIONAL, 'ID склада')
            ->setDescription('Calculate summary product history balance and compare with product stock quantity');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $type = $input->getArgument('type');

        $data = [
            'debug' =>  true,
            'date' => $input->getArgument('date')
        ];

        $ids = $input->getArgument('ids');

        if(!empty($ids)){
            if($type === 'revenueReport'){
                $data['unitIds'] = $ids;
            } else {
                $data['stockIds'] = $ids;
            }
        }

        $report = ReportsTypeFactory::generateReport($type);
        $result = $report->getData($data, $this->entityManager);

        switch ($type){
            case 'revenueReport':
                $sql = "select id, name from reference.reference_stock where not deleted";
                $data = $this->entityManager->getConnection()->fetchAllAssociative($sql);
                $stocks = array_combine(array_column($data, 'id'), array_column($data, 'name'));
                $stocks[0] = 'Услуги';

                $stocksData = [];
                foreach ($result as $item) {
                    $stockId = $item['stock_id'];
                    if (!array_key_exists($stockId, $stocksData)) {
                        $stocksData[$stockId] = [
                            'name' => $stocks[$stockId],
                            'totalCash' => 0,
                            'totalElectronically' => 0
                        ];
                    }

                    if (strcasecmp($item['payment_type'], 'CASH') == 0) {
                        $stocksData[$stockId]['totalCash'] = $item['total_sum'] + (float)$stocksData[$stockId]['totalCash'];
                    } elseif (strcasecmp($item['payment_type'], 'ELECTRONICALLY') == 0) {
                        $stocksData[$stockId]['totalElectronically'] = $item['total_sum'] + (float)$stocksData[$stockId]['totalElectronically'];
                    }
                }
                dd($result, $stocksData);
        }

        return Command::SUCCESS;
    }
}

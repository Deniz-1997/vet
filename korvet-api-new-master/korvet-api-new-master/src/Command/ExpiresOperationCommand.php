<?php

namespace App\Command;


use App\Entity\Cash\CashReceipt;
use App\Repository\Cash\CashReceiptRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Packages\DBAL\Types\FiscalReceiptStateEnum;

class ExpiresOperationCommand extends Command
{
    protected static $defaultName = 'webslon:cashier-equipment:handle-expires-operations';

    /** @var CashReceiptRepository */
    private CashReceiptRepository $cashReceiptRepository;

    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    /** @var integer */
    private int $expiresIn;

    /**
     * ExpiresOperationCommand constructor.
     * @param CashReceiptRepository $cashReceiptRepository
     * @param EntityManagerInterface $entityManager
     * @param int $expiresIn
     */
    public function __construct(CashReceiptRepository $cashReceiptRepository, EntityManagerInterface $entityManager, int $expiresIn)
    {
        $this->cashReceiptRepository = $cashReceiptRepository;
        $this->entityManager = $entityManager;
        $this->expiresIn = $expiresIn;

        parent::__construct(self::$defaultName);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $cashReceipts = $this->cashReceiptRepository->createQueryBuilder('c')
            ->where('c.fiscal.state = :state')
            ->setParameter('state', 'PRINTING')
            ->getQuery()
            ->getResult();

        /** @var CashReceipt $cashReceipt */
        foreach ($cashReceipts as $cashReceipt) {
            if (!$cashReceipt->getStartPrintAt()) {
                continue;
            }

            $diff = (new \DateTime())->diff($cashReceipt->getStartPrintAt());
            $minutes = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;

            if ($minutes >= $this->expiresIn) {
                $fiscal = $cashReceipt->getFiscal();
                $fiscal->setState(FiscalReceiptStateEnum::getItem(FiscalReceiptStateEnum::ERROR));
                $cashReceipt->setFiscal($fiscal);

                $this->entityManager->persist($cashReceipt);
                $this->entityManager->flush();
            }
        }
        return Command::SUCCESS;
    }
}

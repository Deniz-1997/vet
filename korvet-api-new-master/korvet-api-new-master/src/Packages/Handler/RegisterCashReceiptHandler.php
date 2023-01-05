<?php

namespace App\Packages\Handler;

use App\Interfaces\HandlerInterface;
use App\Repository\Cash\CashReceiptRepository;
use DateTime;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Packages\DBAL\Types\FiscalReceiptStateEnum;
use App\Entity\Embeddable\FiscalParameters;
use App\Packages\Event\RegisterCashReceiptEvent;
use App\Repository\ShiftRepository;

class RegisterCashReceiptHandler implements HandlerInterface
{
    /** @var CashReceiptRepository */
    private CashReceiptRepository $cashReceiptRepository;

    /** @var ShiftRepository */
    private ShiftRepository $shiftRepository;

    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    /** @var LoggerInterface */
    private LoggerInterface $logger;

    /** @var EventDispatcher */
    private $eventDispatcher;

    /**
     * RegisterCashReceiptHandler constructor.
     * @param CashReceiptRepository $cashReceiptRepository
     * @param ShiftRepository $shiftRepository
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(CashReceiptRepository $cashReceiptRepository, ShiftRepository $shiftRepository, EntityManagerInterface $entityManager, LoggerInterface $logger, EventDispatcherInterface $eventDispatcher)
    {
        $this->cashReceiptRepository = $cashReceiptRepository;
        $this->shiftRepository = $shiftRepository;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param string $type
     * @param array $data
     * @return bool
     */
    public function support(string $type, array $data): bool
    {
        return in_array($type, ['buyReturn', 'sellReturn', 'buy', 'sellReturnCorrection', 'buyCorrection', 'sellCorrection', 'buyReturnCorrection', 'sell', 'registerCashReceipt']);
    }
    
    /**
     * @param string $type
     * @param array  $data
     * @throws DBALException
     */
    public function handle(string $type, array $data)
    {
        $cashReceiptId = $data['cashReceiptId'];
        $cashReceipt = $this->cashReceiptRepository->find($cashReceiptId);
        if (!$cashReceipt) {
            throw new RuntimeException(sprintf('Cash receipt %s not found', $cashReceiptId));
        }

        $fiscalParameters = $cashReceipt->getFiscal() ?? new FiscalParameters();
        //Костыль, ошибка при отработке на событие registerCashReceipt
        if ('registerCashReceipt' !== $type) {
            $fiscalParameters->setFiscalDocumentDateTime(new DateTime($data['fiscalParams']['fiscalDocumentDateTime']));
            $fiscalParameters->setFiscalDocumentNumber($data['fiscalParams']['fiscalDocumentNumber']);
            $fiscalParameters->setFiscalDocumentSign($data['fiscalParams']['fiscalDocumentSign']);
            $fiscalParameters->setFnNumber($data['fiscalParams']['fnNumber']);
            $fiscalParameters->setFnsUrl($data['fiscalParams']['fnsUrl']);
            $fiscalParameters->setRegistrationNumber($data['fiscalParams']['registrationNumber']);
            $fiscalParameters->setShiftNumber($data['fiscalParams']['shiftNumber']);
        }
        $fiscalParameters->setState(FiscalReceiptStateEnum::getItem(FiscalReceiptStateEnum::DONE));

        $shift = $this->shiftRepository->findOneBy([
            'cashRegister' => $cashReceipt->getCashRegister(),
            'fiscalOpen.shiftNumber' => $data['fiscalParams']['shiftNumber']
        ], ['id' => 'DESC']);
        $cashReceipt->setShift($shift);
        $cashReceipt->setFiscal($fiscalParameters);

        $this->entityManager->persist($cashReceipt);
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(new RegisterCashReceiptEvent($cashReceipt), RegisterCashReceiptEvent::NAME);
    }

    /**
     * @param string $type
     * @param array $errors
     * @throws DBALException
     */
    public function handleErrors(string $type, array $errors)
    {
        $cashReceiptId = $errors['cashReceiptId'] ?? 'unknown';

        $cashReceipt = $this->cashReceiptRepository->find($cashReceiptId);
        if (!$cashReceipt) {
            throw new RuntimeException(sprintf('Cash receipt %s not found', $cashReceiptId));
        }

        $formattedErrors = json_encode($errors['errors'], true);
        $this->logger->error(sprintf('Cash receipt %s errors: %s', $cashReceiptId, $formattedErrors));

        $fiscalParameters = $cashReceipt->getFiscal() ?? new FiscalParameters();
        $fiscalParameters->setState(FiscalReceiptStateEnum::getItem(FiscalReceiptStateEnum::NEW));

        $cashReceipt->setFiscal($fiscalParameters);

        $this->entityManager->persist($cashReceipt);
        $this->entityManager->flush();
    }
}

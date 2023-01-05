<?php

namespace App\Packages\Handler;

use App\Packages\DBAL\Types\FiscalReceiptStateEnum;
use App\Entity\Cash\CashFlow;
use App\Entity\Embeddable\FiscalParameters;
use App\Packages\Event\RegisterCashFlowEvent;
use App\Exception\ErrorResponseException;
use App\Interfaces\HandlerInterface;
use App\Repository\Cash\CashFlowRepository;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * TODO Отрефакторить класс. Как минимум переименовать в RegisterCashFlowHandler чтобы верно описывал назначение.
 *
 * Class CashInHandler
 */
class CashInHandler implements HandlerInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var CashFlowRepository */
    private $cashFlowRepository;

    /** @var LoggerInterface */
    private $logger;

    /** @var EventDispatcher */
    private $eventDispatcher;

    /**
     * CashInHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param CashFlowRepository $cashFlowRepository
     * @param LoggerInterface $logger
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EntityManagerInterface $entityManager, CashFlowRepository $cashFlowRepository, LoggerInterface $logger, EventDispatcherInterface $eventDispatcher)
    {
        $this->entityManager = $entityManager;
        $this->cashFlowRepository = $cashFlowRepository;
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
        return in_array($type, ['cashIn', 'cashOut']);
    }

    /**
     * @param string $type
     * @param array $data
     * @throws DBALException
     * @throws ErrorResponseException
     */
    public function handle(string $type, array $data)
    {
        if (isset($data['warnings']['notPrinted']) && $data['warnings']['notPrinted']) {
            throw new ErrorResponseException('CASHIER_NOT_PRINTED_ERROR', 'cashier.equipment.notPrinted');
        }

        $cashFlowId = $data['cashReceiptId'] ?? null;

        /** @var CashFlow|null $cashFlow */
        $cashFlow = $this->cashFlowRepository->findCashFlow($cashFlowId);
        if (!$cashFlow) {
            throw new RuntimeException(sprintf('Cash flow %d not found', $cashFlowId));
        }

        $fiscal = $cashFlow->getFiscal() ?? new FiscalParameters();
//        $fiscal->setFiscalDocumentDateTime(new \DateTime($data['fiscalParams']['fiscalDocumentDateTime']));
//        $fiscal->setFiscalDocumentNumber($data['fiscalParams']['fiscalDocumentNumber']);
//        $fiscal->setFiscalDocumentSign($data['fiscalParams']['fiscalDocumentSign']);
//        $fiscal->setFnNumber($data['fiscalParams']['fnNumber']);
//        $fiscal->setFnsUrl($data['fiscalParams']['fnsUrl']);
//        $fiscal->setRegistrationNumber($data['fiscalParams']['registrationNumber']);
//        $fiscal->setShiftNumber($data['fiscalParams']['shiftNumber']);
        $fiscal->setState(FiscalReceiptStateEnum::getItem(FiscalReceiptStateEnum::DONE));
        
        $cashFlow->setFiscal($fiscal);

        $this->entityManager->persist($cashFlow);
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch( new RegisterCashFlowEvent($cashFlow), RegisterCashFlowEvent::NAME);
    }

    /**
     * @param string $type
     * @param array $errors
     * @throws DBALException
     */
    public function handleErrors(string $type, array $errors)
    {
        $cashFlowId = $data['cashFlowId'];

        /** @var CashFlow|null $cashFlow */
        $cashFlow = $this->cashFlowRepository->findCashFlow($cashFlowId);
        if (!$cashFlow) {
            throw new RuntimeException(sprintf('Cash flow %d not found', $cashFlowId));
        }

        $formattedErrors = json_encode($data['errors'], true);
        $this->logger->error(sprintf('Cash flow %s errors: %s', $cashFlowId, $formattedErrors));

        $fiscalParameters = $cashFlow->getFiscal() ?? new FiscalParameters();
        $fiscalParameters->setState(FiscalReceiptStateEnum::getItem(FiscalReceiptStateEnum::NEW));

        $cashFlow->setFiscal($fiscalParameters);

        $this->entityManager->persist($cashFlow);
        $this->entityManager->flush();
    }
}

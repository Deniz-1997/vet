<?php

namespace App\Packages\Handler;

use App\Interfaces\HandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Repository\Reference\CashRegisterRepository;
use App\Entity\Reference\CashRegister;
use App\Packages\Event\ReportExchangeStatusEvent;

/**
 * Class ReportExchangeStatusHandler
 */
class ReportExchangeStatusHandler implements HandlerInterface
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    /** @var LoggerInterface */
    private LoggerInterface $logger;

    /** @var EventDispatcher */
    private $eventDispatcher;

    /** @var CashRegisterRepository */
    private CashRegisterRepository $cashRegisterRepository;

    /**
     * ReportExchangeStatusHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     * @param EventDispatcherInterface $eventDispatcher
     * @param CashRegisterRepository $cashRegisterRepository
     */
    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger, EventDispatcherInterface $eventDispatcher, CashRegisterRepository $cashRegisterRepository)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->eventDispatcher = $eventDispatcher;
        $this->cashRegisterRepository = $cashRegisterRepository;
    }

    /**
     * @param string $type
     * @param array $data
     * @return bool
     */
    public function support(string $type, array $data): bool
    {
        return $type === 'reportOfdExchangeStatus';
    }

    /**
     * @param string $type
     * @param array $data
     */
    public function handle(string $type, array $data)
    {
        $cashRegisterId = $data['cashRegisterId'] ?? null;

        /** @var CashRegister|null $cashRegister */
        $cashRegister = $this->cashRegisterRepository->findCashRegister($cashRegisterId);
        if (!$cashRegister) {
            throw new RuntimeException(sprintf('Cash register %s not found', $cashRegisterId));
        }

        $this->eventDispatcher->dispatch( new ReportExchangeStatusEvent($cashRegister), ReportExchangeStatusEvent::NAME);
    }

    /**
     * @param string $type
     * @param array $errors
     */
    public function handleErrors(string $type, array $errors)
    {
        $cashRegisterId = $errors['cashRegisterId'] ?? null;

        /** @var CashRegister|null $cashRegister */
        $cashRegister = $this->cashRegisterRepository->findCashRegister($cashRegisterId);
        if (!$cashRegister) {
            throw new RuntimeException(sprintf('Cash register %s not found', $cashRegisterId));
        }

        $formattedErrors = json_encode($errors['errors'], true);
        $this->logger->error(sprintf('Cash register %s errors: %s', $cashRegisterId, $formattedErrors));
    }
}

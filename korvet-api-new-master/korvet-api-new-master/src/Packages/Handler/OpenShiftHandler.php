<?php

namespace App\Packages\Handler;

use App\Packages\DBAL\Types\FiscalReceiptStateEnum;
use App\Packages\DBAL\Types\ShiftStateEnum;
use App\Entity\Embeddable\FiscalParameters;
use App\Entity\Reference\CashRegister;
use App\Entity\Shift;
use App\Exception\ErrorResponseException;
use App\Interfaces\HandlerInterface;
use App\Repository\Reference\CashRegisterRepository;
use App\Repository\ShiftRepository;
use DateTime;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class OpenShiftHandler
 */
class OpenShiftHandler implements HandlerInterface
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    /** @var CashRegisterRepository */
    private CashRegisterRepository $cashRegisterRepository;

    /** @var ShiftRepository */
    private ShiftRepository $shiftRepository;

    /**
     * OpenShiftHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param CashRegisterRepository $cashRegisterRepository
     * @param ShiftRepository $shiftRepository
     */
    public function __construct(EntityManagerInterface $entityManager, CashRegisterRepository $cashRegisterRepository, ShiftRepository $shiftRepository)
    {
        $this->entityManager = $entityManager;
        $this->cashRegisterRepository = $cashRegisterRepository;
        $this->shiftRepository = $shiftRepository;
    }

    /**
     * @param string $type
     * @param array $data
     * @return bool
     */
    public function support(string $type, array $data): bool
    {
        return $type === 'openShift';
    }

    /**
     * @param string $type
     * @param array $data
     * @throws ErrorResponseException
     * @throws DBALException
     * @throws \Exception
     */
    public function handle(string $type, array $data)
    {
        if (isset($data['warnings']['notPrinted']) && $data['warnings']['notPrinted']) {
            throw new ErrorResponseException('CASHIER_NOT_PRINTED_ERROR', 'cashier.equipment.notPrinted');
        }

        /** @var CashRegister|null $cashRegister */
        $cashRegister = $this->cashRegisterRepository->find($data['cashRegisterId']);
        if (is_null($cashRegister)) {
            return; // пришло сообщение с несуществующей ККМ
        }
        $lastShift = $this->shiftRepository->findLastShift($cashRegister);
        if ($lastShift && $lastShift->getState()->code === ShiftStateEnum::OPEN) {
            return; //могли несколько раз на кнопку открытия смены нажать
        }

        $fiscalParameters = new FiscalParameters();
        $fiscalParameters->setFiscalDocumentDateTime(new DateTime($data['fiscalParams']['fiscalDocumentDateTime']));
        $fiscalParameters->setFiscalDocumentNumber($data['fiscalParams']['fiscalDocumentNumber']);
        $fiscalParameters->setFiscalDocumentSign($data['fiscalParams']['fiscalDocumentSign']);
        $fiscalParameters->setFnNumber($data['fiscalParams']['fnNumber']);
        $fiscalParameters->setFnsUrl($data['fiscalParams']['fnsUrl']);
        $fiscalParameters->setRegistrationNumber($data['fiscalParams']['registrationNumber']);
        $fiscalParameters->setShiftNumber($data['fiscalParams']['shiftNumber']);
        $fiscalParameters->setState(FiscalReceiptStateEnum::getItem(FiscalReceiptStateEnum::DONE));

        if ($lastShift && $lastShift->getState()->code === ShiftStateEnum::NEW) {
            $shift = $lastShift;
        } else {
            $shift = new Shift();
            if (!is_null($lastShift)) {
                $shift->setCreator($lastShift->getCreator());
            }
        }
        $shift->setFiscalOpen($fiscalParameters);
        $shift->setCreatedAt(new DateTime());
        $shift->setCashRegister($cashRegister);
        $shift->setCashier($shift->getCreator());

        $this->entityManager->persist($shift);
        $this->entityManager->flush();
    }

    public function handleErrors(string $type, array $errors)
    {

    }
}

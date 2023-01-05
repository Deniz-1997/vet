<?php

namespace App\Controller\Reference;

use App\Controller\ApiController;
use App\Packages\DBAL\Types\FiscalReceiptStateEnum;
use App\Entity\Embeddable\FiscalParameters;
use App\Exception\ApiException;
use App\Repository\Cash\CashReceiptRepository;
use App\Repository\Reference\CashRegisterRepository;
use App\Repository\ShiftRepository;
use App\Service\CashierEquipmentMessageService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ResetCashRegisterController extends ApiController
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("api/cash-receipt/{id}/reset/", name="reference_reset_cash_register", methods={"POST"})
     * @param string $id
     * @param ShiftRepository $shiftRepository
     * @param CashRegisterRepository $cashRegisterRepository
     * @param CashReceiptRepository $cashReceiptRepository
     * @param CashierEquipmentMessageService $cashierEquipmentService
     * @return Response
     * @throws ApiException
     * @throws \Doctrine\DBAL\Exception
     */
    public function resetCashRegister(string $id, ShiftRepository $shiftRepository, CashRegisterRepository $cashRegisterRepository,
                                      CashReceiptRepository $cashReceiptRepository, CashierEquipmentMessageService $cashierEquipmentService): Response
    {
        $equipConnection = false;
        try {
            $cashierResult = $cashierEquipmentService->reportOfExchangeStatus($id);
            if (empty($cashierResult)) {
                $equipConnection = false;
            } else {
                $equipConnection = true;
            }
        } catch (\Exception $e) {
            $equipConnection = false;
        }
        /* if ($equipConnection) {
            throw new ApiException('Cмена открыта на кассе, принудительное закрытие в Корвете не возможно');
        } */

        $cashRegister = $cashRegisterRepository->findCashRegister($id);
        if (!$cashRegister) {
            throw new ApiException('cashier.cash_register.not_found', 'CASH_REGISTER_NOT_FOUND', null, 404);
        }

        $openedShift = $shiftRepository->findLastShift($cashRegister);
        $cashReceipts = $cashReceiptRepository->findByShift($openedShift);

        $fiscal = $openedShift->getFiscalClose() ?? new FiscalParameters();
        $fiscal->setState(FiscalReceiptStateEnum::getItem(FiscalReceiptStateEnum::DONE));
        if ($cashReceipts != null && count($cashReceipts) > 0) {
            $fiscal->setReceiptsCount(count($cashReceipts));
        }
        $fiscal->setFiscalDocumentDateTime(new DateTime());
        $openedShift->setFiscalClose($fiscal);

        if (isset($this->entityManager)) {
            $this->entityManager->persist($openedShift);
            $this->entityManager->flush();
        }


        return $this->redirect(sprintf('%s://%s/api/reference/cash-register/%s/shift/',
            $_SERVER["REQUEST_SCHEME"], $_SERVER["HTTP_HOST"], $id));
    }
}

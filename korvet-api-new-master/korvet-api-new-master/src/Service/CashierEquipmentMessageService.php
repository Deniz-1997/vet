<?php

namespace App\Service;

use App\Entity\Cash\CashFlow;
use App\Entity\Cash\CashReceipt;
use App\Interfaces\CashierUserInterface;
use App\Repository\Cash\CashFlowRepository;
use App\Repository\Cash\CashierScheduleRepository;
use App\Repository\Cash\CashReceiptRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Packages\AMQP\Producer;
use App\Packages\DBAL\Types\CashReceiptTypeEnum;
use App\Packages\DBAL\Types\CorrectionTypeEnum;
use App\Packages\DBAL\Types\FiscalReceiptStateEnum;
use App\Packages\DBAL\Types\PaymentMethodEnum;
use App\Packages\DBAL\Types\PaymentObjectEnum;
use App\Packages\DBAL\Types\PaymentTypeEnum;
use App\Packages\DBAL\Types\ReceiptDeliveryTypeEnum;
use App\Packages\DBAL\Types\ShiftStateEnum;
use App\Packages\DBAL\Types\TaxationTypeEnum;
use App\Packages\DTO\CashierEquipment\ClientInfo;
use App\Packages\DTO\CashierEquipment\Command\CloseShiftCommand;
use App\Packages\DTO\CashierEquipment\Command\ContinuePrintCommand;
use App\Packages\DTO\CashierEquipment\Command\OpenShiftCommand;
use App\Packages\DTO\CashierEquipment\Command\Receipts\BaseRegisterCorrectionCommand;
use App\Packages\DTO\CashierEquipment\Command\Receipts\BuyCommand;
use App\Packages\DTO\CashierEquipment\Command\Receipts\BuyCorrectionCommand;
use App\Packages\DTO\CashierEquipment\Command\Receipts\BuyReturnCommand;
use App\Packages\DTO\CashierEquipment\Command\Receipts\SellCommand;
use App\Packages\DTO\CashierEquipment\Command\Receipts\SellCorrectionCommand;
use App\Packages\DTO\CashierEquipment\Command\Receipts\SellReturnCommand;
use App\Packages\DTO\CashierEquipment\Command\Receipts\SellReturnCorrectionCommand;
use App\Packages\DTO\CashierEquipment\Command\Receipts\CashFlowCommand;
use App\Packages\DTO\CashierEquipment\Command\ReportOfExchangeStatusCommand;
use App\Packages\DTO\CashierEquipment\Command\ReportXCommand;
use App\Packages\DTO\CashierEquipment\Command\RegistrationInfoCommand;
use App\Packages\DTO\CashierEquipment\Item;
use App\Packages\DTO\CashierEquipment\NomenclatureCode;
use App\Packages\DTO\CashierEquipment\Operator;
use App\Packages\DTO\CashierEquipment\Payment;
use App\Packages\DTO\CashierEquipment\Tax;
use App\Entity\Embeddable\FiscalParameters;
use App\Entity\ReceiptItem;
use App\Entity\Reference\CashRegister;
use App\Packages\Handler\RegisterCashReceiptHandler;
use App\Repository\Reference\CashRegisterRepository;
use App\Repository\ShiftRepository;
use App\Packages\Security\Voter\CashRegisterVoter;
use App\Exception\ApiException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CashierEquipmentMessageService
{
    /** @var Producer */
    private $producer;

    /** @var string */
    private string $operatorName = '';

    /** @var string */
    private $operatorInn = '';

    /** @var CashRegisterRepository */
    private CashRegisterRepository $cashRegisterRepository;

    /** @var CashReceiptRepository */
    private CashReceiptRepository $cashReceiptRepository;

    /** @var CashFlowRepository */
    private CashFlowRepository $cashFlowRepository;

    /** @var ShiftRepository*/
    private ShiftRepository $shiftRepository;

    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    /** @var UserInterface|null */
    private $currentUser;

    /** @var AuthorizationCheckerInterface */
    private AuthorizationCheckerInterface $authorizationChecker;

    /**@var EventDispatcherInterface */
    private EventDispatcherInterface $eventDispatcher;

    /**@var RegisterCashReceiptHandler $handler*/
    private RegisterCashReceiptHandler $handler;

    /** @var CashierScheduleRepository $cashierScheduleRepository */
    private CashierScheduleRepository $cashierScheduleRepository;

    /**
     * CashierEquipmentMessageService constructor.
     *
     * @param Producer $producer
     * @param TokenStorageInterface $tokenStorage
     * @param CashRegisterRepository $cashRegisterRepository
     * @param CashFlowRepository $cashFlowRepository
     * @param CashReceiptRepository $cashReceiptRepository
     * @param ShiftRepository $shiftRepository
     * @param EntityManagerInterface $entityManager
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param EventDispatcherInterface $eventDispatcher
     * @param RegisterCashReceiptHandler $handler
     */
    public function __construct(
        Producer $producer,
        TokenStorageInterface $tokenStorage,
        CashRegisterRepository $cashRegisterRepository,
        CashFlowRepository $cashFlowRepository,
        CashReceiptRepository $cashReceiptRepository,
        CashierScheduleRepository $cashierScheduleRepository,
        ShiftRepository $shiftRepository,
        EntityManagerInterface $entityManager,
        AuthorizationCheckerInterface $authorizationChecker,
        EventDispatcherInterface $eventDispatcher,
        RegisterCashReceiptHandler $handler
    )
    {
        $this->producer = $producer;
        $token = $tokenStorage->getToken();

        /** @var CashierUserInterface $currentUser */
        $currentUser = $token ? $token->getUser() : null;

        if ($currentUser instanceof CashierUserInterface) {
            $this->operatorName = trim(implode(' ',
                [$currentUser->getSurname(), $currentUser->getName(), $currentUser->getPatronymic()]));

            $this->operatorInn = $currentUser->getInn();
        }

        $this->currentUser = $currentUser;
        $this->cashRegisterRepository = $cashRegisterRepository;
        $this->cashFlowRepository = $cashFlowRepository;
        $this->cashReceiptRepository = $cashReceiptRepository;
        $this->shiftRepository = $shiftRepository;
        $this->entityManager = $entityManager;
        $this->authorizationChecker = $authorizationChecker;
        $this->eventDispatcher = $eventDispatcher;
        $this->handler = $handler;
        $this->cashierScheduleRepository = $cashierScheduleRepository;
    }

    /**
     * @param string $cashRegisterId
     * @return CashRegister
     * @throws ApiException
     */
    private function getCashRegisterById(string $cashRegisterId): CashRegister
    {
        $cashRegister = $this->cashRegisterRepository->findCashRegister($cashRegisterId);
        $this->denyIfNotAllowCashRegister($cashRegister);

        if (is_null($cashRegister)) {
            throw new ApiException("ККМ с id $cashRegisterId не найдена", '404', null, Response::HTTP_NOT_FOUND);
        }
        return $cashRegister;
    }

    /**
     * @param CashReceipt $cashReceipt
     * @return bool
     */
    private function isElectronicallyReceipt(CashReceipt $cashReceipt): bool
    {
        if ($cashReceipt->getDeliveryType() === ReceiptDeliveryTypeEnum::PHONE || $cashReceipt->getDeliveryType() === ReceiptDeliveryTypeEnum::EMAIL) {
            return true;
        }
        return false;
    }

    /**
     * @param CashReceipt $cashReceipt
     * @return string|null
     */
    private function getReceiptTaxationType(CashReceipt $cashReceipt): ?string
    {
        switch ($cashReceipt->getTaxationType()) {
            case TaxationTypeEnum::OSN: // общая
                return 'osn';
            case TaxationTypeEnum::USN_INCOME: // упрощенная (Доход)
                return 'usnIncome';
            case TaxationTypeEnum::USN_OUTCOME: // упрощенная (Доход минус Расход)
                return 'usnIncomeOutcome';
            case TaxationTypeEnum::ENVD: // ЕНВД
                return 'envd';
            case TaxationTypeEnum::ESN: // единый сельскохозяйственный налог
                return 'esn';
            case TaxationTypeEnum::PATENT: // патентная
                return 'patent';
            default:
                return null;
        }
    }

    /**
     * @param CashReceipt $cashReceipt
     * @return string|null
     */
    private function getReceiptPaymentMethod(CashReceipt $cashReceipt): ?string
    {
        switch ($cashReceipt->getPaymentMethod()) {
            case PaymentMethodEnum::FULL_PREPAYMENT: // предоплата 100%
                return 'fullPrepayment';
            case PaymentMethodEnum::PREPAYMENT: // предоплата
                return 'prepayment';
            case PaymentMethodEnum::ADVANCE: // аванс
                return 'advance';
            case PaymentMethodEnum::FULL_PAYMENT: // полный расчет
                return 'fullPayment';
            case PaymentMethodEnum::PARTIAL_PAYMENT: // частичный расчет и кредит
                return 'partialPayment';
            case PaymentMethodEnum::CREDIT: // передача в кредит
                return 'credit';
            case PaymentMethodEnum::CREDIT_PAYMENT: // оплата кредита
                return 'creditPayment';
            default:
                return null;
        }
    }

    /**
     * @param CashReceipt $cashReceipt
     * @return string|null
     */
    private function getReceiptPaymentType(CashReceipt $cashReceipt): ?string
    {
        switch ($cashReceipt->getPaymentType()) {
            case PaymentTypeEnum::CASH: // наличными
                return 'cash';
            case PaymentTypeEnum::ELECTRONICALLY: // безналичными
                return 'electronically';
            case PaymentTypeEnum::PREPAID: // предварительная оплата (аванс)
                return 'prepaid';
            case PaymentTypeEnum::CREDIT: // последующая оплата (кредит)
                return 'credit';
            case PaymentTypeEnum::OTHER: // иная форма оплаты (встречное предоставление)
                return 'other';
            default:
                return null;
        }
    }

    /**
     * @param CashReceipt $cashReceipt
     * @return string|null
     */
    private function getReceiptCorrectionType(CashReceipt $cashReceipt): ?string
    {
        switch ($cashReceipt->getCorrectionType()) {
            case CorrectionTypeEnum::SELF: // самостоятельно
                return 'self';
            case CorrectionTypeEnum::INSTRUCTION: // по предписанию
                return 'instruction';
            default:
                return null;
        }
    }

    /**
     * @param ReceiptItem $receiptItem
     * @return string|null
     */
    private function getReceiptItemPaymentObject(ReceiptItem $receiptItem): ?string
    {
        switch ($receiptItem->getPaymentObject()) {
            case PaymentObjectEnum::COMMODITY: // товар
                return 'commodity';
            case PaymentObjectEnum::EXCISE: // подакцизный товар
                return 'excise';
            case PaymentObjectEnum::JOB: // работа
                return 'job';
            case PaymentObjectEnum::SERVICE: // услуга
                return 'service';
            case PaymentObjectEnum::GAMBLING_BET: // ставка азартной игры
                return 'gamblingBet';
            case PaymentObjectEnum::GAMBLING_PRIZE: // выигрыш азартной игры
                return 'gamblingPrize';
            case PaymentObjectEnum::LOTTERY: // лотерейный билет
                return 'lottery';
            case PaymentObjectEnum::LOTTERY_PRIZE: // выигрыш лотереи
                return 'lotteryPrize';
            case PaymentObjectEnum::INTELLECTUAL_ACTIVITY: // предоставление результатов интерелектуальной деятельности
                return 'intellectualActivity';
            case PaymentObjectEnum::PAYMENT: // платеж
                return 'payment';
            case PaymentObjectEnum::AGENT_COMMISSION: // агентское вознаграждение
                return 'agentCommission';
            case PaymentObjectEnum::PROPRIETARY_LAW: // имущественное право
                return 'proprietaryLaw';
            case PaymentObjectEnum::NON_OPERATING_INCOME: // внереализационный доход
                return 'nonOperatingIncome';
            case PaymentObjectEnum::INSURANCE_CONTRIBUTIONS: // страховые взносы
                return 'insuranceСontributions';
            case PaymentObjectEnum::MERCHANT_TAX: // торговый сбор
                return 'merchantTax';
            case PaymentObjectEnum::RESORT_FEE: // курортный сбор
                return 'resortFee';
            case PaymentObjectEnum::DEPOSIT: // залог
                return 'deposit';
            case PaymentObjectEnum::COMPOSITE: // составной предмет расчета
                return 'composite';
            case PaymentObjectEnum::ANOTHER: // иной предмет расчета
                return 'another';
            default:
                return null;
        }
    }

    /**
     * @param string $receiptType
     * @return string
     * @throws ApiException
     */
    private function getReceiptsCommandClassNameByReceiptType(string $receiptType): string
    {
        switch ($receiptType) {
            case CashReceiptTypeEnum::BUY;
                $className = BuyCommand::class;
                break;
            case CashReceiptTypeEnum::SELL;
                $className = SellCommand::class;
                break;
            case CashReceiptTypeEnum::BUY_RETURN_CORRECTION;
            case CashReceiptTypeEnum::BUY_RETURN;
                $className = BuyReturnCommand::class;
                break;
            case CashReceiptTypeEnum::SELL_RETURN;
                $className = SellReturnCommand::class;
                break;
            case CashReceiptTypeEnum::BUY_CORRECTION;
                $className = BuyCorrectionCommand::class;
                break;
            case CashReceiptTypeEnum::SELL_CORRECTION;
                $className = SellCorrectionCommand::class;
                break;
            case CashReceiptTypeEnum::SELL_RETURN_CORRECTION;
                $className = SellReturnCorrectionCommand::class;
                break;
            default:
                $className = null;
        }
        if (is_null($className)) {
            throw new ApiException("В чеке указан некорректный тип ($receiptType)", '422', null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $className;
    }

    /**
     * Открытие смены
     *
     * @param string $cashRegisterId
     * @return string
     * @throws ApiException
     * @throws Exception
     */
    public function openShift(string $cashRegisterId): string
    {
        // TODO: https://portal.web-slon.ru/company/personal/user/1/tasks/task/view/9517/
        //Получаем данные текущего пользователя: ФИО и ИНН.
        //Отправляем сообщение в RabbitMQ в очередь cashRegisterServer.{id}, операция Открытие смены.
        //Возвращаем в ответ на запрос сообщение, что запрос получен (не оставляем висеть соединение).
        //Слушаем RabbitMQ, при появлении сообщения о том, что смена открыта на ККМ создаем новый документ Кассовая смена (заполняем полученными параметрами).
        //Извещаем фронт об успешном открытии кассовой смены. Может быть вариант, что смена уже открыта или другие ошибки, тогда сообщаем об этом пользователю.
        //В ответе от ККМ может прийти warnings.notPrinted = true, в этом случае сообщаем пользователю: “Документ закрыт, но не допечатан. Вероятно произошел сбой печати (самый стандартный случай - закончилась бумага). Необходимо устранить неисправность. После устранения неисправности требуется продолжить печать.”

        $cashRegister = $this->getCashRegisterById($cashRegisterId);
        $this->denyIfNotAllowCashRegister($cashRegister);

        $scheduleForCashier = $this->cashierScheduleRepository->findCurrentCashierScheduleForCashier($this->currentUser);
        if (is_null($scheduleForCashier)) {
            throw new ApiException(
                'appointment.cash_receipt.not_found_actual_cashier_shift',
                'ERROR_ACTUAL_CASHIER_SHIFT',
                null,
                400
            );
        }

        if (!$this->authorizationChecker->isGranted(CashRegisterVoter::ATTRIBUTE_ALLOW_OPEN_SHIFT, $cashRegister)) {
            throw new ApiException('cashier.shift.open_access_denied', 'ACCESS_DENIED', null, 403);
        }

        $cashRegisters = $this->cashRegisterRepository->findAll();
        foreach ($cashRegisters as $cashRegisterItem) {
            $shift = $this->shiftRepository->findOneBy(
                ['cashier' => $this->currentUser->getId(), 'deleted' => false, 'cashRegister' => $cashRegisterItem->getId()],
                ['id' => 'DESC']
            );
            if (is_object($shift)) {
                if ($shift->getState()->code === ShiftStateEnum::OPEN) {
                    throw new ApiException('cashier.shift.found_open_shift_for_selected_cashier', 'FOUND_ALREADY_OPEN_SHIFT_FOR_SELECTED_CASHIER', '', 400);
                }
            }
        }

        if ($lastShift = $this->shiftRepository->findLastShift($cashRegister)) {
            if ($lastShift->getState()->code == ShiftStateEnum::OPEN) {
                throw new ApiException('cashier.shift.found_already_opened_shift', 'FOUND_ALREADY_OPENED_SHIFT', '', 400);
            } elseif ($lastShift->getState()->code === ShiftStateEnum::CLOSE) {
                $lastShift->setCreator($this->currentUser);
                $this->entityManager->persist($lastShift);
                $this->entityManager->flush();
            }
        }

        $command = new OpenShiftCommand($cashRegister->getId(), new Operator($this->operatorName, $this->operatorInn));

        return $this->producer->rpcCallAsync('cashRegisterServer.'.$cashRegister->getCashRegisterServer()->getId(), $command->toArray());
    }

    /**
     * Закрытие смены
     *
     * @param string $cashRegisterId
     * @return string
     * @throws ApiException
     * @throws Exception
     */
    public function closeShift(string $cashRegisterId): string
    {
        // TODO: https://portal.web-slon.ru/company/personal/user/1/tasks/task/view/9520/
        //Получаем данные текущего пользователя: ФИО и ИНН.
        //Получаем текущую открытую Кассовую смену. Если открытой нет - выдаем ошибку.
        //Отправляем сообщение в RabbitMQ в очередь cashRegisterServer.{id}, операция Закрытие смены.
        //Возвращаем в ответ на запрос сообщение, что запрос получен (не оставляем висеть соединение).
        //Слушаем RabbitMQ, при появлении сообщения о том, что смена закрыта на ККМ обновляем документ Кассовая смена (заполняем полученными параметрами). Сохраняется признак закрытия смены на ККМ и фискальные параметры, после чего любые изменения документа становятся невозможны.
        //Извещаем фронт об успешном закрытии кассовой смены. Может быть вариант, что смена уже закрыта или другие ошибки, тогда сообщаем об этом пользователю.
        //В ответе от ККМ может прийти warnings.notPrinted = true, в этом случае сообщаем пользователю: “Документ закрыт, но не допечатан. Вероятно произошел сбой печати (самый стандартный случай - закончилась бумага). Необходимо устранить неисправность. После устранения неисправности требуется продолжить печать.”

        $cashRegister = $this->getCashRegisterById($cashRegisterId);
        $this->denyIfNotAllowCashRegister($cashRegister);
        $command = new CloseShiftCommand($cashRegister->getId(), new Operator($this->operatorName, $this->operatorInn));

        if (!$this->authorizationChecker->isGranted(CashRegisterVoter::ATTRIBUTE_ALLOW_REGISTER_CASH_RECEIPT, $cashRegister)) {
            throw new ApiException('cashier.shift.close_access_denied', 'ACCESS_DENIED', null, 403);
        }

        if ($lastShift = $this->shiftRepository->findLastShift($cashRegister)) {
            if ($lastShift->getState()->code !== ShiftStateEnum::OPEN) {
                throw new ApiException('cashier.shift.not_found_opened_shift', 'NOT_FOUND_OPENED_SHIFT', '', 400);
            }
        }

        if ($this->currentUser && $lastShift->getCashier()) {
            if ($this->currentUser->getId() != $lastShift->getCreator()->getId()) {
                throw new ApiException('cashier.shift.attempt_to_close_shift_by_another_user', 'NOT_CREATOR_CLOSED_SHIFT', '', 400);
            }
        }

        $id = $this->producer->rpcCallAsync('cashRegisterServer.'.$cashRegister->getCashRegisterServer()->getId(), $command->toArray());

        $fiscalParameters = new FiscalParameters();
        $fiscalParameters->setState(FiscalReceiptStateEnum::getItem(FiscalReceiptStateEnum::PRINTING));
        $lastShift->setFiscalClose($fiscalParameters);

        if ($this->currentUser) {
            $lastShift->setCashier($this->currentUser);
        }

        $this->entityManager->persist($lastShift);
        $this->entityManager->flush();

        return $id;
    }

    /**
     * Отчет без гашения, ответа от ККМ нет
     *
     * @param string $cashRegisterId
     * @return string
     * @throws ApiException
     * @throws Exception
     */
    public function reportX(string $cashRegisterId): string
    {
        $cashRegister = $this->getCashRegisterById($cashRegisterId);
        $this->denyIfNotAllowCashRegister($cashRegister);

        $command = new ReportXCommand($cashRegister->getId(), new Operator($this->operatorName, $this->operatorInn));

        return $this->producer->rpcCallAsync('cashRegisterServer.'.$cashRegister->getCashRegisterServer()->getId(), $command->toArray());
    }

    /**
     * Допечатать документ, ответа от ККМ нет
     *
     * @param string $cashRegisterId
     * @return string
     * @throws ApiException
     * @throws Exception
     */
    public function continuePrint(string $cashRegisterId): string
    {
        $cashRegister = $this->getCashRegisterById($cashRegisterId);
        $this->denyIfNotAllowCashRegister($cashRegister);

        $command = new ContinuePrintCommand($cashRegister->getId());

        return $this->producer->rpcCallAsync('cashRegisterServer.'.$cashRegister->getCashRegisterServer()->getId(), $command->toArray());
    }

    /**
     * @param int $cashReceiptId
     * @return bool|string
     * @throws ApiException
     * @throws Exception
     */
    public function registerCashReceipt(int $cashReceiptId) {
        // TODO: https://portal.web-slon.ru/company/personal/user/1/tasks/task/view/9519/
        //Получаем данные текущего пользователя: ФИО и ИНН.
        //По id получаем данные Кассового чека, а также данные ККМ-сервера из ККМ в чеке.
        //Отправляем сообщение в RabbitMQ в очередь cashRegisterServer.{id}, операция Пробить кассовый чек.
        //Возвращаем в ответ на запрос сообщение, что запрос получен (не оставляем висеть соединение).
        //Слушаем RabbitMQ, при появлении сообщения о том, что чек пробит, обновляем документ Кассовый чек. Сохраняется признак, что чек пробит на ККМ и фискальные параметры чека, после чего любые изменения документа становятся невозможны.
        //Извещаем фронт об успешном пробитии чека. Либо если возникли ошибки, тогда сообщаем об этом пользователю.
        //В ответе от ККМ может прийти warnings.notPrinted = true, в этом случае сообщаем пользователю: “Документ закрыт, но не допечатан. Вероятно произошел сбой печати (самый стандартный случай - закончилась бумага). Необходимо устранить неисправность. После устранения неисправности требуется продолжить печать.”
        $cashReceipt = $this->cashReceiptRepository->findCashReceipt($cashReceiptId);
        if (is_null($cashReceipt)) {
            throw new ApiException("Кассовый чек с id $cashReceiptId не найден", '404', null, Response::HTTP_NOT_FOUND);
        }
        $this->denyIfNotAllowCashRegister($cashReceipt->getCashRegister());

        if (!$this->authorizationChecker->isGranted(CashRegisterVoter::ATTRIBUTE_ALLOW_REGISTER_CASH_RECEIPT, $cashReceipt->getCashRegister())) {
            throw new ApiException('cashier.cash_receipt.access_denied', 'ACCESS_DENIED', null, 403);
        }

        if ($lastShift = $this->shiftRepository->findLastShift($cashReceipt->getCashRegister())) {
            if ($lastShift->getState()->code !== ShiftStateEnum::OPEN) {
                throw new ApiException('cashier.shift.not_found_opened_shift', 'NOT_FOUND_OPENED_SHIFT', '', 400);
            }
        }

        if ($cashReceipt->getFiscal()->getState()->code === FiscalReceiptStateEnum::PRINTING) {
            throw new ApiException('cashier.cash_receipt.already_printing', 'CASH_RECEIPT_ALREADY_PRINTING', null, Response::HTTP_BAD_REQUEST);
        }

        if ($cashReceipt->getFiscal()->getState()->code === FiscalReceiptStateEnum::DONE) {
            throw new ApiException('cashier.cash_receipt.printing_finished', 'CASH_RECEIPT_PRINTING_FINISHED', null, Response::HTTP_BAD_REQUEST);
        }

        $commandClassName = $this->getReceiptsCommandClassNameByReceiptType($cashReceipt->getType());
        /** @var SellCorrectionCommand $command */
        $command = (new $commandClassName(
            $cashReceiptId,
            $cashReceipt->getCashRegister()->getId(),
            new Operator($this->operatorName, $this->operatorInn)
        ));

        // общие параметры
        $electronically = $this->isElectronicallyReceipt($cashReceipt);
        $command
            ->setIgnoreNonFiscalPrintErrors(false)
            ->setElectronically($electronically)
            ->setTaxationType($this->getReceiptTaxationType($cashReceipt));

        // данные клиента для электронных чеков
        if ($electronically) { // надо передать email или телефон
            if ($cashReceipt->getDeliveryType() === ReceiptDeliveryTypeEnum::EMAIL) {
                $command->setClientInfo(new ClientInfo($cashReceipt->getCustomerEmail()));
            }
            if ($cashReceipt->getDeliveryType() === ReceiptDeliveryTypeEnum::PHONE) {
                $command->setClientInfo(new ClientInfo($cashReceipt->getCustomerPhone()));
            }
        }

        // таблица товаров
        /** @var ReceiptItem $cashItem */
        foreach ($cashReceipt->getItems() as $cashItem) {
            $item = new Item();
            $item->type = 'position';
            $item->name = $cashItem->getName();
            $item->price = $cashItem->getPriceWithCharge();
            $item->quantity = $cashItem->getQuantity();
            $item->amount = $cashItem->getAmount();
            $item->measurementUnit = $cashItem->getMeasure();
            $item->paymentMethod = $this->getReceiptPaymentMethod($cashReceipt);
            $item->paymentObject = $this->getReceiptItemPaymentObject($cashItem);

            $productCodeType = $cashItem->getProductCode()->getType();
            $productCodeGtin = $cashItem->getProductCode()->getGtin();
            $productCodeSerial = $cashItem->getProductCode()->getSerial();

            if ($productCodeType) {
                $item->nomenclatureCode = (new NomenclatureCode())
                    ->setType($productCodeType)
                    ->setGtin($productCodeGtin)
                    ->setSerial($productCodeSerial)
                ;
            }

            $item->tax = (new Tax())->setTypeByVatRate($cashItem->getVatRate());

            /*
            $item->agentInfo = new AgentInfo();
            $item->agentInfo->agents = [];
            $item->agentInfo->moneyTransferOperator = new MoneyTransferOperator();
            $item->agentInfo->moneyTransferOperator->name = '';
            $item->agentInfo->moneyTransferOperator->address = '';
            $item->agentInfo->moneyTransferOperator->phones = [];
            $item->agentInfo->moneyTransferOperator->vatin = '';
            $item->agentInfo->payingAgent = new PayingAgent();
            $item->agentInfo->payingAgent->phones = [];
            $item->agentInfo->payingAgent->operation = '';
            $item->agentInfo->agents = [];
            $item->agentInfo->receivePaymentsOperator = new ReceivePaymentsOperator();
            $item->agentInfo->receivePaymentsOperator->phones = [];
            $item->supplierInfo = new SupplierInfo();
            $item->supplierInfo->phones = [];
            $item->supplierInfo->vatin = '';
            $item->supplierInfo->name = '';
            */

            $command->addItem($item);
        }

        // платеж
        $payment = (new Payment())
            ->setType($this->getReceiptPaymentType($cashReceipt))
            ->setSum($cashReceipt->getTotal())
        ;
        $command->addPayment($payment);

        if (is_subclass_of($command, BaseRegisterCorrectionCommand::class)) {
            $command
                ->setCorrectionBaseDate($cashReceipt->getCorrectionBaseDate()->format('Y.m.d'))
                ->setCorrectionBaseName($cashReceipt->getCorrectionDescription())
                ->setCorrectionBaseNumber($cashReceipt->getCorrectionBaseNumber())
                ->setCorrectionType($this->getReceiptCorrectionType($cashReceipt))
            ;
        }

        $id = $this->producer->rpcCallAsync('cashRegisterServer.'.$cashReceipt->getCashRegister()->getCashRegisterServer()->getId(), $command->toArray());

        $fiscal = $cashReceipt->getFiscal() ?? new FiscalParameters();
        $fiscal->setState(FiscalReceiptStateEnum::getItem(FiscalReceiptStateEnum::PRINTING));
        $cashReceipt->setFiscal($fiscal);
        if ($this->currentUser) {
            $cashReceipt->setCashier($this->currentUser);
        }
        $cashReceipt->setStartPrintAt(new DateTime());

        $this->entityManager->persist($cashReceipt);
        $this->entityManager->flush();

        return $id;
    }

    /**
     * @param int $cashFlowId
     * @return bool|string
     * @throws ApiException
     * @throws Exception
     */
    public function registerCashFlow(int $cashFlowId)
    {
        /** @var CashFlow $cashFlow */
        $cashFlow = $this->cashFlowRepository->findCashFlow($cashFlowId);
        if (is_null($cashFlow)) {
            throw new ApiException("Чек внесения / выплаты с id $cashFlowId не найден", '404', null, Response::HTTP_NOT_FOUND);
        }

        $this->denyIfNotAllowCashRegister($cashFlow->getCashRegister());

        // TODO Необходимо доработаь проверку для CashFlow
        if (!$this->authorizationChecker->isGranted(CashRegisterVoter::ATTRIBUTE_ALLOW_REGISTER_CASH_RECEIPT, $cashFlow->getCashRegister())) {
            throw new ApiException('cashier.cash_receipt.access_denied', 'ACCESS_DENIED', null, 403);
        }

        if ($lastShift = $this->shiftRepository->findLastShift($cashFlow->getCashRegister())) {
            if ($lastShift->getState()->code !== ShiftStateEnum::OPEN) {
                throw new ApiException('cashier.shift.not_found_opened_shift', 'NOT_FOUND_OPENED_SHIFT', '', 400);
            }
        }

        $command = (new CashFlowCommand(
            $cashFlow->getCashRegister()->getId(),
            $cashFlow->getId(),
            new Operator($this->operatorName, $this->operatorInn)
        ));

        $command->setCashSum($cashFlow->getTotal());
        $command->setType($cashFlow->getType());

        return $this->producer->rpcCallAsync(
            'cashRegisterServer.'.$cashFlow->getCashRegister()->getCashRegisterServer()->getId(),
            $command->toArray()
        );
    }

    /**
     * Отчет о текущем состоянии расчетов
     *
     * @param string $cashRegisterId
     * @return string
     * @throws ApiException
     * @throws Exception
     */
    public function reportOfExchangeStatus(string $cashRegisterId): string
    {
        $cashRegister = $this->getCashRegisterById($cashRegisterId);
        $this->denyIfNotAllowCashRegister($cashRegister);

        $command = new ReportOfExchangeStatusCommand(
            $cashRegister->getId(),
            new Operator($this->operatorName, $this->operatorInn)
        );

        return $this->producer->rpcCallAsync('cashRegisterServer.'.$cashRegister->getCashRegisterServer()->getId(), $command->toArray());
    }


    /**
     * Запрос параметров регистрации ККМ
     *
     * @param string $cashRegisterId
     * @return string
     * @throws ApiException
     * @throws Exception
     */
    public function getRegistrationInfo(string $cashRegisterId): string
    {
        $cashRegister = $this->getCashRegisterById($cashRegisterId);
        $this->denyIfNotAllowCashRegister($cashRegister);

        $command = new RegistrationInfoCommand($cashRegister->getId());

        return $this->producer->rpcCallAsync('cashRegisterServer.'.$cashRegister->getCashRegisterServer()->getId(), $command->toArray());
    }

    private function denyIfNotAllowCashRegister(CashRegister $cashRegister)
    {
        if (!$this->authorizationChecker->isGranted(CashRegisterVoter::ATTRIBUTE_AVAILABLE_CASH_REGISTER, $cashRegister)) {
            throw new ApiException('cashier.cash_register.access_denied', 'ACCESS_DENIED', null, 403);
        }
    }
}

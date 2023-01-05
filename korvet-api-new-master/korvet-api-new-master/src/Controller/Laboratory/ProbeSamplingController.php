<?php

namespace App\Controller\Laboratory;

use App\Entity\Laboratory\ProbeSampling;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Packages\DBAL\Types\CashReceiptTypeEnum;
use App\Packages\DBAL\Types\FiscalReceiptStateEnum;
use App\Packages\DBAL\Types\PaymentMethodEnum;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\DependenciesService;
use App\Repository\Reference\CashRegisterRepository;
use App\Repository\Cash\CashierScheduleRepository;
use App\Repository\Laboratory\ProbeSamplingRepository;
use App\Packages\Response\BaseResponse;
use App\Exception\ApiException;
use App\Entity\Cash\CashReceipt;
use App\Entity\ReceiptItem;
use App\Packages\DBAL\Types\ReceiptDeliveryTypeEnum;
use App\Entity\Embeddable\FiscalParameters;
use App\Entity\Embeddable\ProductCode;
use App\Packages\DBAL\Types\PaymentObjectEnum;
use OpenApi\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * Class ProbeSamplingController
 * @Route("/api/laboratory/probe-sampling")
 * @Resource(
 *     description="Main desc",
 *     tags={"ProbeSampling"},
 *     summariesMap={
 *          "list": "Получить список отбора проб",
 *          "get": "Получить отбор проб",
 *          "post": "Создать отбор проб",
 *          "delete": "Удалить отбор проб",
 *          "patch": "Обновить отбор проб"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список отбора проб",
 *          "get": "Возвращает отбор проб",
 *          "post": "Создает отбор проб",
 *          "delete": "Удаляет существующую отбор проб",
 *          "patch": "Обновляет существующую отбор проб"
 *     }
 * )
 */
class ProbeSamplingController extends AbstractController
{
    public const ENTITY_CLASS = ProbeSampling::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;

     /** @var EntityManagerInterface */
     private $_entityManager;

     private $logger;
 
     /**
      * ShopSubscriber constructor.
      * @param EntityManagerInterface $entityManager
      * @param LoggerInterface $logger
      */
     public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
     {
         $this->_entityManager = $entityManager;
         $this->logger = $logger;
     }

    /**
     * @SWG\Post(
     *     summary="Создает новый кассовый чек для продажи",
     *     description="Создает новый кассовый чек для продажи",
     *     @SWG\Response(
     *         response=200,
     *         @SWG\JsonContent(),
     *         description="Успешный ответ сервиса",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="boolean"),
     *              @SWG\Property(property="requestId", type="string"),
     *              @SWG\Property(property="errors", type="array", @SWG\Items(ref=@Model(type=ApiException::class))),
     *              @SWG\Property(property="response",
     *                  type="object",
     *                  ref=@Model(type=CashReceipt::class)
     *              )
     *         )
     *     )
     * )
     * @Route("/{id}/cash-receipt/", methods={"POST"})
     *
     * @param $id
     * @param \App\Repository\Laboratory\ProbeSamplingRepository $probeSamplingRepository
     * @param \App\Repository\Cash\CashierScheduleRepository $cashierScheduleRepository
     * @param EntityManagerInterface $entityManager
     * @param DependenciesService $dependenciesService
     * @param CashRegisterRepository $cashRegisterRepository
     * @param BaseResponse $response
     * @throws \App\Exception\ApiException
     * @throws \App\Service\HandlerException\Validation\ValidationException
     * @throws \Doctrine\DBAL\Exception
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
     public function createCashReceipt(
         $id,
         ProbeSamplingRepository $probeSamplingRepository,
         CashierScheduleRepository $cashierScheduleRepository,
         EntityManagerInterface $entityManager,
         DependenciesService $dependenciesService,
         CashRegisterRepository $cashRegisterRepository,
         BaseResponse $response
     )
     {
         $probeSampling = $probeSamplingRepository->findOneBy(['id' => $id, 'deleted' => false]);
 
         if (!$probeSampling) {
             throw new ApiException('appointment.not_found');
         }
 
         if ($probeSampling->getCashReceipt()) {
             return $response
                 ->setResponse(['id' => $probeSampling->getCashReceipt()->getId()])
                 ->setSerializationContext(['groups' => ['default']])
                 ->send();
         }
 
         $scheduleForCashier = $cashierScheduleRepository->findCurrentCashierScheduleForCashier($this->getUser());
         if (!$scheduleForCashier) {
             throw new ApiException(
                 'appointment.cash_receipt.not_found_actual_cashier_shift',
                 'ERROR_ACTUAL_CASHIER_SHIFT',
                 null,
                 400
             );
         }
         $cashRegister = $scheduleForCashier->getCashRegister();
 
         $cashReceipt = new CashReceipt();
 
         $total = 0;
         $itemsForCashReceipt = [];
         foreach ($probeSampling->getProbeItems() as $probeItem) {
             $cashReceiptItem = new ReceiptItem();
 
             $cashReceiptItem->setCashReceipt($cashReceipt);
             $cashReceiptItem->setProductCode(new ProductCode());
             $cashReceiptItem->setPaymentObject(PaymentObjectEnum::getItem(PaymentObjectEnum::SERVICE));
             $cashReceiptItem->setVatRate($probeItem->getProbe()->getVatRate());
             $cashReceiptItem->setPrice($probeItem->getPrice());
             $cashReceiptItem->setMeasure('шт');
             $cashReceiptItem->setAmount(round($probeItem->getAmount(), 2));
             $cashReceiptItem->setQuantity($probeItem->getQuantity());
             $cashReceiptItem->setName('Отбор пробы: '.$probeItem->getProbe()->getName());
             $cashReceiptItem->setPriceWithCharge($probeItem->getPrice());
 
             $itemsForCashReceipt[] = $cashReceiptItem;
             $total += $cashReceiptItem->getAmount();

             foreach ($probeItem->getResearchDocuments() as $researchItem) {
                 if ($researchItem->getPrice() == 0) {
                     continue;
                 }
                $cashResearchItem = new ReceiptItem();
    
                $cashResearchItem->setCashReceipt($cashReceipt);
                $cashResearchItem->setProductCode(new ProductCode());
                $cashResearchItem->setPaymentObject(PaymentObjectEnum::getItem(PaymentObjectEnum::SERVICE));
                $cashResearchItem->setVatRate($probeItem->getProbe()->getVatRate());
                $cashResearchItem->setPrice($researchItem->getPrice());
                $cashResearchItem->setMeasure('шт');
                $cashResearchItem->setAmount(round($researchItem->getPrice(), 2));
                $cashResearchItem->setQuantity(1);
                $cashResearchItem->setName('Исследование: '.$researchItem->getResearch()->getName());
                $cashResearchItem->setPriceWithCharge($researchItem->getPrice());
    
                $itemsForCashReceipt[] = $cashResearchItem;
                $total += $cashResearchItem->getAmount();
            }
         }
 
         # Logger for KORVET-40
         $this->logger->error(sprintf('Cash receipt. ID: %d. Total: %d. ItemsForCashReceipt: %s',
             $id,
             $total,
             \GuzzleHttp\json_encode($itemsForCashReceipt)
         ));
 
         $cashReceipt->setType(CashReceiptTypeEnum::getItem(CashReceiptTypeEnum::SELL));
         $cashReceipt->setCashRegister($cashRegister);
         $cashReceipt->setCreator($this->getUser());
         $cashReceipt->setTotal(round($total, 2));
         $cashReceipt->setTaxationType($cashRegister->getOrganization()->getTaxationType());
         $cashReceipt->setItems($itemsForCashReceipt);
         $cashReceipt->setDeliveryType(ReceiptDeliveryTypeEnum::getItem(ReceiptDeliveryTypeEnum::PRINT));
         $cashReceipt->setPaymentMethod(PaymentMethodEnum::getItem(PaymentMethodEnum::FULL_PAYMENT));
 
         $cashReceipt->setPaymentType($probeSampling->getPaymentType());
 
         $fiscalParameters = new FiscalParameters();
         $fiscalParameters->setState(FiscalReceiptStateEnum::getItem(FiscalReceiptStateEnum::NEW));
         $cashReceipt->setFiscal($fiscalParameters);
 
         $dependenciesService->getValidator()->validate($cashReceipt);
 
         $entityManager->persist($cashReceipt);
         $entityManager->flush();
 
         $probeSampling->setCashReceipt($cashReceipt);
         $entityManager->persist($probeSampling);
         $entityManager->flush();
 
         return $response
             ->setResponse(['id' => $cashReceipt->getId()])
             ->setSerializationContext(['groups' => ['default']])
             ->send();
     }
 
     /**
      * @return array
      */
     public function getSerializationContextOptions(): array
     {
         return [
             'getList' => ['groups' => ['default']],
             'addItem' => ['groups' => ['default']],
             'getItem' => ['groups' => ['default']],
             'patchItem' => ['groups' => ['default']],
             'updateItem' => ['groups' => ['default']],
         ];
     }
     
}

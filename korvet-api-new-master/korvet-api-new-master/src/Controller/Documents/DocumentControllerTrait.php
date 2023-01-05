<?php

namespace App\Controller\Documents;

use App\Entity\Appointment\Appointment;
use App\Entity\Cash\CashReceipt;
use App\Entity\Document\DocumentHistory;
use App\Entity\ProductStock;
use App\Enum\DocumentStateEnum;
use App\Interfaces\ServiceDocumentInterface;
use App\Service\Document\DocumentService;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\DBAL\Types\FiscalReceiptStateEnum;
use App\Exception\ApiException;
use App\Packages\Response\BaseResponse;
use App\Service\DeserializeService;
use OpenApi\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;

trait DocumentControllerTrait
{
    /**
     * Обновить статус документа
     *
     * @Route("/{number}/state/", methods={"POST"})
     * @SWG\Post(
     *
     * @SWG\Parameter(
     *      name="number",
     *      in="path",
     *          @SWG\Schema(
     *              type="string"
     *          ),
     *      description="Номер документа",
     *  ),
     *
     * @SWG\Parameter(
     *         in="body",
     *         name="Данные для обновления статуса документа",

     *         @SWG\Schema(ref=@Model(type=DocumentStateEnum::class))
     *  ),
     *
     * @SWG\Response(
     *          response=200,
     *          description="Успешный ответ сервиса",
     *     )
     * )
     *
     * @param int $number
     * @param BaseResponse $response
     * @param Request $request
     * @param DocumentService $documentService
     * @param DeserializeService $deserializeService
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws ApiException
     * @throws DBALException
     */
    public function changeState(
        $number,
        BaseResponse $response,
        Request $request,
        DocumentService $documentService,
        DeserializeService $deserializeService,
        EntityManagerInterface $entityManager
    ) :Response {

        /** @var ServiceDocumentInterface $document*/
        $document = $documentService->addDocument($number, $this::ENTITY_CLASS);

        if (!$document) {
            throw new ApiException(
                'document.not_found',
                'document.not_found',
                null,
                Response::HTTP_BAD_REQUEST
            );
        }

        /** @var DocumentStateEnum $state */
        $state = $deserializeService->deserialize($request->getContent(), DocumentStateEnum::class, 'json');
        if (!$state instanceof DocumentStateEnum) {
            throw new ApiException(
                'document.state.not_found',
                'document.state.not_found',
                null,
                Response::HTTP_BAD_REQUEST
            );
        }

        //был черновик и теперь регестрируем
        if (($document->getDocument()->getState()->code === DocumentStateEnum::DRAFT || $document->getDocument()->getState()->code === DocumentStateEnum::ERROR) &&
            $state->code === DocumentStateEnum::REGISTERED
        ) {
            $entityManager->getConnection()->beginTransaction();

            $document->setState($state);

            /** @var DocumentHistory[] $historyArray */
            $historyArray = $document->getHistory();

            try {
                //поступление по старой схеме
                /** @var DocumentHistory $history */
                foreach ($historyArray as $history) {

                    /** @var ProductStock $productStock */
                    $productStock = $entityManager->getRepository(ProductStock::class)->findOneBy(
                        ['product' => $history->getProduct()->getId(), 'stock' => $history->getStock()->getId()]
                    );
                    if (!$productStock) {
                        $productStock = new ProductStock();
                        $productStock->setStock($history->getStock());
                        $productStock->setProduct($history->getProduct());
                    }

                    $quantity = $productStock->getQuantity() + $history->getQuantity();

                    //поступление по этому прибавляем
                    $productStock->setQuantity($quantity);

                    if ($productStock->getQuantity() !== 0) {
                        $productStock->getProduct()->setExistQuantity(true);
                    }

                    $entityManager->persist($productStock);
                    $entityManager->flush();
                }
                $entityManager->getConnection()->commit();
            } catch (\Exception $exception) {
                $entityManager->getConnection()->rollBack();
                $document->removeHistory();
                $document->setState(DocumentStateEnum::getItem(DocumentStateEnum::ERROR));
                //TODO: сообщение попадает без перевода, и не очень все понятно из текста
                $document->addError($exception->getMessage());
                throw new ApiException($exception->getMessage());
            }

        } elseif ($document->getDocument()->getState()->code === DocumentStateEnum::REGISTERED &&
            $state->code === DocumentStateEnum::DRAFT) { //переводим обратно в статус черновика
            $entityManager->getConnection()->beginTransaction();
            try {
                $history = $document->getHistory();
                $document->setState($state);

                //по действующей схеме откатить, если ранне конечно что то записали
                $this->rollback($history, $entityManager);
                //                Если прием и чек создан, но не распечатан - удаляем
                if ($document->getDocument() instanceof Appointment) {
                    /** @var Appointment $appointment */
                    $appointment = $document->getDocument();
                    /** @var CashReceipt $cashReceipt */
                    $cashReceipt = $appointment->getCashReceipt();
                    if ($cashReceipt) {
                        if (strcasecmp($cashReceipt->getFiscal()->getState(), FiscalReceiptStateEnum::DONE) != 0) {
                            $appointment->removeCashReceipt();
                        }
                    }
                }


                $entityManager->getConnection()->commit();
            } catch (\Exception $exception) {
                $entityManager->getConnection()->rollBack();
                $document->addError($exception->getMessage());
                throw new ApiException($exception->getMessage());
            }
        } elseif ($document->getDocument()->getState()->code === DocumentStateEnum::ERROR &&
            $state->code === DocumentStateEnum::DRAFT) {
            $entityManager->getConnection()->beginTransaction();
            $document->setState($state);
            $document->clearErrors();
            $entityManager->getConnection()->commit();
        } elseif ($document->getDocument()->getState()->code === DocumentStateEnum::REGISTERED &&
            $state->code === DocumentStateEnum::REGISTERED) {
            throw new ApiException(
                'document.state.already_registered',
                'document.state.not_found',
                null,
                Response::HTTP_BAD_REQUEST
            );
        }

        return $response
            ->setResponse([
                'state' => $document->getDocument()->getState(),
                'date'  => $document->getDate() // дата регистрации документа
            ])
            ->setSerializationContext(['groups' => ['default']])
            ->send();
    }

    /**
     * по действующей схеме откатить, если ранне конечно что то записали
     * @param array|null $objects
     * @param EntityManagerInterface $entityManager
     */
    protected function rollback(?array $objects, EntityManagerInterface $entityManager) : void
    {
        if ($objects === null) {
            return;
        }

        foreach ($objects as $history) {
            /** @var ProductStock $productStock*/
            $productStock = $entityManager->getRepository(ProductStock::class)->findOneBy(
                ['product' => $history->getProduct()->getId(), 'stock' => $history->getStock()->getId()]
            );

            if (!$productStock) {
                continue;
            }
            //отнимем, у перемещения должны быть отрицательные значения
            $productStock->setQuantity($productStock->getQuantity() - $history->getQuantity());
        }
    }
}

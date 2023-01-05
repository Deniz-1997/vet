<?php

namespace App\Controller\CRUD\Batch;

use App\Interfaces\CRUD\CrudBatchServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Operation;
use App\Interfaces\ApiControllerInterface;
use App\Packages\Response\Async\AsyncResponseBody;
use App\Service\CRUD\UpdateItemService;
use App\Service\SerializationContextFetcher;
use App\Service\ValidationService;
use App\Exception\ApiException;
use App\Packages\Response\BaseResponse;

/**
 * Trait ReplaceItemTrait
 */
trait ReplaceBatchTrait
{
    /**
     * @Route("/batch/replace/", methods={"PUT"})
     * @Operation("batch-put")
     * @SWG\Put(
     *     @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="Идентификатор обновляемой сущности",
     *          @SWG\Schema(
     *              type="string"
     *          )
     *     ),
     *     @SWG\Parameter(
     *          name="entity",
     *          in="body",
     *          description="Json-объект с данными обновляемой сущности, допустима передача сущности частично"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         @SWG\JsonContent(),
     *         description="Успешный ответ сервиса, в результате приходят
     *     данные обновленного объекта",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         @SWG\JsonContent(),
     *         description="Ошибка операции обновления",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     )
     * )
     *
     * @param Request                   $request
     * @param CrudBatchServiceInterface $batchService
     * @param BaseResponse              $response
     * @param array                     $params
     *
     * @return Response
     */
    public function replaceBatchAction(Request $request, CrudBatchServiceInterface $batchService, BaseResponse $response, array $params = []): Response
    {
        $dtoName = defined(get_class($this) . '::DTO_CLASS') ? self::DTO_CLASS : null;
        $correlationId = $batchService->handleReplace($request->getContent(), self::ENTITY_CLASS, $dtoName, $params);

        return $response
            ->setHttpResponseCode(Response::HTTP_ACCEPTED)
            ->setResponse((new AsyncResponseBody())->setCorrelationId($correlationId))
            ->send();
    }
}

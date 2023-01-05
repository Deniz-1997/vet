<?php

namespace App\Controller\CRUD\Batch;

use App\Interfaces\CRUD\CrudBatchServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Operation;
use App\Packages\Response\Async\AsyncResponseBody;
use App\Packages\Response\BaseResponse;
/**
 * Trait DeleteItemTrait
 */
trait DeleteBatchTrait
{
    /**
     * @Route("/batch/delete", methods={"DELETE"})
     * @Operation("batch-delete")
     * @SWG\Delete(
     *     @SWG\Parameter(
     *          name="package",
     *          in="body",
     *          description="Массив с Json-объектами сущностей",
     *          @SWG\Schema(type="array", @SWG\Items(type="object"))
     *     ),
     *     @SWG\Response(
     *         response=202,
     *         @SWG\JsonContent(),
     *         description="Запрос принят в обработку",
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
    public function deleteBatchAction(Request $request, CrudBatchServiceInterface $batchService, BaseResponse $response, array $params = []): Response
    {
        $dtoName = defined(get_class($this) . '::DTO_CLASS') ? self::DTO_CLASS : null;
        $correlationId = $batchService->handleDelete($request->getContent(), self::ENTITY_CLASS, $dtoName, $params);

        return $response
            ->setHttpResponseCode(Response::HTTP_ACCEPTED)
            ->setResponse((new AsyncResponseBody())->setCorrelationId($correlationId))
            ->send();
    }
}

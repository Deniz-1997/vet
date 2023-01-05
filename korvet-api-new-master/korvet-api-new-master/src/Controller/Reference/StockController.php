<?php

namespace App\Controller\Reference;

use App\Entity\Reference\Stock;
use App\Entity\FtpHistory;
use App\Service\Export\ExportStockService;
use App\Service\Import\ImportStockService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\ApiController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use App\Packages\Response\BaseResponse;
use App\Exception\ApiException;
use OpenApi\Annotations as SWG;

/**
 * Class StockController
 * @Route("/api/reference/stock")
 * @Resource(
 *     tags={"Stock"},
 *     summariesMap={
 *          "list": "Получить список складов",
 *          "get": "Получить склад",
 *          "post": "Создать склад",
 *          "delete": "Удалить склад",
 *          "patch": "Обновить склад"
 *     },
 *     descriptionsMap={
 *          "list": "Получить список складов",
 *          "get": "Получить склад",
 *          "post": "Создать склад",
 *          "delete": "Удалить склад",
 *          "patch": "Обновить склад"
 *     },
 * )
 */
class StockController extends ApiController
{
    public const ENTITY_CLASS = Stock::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;

    /**
     *
     * @SWG\Post(
     *      @SWG\JsonContent(),
     *     summary="Создает и загружает CSV файл на фтп 1с",
     *     description="Создает и загружает CSV файл на фтп 1с
### Пример запроса:
```
{
""dateFrom"":""11.03.2019 09:00:00"",
""dateTill"":""12.03.2019 09:00:00""
}
```
    "),
     *     @SWG\Parameter(
     *          parameter="body",
     *          name="body",
     *          in="body",
     *          @SWG\Schema(
     *              example={
     *                  "dateFrom"="11.03.2019 09:00:00",
     *                  "dateTill"="12.03.2019 09:00:00",
     *              })
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Успешный ответ сервиса",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="boolean"),
     *              @SWG\Property(property="errors", type="array", @SWG\Items(ref=@Model(type=ApiException::class))),
     *              @SWG\Property(property="response", @SWG\Items(
     *                  type="object",
     *                  ref=@Model(type=FtpHistory::class)
     *              )),
     *              @SWG\Property(property="requestId", type="string"),
     *         )
     *     )
     * )
     * @Route("/export-to-1c/", methods={"POST"})
     * @param Request $request
     * @param ExportStockService $exportStockService
     * @param BaseResponse $response
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function exportTo1C(Request $request, ExportStockService $exportStockService, BaseResponse $response)
    {
        $data = json_decode($request->getContent(), true);
        if (empty($data['dateFrom']) || empty($data['dateTill'])) {
            throw new ApiException('dateFrom and dateTill should be string dates', 400);
        }

        $ftpHistory = $exportStockService->exportStock($data['dateFrom'], $data['dateTill']);

        return $response
            ->setResponse($ftpHistory)
            ->setSerializationContext(['groups' => ['default']])
            ->send();
    }

    /**
     * @SWG\Post(
     *      @SWG\JsonContent(),
     *     summary="Импотрирует данные 1с о Складах с фтп",
     *     description="Импотрирует данные 1с о Складах с фтп",
     *     @SWG\Response(
     *         response=200,
     *         description="Успешный ответ сервиса",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="boolean"),
     *              @SWG\Property(property="errors", type="array", @SWG\Items(ref=@Model(type=ApiException::class))),
     *              @SWG\Property(property="response", type="object",
     *                  @SWG\Property(property="status", type="string")
     *              ),
     *              @SWG\Property(property="requestId", type="string"),
     *         )
     *     )
     * )
     * @Route("/import-stock/", methods={"POST"})
     * @param ImportStockService $importStock
     * @param BaseResponse $response
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function importStock(ImportStockService $importStock, BaseResponse $response)
    {
        $results = $importStock->importStock();

        return $response
            ->setResponse($results)
            ->setSerializationContext(['groups' => ['default']])
            ->send();
    }

    /**
     * @SWG\Post(
     *      @SWG\JsonContent(),
     *     summary="Импотрирует данные 1с о сервисах с фтп",
     *     description="Импотрирует данные 1с о сервисах с фтп",
     *     @SWG\Response(
     *         response=200,
     *         description="Успешный ответ сервиса",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="boolean"),
     *              @SWG\Property(property="errors", type="array", @SWG\Items(ref=@Model(type=ApiException::class))),
     *              @SWG\Property(property="response", type="object",
     *                  @SWG\Property(property="status", type="string")
     *              ),
     *              @SWG\Property(property="requestId", type="string"),
     *         )
     *     )
     * )
     * @Route("/import-service/", methods={"POST"})
     * @param ImportStockService $importStock
     * @param BaseResponse $response
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function importService(ImportStockService $importStock, BaseResponse $response)
    {
        $results = $importStock->importService();

        return $response
            ->setResponse($results)
            ->setSerializationContext(['groups' => ['default']])
            ->send();
    }

    /**
     * @SWG\Post(
     *      @SWG\JsonContent(),
     *     summary="Импотрирует данные 1с о передвижениях на складах с фтп",
     *     description="Импотрирует данные 1с о передвижениях на складах с фтп",
     *     @SWG\Response(
     *         response=200,
     *         description="Успешный ответ сервиса",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="boolean"),
     *              @SWG\Property(property="errors", type="array", @SWG\Items(ref=@Model(type=ApiException::class))),
     *              @SWG\Property(property="response", type="object",
     *                  @SWG\Property(property="status", type="string")
     *              ),
     *              @SWG\Property(property="requestId", type="string"),
     *         )
     *     )
     * )
     * @Route("/import-stock-move/", methods={"POST"})
     * @param ImportStockService $importStock
     * @param BaseResponse $response
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function importStockMove(ImportStockService $importStock, BaseResponse $response)
    {
        $results = $importStock->importStockMove();

        return $response
            ->setResponse($results)
            ->setSerializationContext(['groups' => ['default']])
            ->send();
    }

    /**
     * @return array
     */
    public function getSerializationContextOptions() : array
    {
        return [
            'getList'    => ['groups' => ['default']],
            'addItem'    => ['groups' => ['default']],
            'getItem'    => ['groups' => ['default']],
            'patchItem'  => ['groups' => ['default']],
            'updateItem' => ['groups' => ['default']],
        ];
    }
}

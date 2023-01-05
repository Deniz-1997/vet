<?php

namespace App\Controller;

use App\Service\ReportsTypeFactory;
use App\Service\PrintFormHistoryService;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use PhpOffice\PhpSpreadsheet\Exception;
use OpenApi\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Packages\Annotation\Response as WebslonResponse;
use App\Controller\ApiController;
use App\Exception\ApiException;
use App\Packages\Response\BaseResponse as ApiResponse;


/**
 * @Route("/api/print-report")
 * @Resource(
 *     description="Печать",
 *     tags={"Printing"},
 *     summariesMap={
 *          "post": "Печать",
 *     },
 *     descriptionsMap={
 *          "post": "Печать",
 *     },
 *     responsesMap={
 *          "list": {
 *              @WebslonResponse(
 *                  response=200,
 *                  description="Успешный ответ от сервиса",
 *                  model=OrdersTodayResponse::class
 *              ),
 *              @WebslonResponse(
 *                  response=500,
 *                  description="Ошибка",
 *                  model=BaseResponse::class
 *              )
 *          }
 *     }
 * )
 */
class PrintReportController extends ApiController
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /** KernelInterface $appKernel */
    private KernelInterface $appKernel;

    /** @var ReportsTypeFactory */
    private ReportsTypeFactory $reportType;

    /** @var ApiResponse */
    private ApiResponse $response;

    /** @var PrintFormHistoryService */
    private PrintFormHistoryService $reportFormService;

    public function __construct(
        EntityManagerInterface $entityManager,
        ReportsTypeFactory $typeFactory,
        KernelInterface $appKernel,
        ApiResponse $response,
        PrintFormHistoryService $reportFormService
    ) {
        $this->reportType = $typeFactory;
        $this->appKernel = $appKernel;
        $this->entityManager = $entityManager;
        $this->response = $response;
        $this->reportFormService = $reportFormService;
    }

    /**
     * @param Request $request
     * @param string $form
     *
     * @return Response
     * @Route("/{form}/", methods={"POST"})
     * @Operation(
     *     tags={"Printing"},
     *     summary="Printing form",
     *     description="

    ### Примеры запросов:

    ### shiftReport - Отчет за смену (все склады - если пустой массив stockIds):
    {
    ""stockIds"":[1, 2, 3],
    ""date"":""2019-09-25 00:00:00""
    }

    ### revenueReport - Отчет по выручке (все склады - если массив stockIds пустой или не указан)
    {
    ""stockIds"":[1, 2, 3],
    ""date"":""2019-09-26 00:00:00""
    }

    ### warehouseStatement - Ведомость по товарам на складах (productID - необязательное поле, усли указано - выборка идет по конкретной номенклатуре)
    {
    ""startTime"":""2019-09-27 00:00:00"",
    ""endTime"":""2019-09-28 23:59:59"",
    ""stockId"":""2"",
    ""productId"":""1024""
    }

    ### cullingReport - Отчет по отлову живоных
    {
    ""startDate"":""2019-09-27 00:00:00"",
    ""endDate"":""2021-09-28 23:59:59"",
    ""contractorIds"":[1,2,3,4],
    }
    ",
     *
     *     @SWG\Parameter(
     *          name="form",
     *          in="path",
     *          @SWG\Schema(
     *              type="string"
     *          ),
     *          description="Идентификатор печатной формы",
     *          required=true
     *     ),
     *     @SWG\Parameter(
     *          parameter="body",
     *          name="body",
     *          in="body",
     *          @SWG\Schema(
     *              type="object"
     *          )
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="Success",
     *          @SWG\Schema(type="object",
     *              @SWG\Property(property="status", type="boolean"),
     *              @SWG\Property(property="response", type="object",
     *                  @SWG\Property(property="name", type="string"),
     *                  @SWG\Property(property="outputDir", type="string")
     *              ),
     *              @SWG\Property(property="requestId", type="string"),
     *          )
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         description="Ошибка выполнения операции",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     )
     * )
     *          examples={
     *              "application/json":{
     *                  "status": true,
     *                  "errors": null,
     *                  "requestId": null,
     *                  "response": {
     *                      "name": "file-01-11-2018-3f23t1320gf",
     *                      "outputDir": ""
     *                  }
     *              }
     *          },
     *         examples={
     *              "application/json":{
     *                  "status": false,
     *                  "response": null,
     *                  "errors": {0: {"message": "Текст ошибки", "stringCode":
     *     "ERROR_STRING_CODE", "relatedField": null}}
     *              }
     *         },
     */
    public function getReport(Request $request, string $form): Response
    {

        $data = json_decode($request->getContent(), true);

        $report = ReportsTypeFactory::generateReport($form);
        $result = $report->getData($data, $this->entityManager);

        if (!is_array($result)) {
            return $this->response->addError(new ApiException($result))->statusError()->send();
        }

        $fileName = $report->createFile($result, $this->appKernel);

        $response = [
            'name' => $fileName,
            'outputDir' => '/docs/xlsx/',
        ];

        try {
            $this->reportFormService->AddChanges($form);
        } finally {
            return $this->response->setResponse($response)->send();
        }
    }
}

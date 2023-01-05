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
    private $entityManager;

    /** KernelInterface $appKernel */
    private $appKernel;

    /** @var ReportsTypeFactory */
    private $reportType;

    /** @var ApiResponse */
    private $response;

    public function __construct(
        EntityManagerInterface $entityManager,
        ReportsTypeFactory $typeFactory,
        KernelInterface $appKernel,
        ApiResponse $response
    ) {
        $this->reportType = $typeFactory;
        $this->appKernel = $appKernel;
        $this->entityManager = $entityManager;
        $this->response = $response;
    }

    /**
     * @param Request $request
     * @param string $form
     *
     * @return Response
     * @throws Exception
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     * @Route("/{form}/", methods={"POST"})
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

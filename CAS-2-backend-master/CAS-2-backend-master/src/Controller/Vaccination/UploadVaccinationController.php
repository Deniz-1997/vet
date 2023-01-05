<?php

namespace App\Controller\Vaccination;

use App\Entity\UploadedFile;
use App\Exception\ApiException;
use App\Packages\Response\BaseResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Interfaces\CAS\CasExcelUploadInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Packages\Annotation\Operation;
use OpenApi\Annotations as SWG;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Reference\BusinesEntity;
use App\Entity\Reference\SupervisedObjects;
use App\Entity\Reference\Subdivision;

/**
 * Class UploadVaccinationController
 * @Route("/api")
 * @Resource(tags={"UploadVaccination"})
 */
class UploadVaccinationController extends AbstractController
{
    /**
     * @Route("/upload_vaccination/excel", methods={"POST"})
     * @Operation("post")
     * @SWG\Post(
     *     @SWG\Parameter(
     *          @SWG\Schema(
     *              type="file"
     *          ),
     *          name="file",
     *          in="formData",
     *          description="Файл"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Успешный ответ сервиса",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     )
     * )
     *  examples={
     *              "application/json":{
     *                  "status": true,
     *                  "response": {"id": 1},
     *                  "errors": null
     *              }
     *         },
     * @param Request $request
     * @param BaseResponse $response
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function uploadExcelFile(
        Request $request,
        BaseResponse $response,
        TranslatorInterface $translator,
        CasExcelUploadInterface $casUploadService,
        EntityManagerInterface $entityManagerInterface
    ): Response {

        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
        $file = is_null($request->files->get('file')) ? $request->files->get('upload') : $request->files->get('file');

        if (is_null($file)) {
            return $response->statusError()
                ->addError(new ApiException($translator->trans('file.not_found')))
                ->send();
        }

        $beId = $request->request->getInt('beId');
        $businesEntity = null;
        if ($beId != null) {
            /** @var BusinesEntity $businesEntity*/
            $businesEntity = $entityManagerInterface->getRepository(BusinesEntity::class)->findOneBy(['id' => $beId]);
        }

        $supervisedObjectId = $request->request->getInt('supervisedObjectId');
        $supervisedObject = null;
        $station = null;
        if ($supervisedObjectId != null) {
            /** @var SupervisedObjects $businesEntity*/
            $supervisedObject = $entityManagerInterface->getRepository(SupervisedObjects::class)->findOneBy(['id' => $supervisedObjectId]);
            if ($supervisedObject != null) {
                $station = $supervisedObject->getStation();
            }
        }

        $subdivisionObjectId = $request->request->getInt('subdivisionObjectId');
        $subdivisionObject = null;
        if ($subdivisionObjectId != null) {
            /** @var Subdivision $subdivisionObject*/
            $subdivisionObject = $entityManagerInterface->getRepository(Subdivision::class)->findOneBy(['id' => $subdivisionObjectId]);
        }

        $subdivisionObjectId = $request->request->getInt('subdivisionObjectId');

        $result = $casUploadService->SaveFile($file, $station,  $businesEntity, $supervisedObject, $subdivisionObject);

        return $response->setResponse($result)->send();
    }
}

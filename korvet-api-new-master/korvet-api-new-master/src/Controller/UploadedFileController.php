<?php

namespace App\Controller;

use App\Entity\ProductStock;
use App\Entity\Security\Role;
use App\Entity\UploadedFile;
use App\Model\Env;
use App\Repository\PrintFormsRepository;
use App\Repository\Security\RoleRepository;
use App\Repository\UploadedFileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use GuzzleHttp\Psr7\MimeType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Packages\Annotation\Resource;
use App\Controller\ApiController;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\PatchItemTrait;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Packages\Annotation\Response as WebslonResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Packages\Annotation\Operation;
use OpenApi\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Interfaces\ApiControllerInterface;
use App\Service\CRUD\AddItemService;
use App\Service\SerializationContextFetcher;
use App\Service\ValidationService;
use App\Exception\ApiException;
use App\Packages\Response\BaseResponse;

/**
 * @Route("/api/uploaded-file")
 *
 * @Resource(
 *     tags={"UploadedFile"},
 *     summariesMap={
 *          "list": "Получить список загруженных файлов",
 *          "get": "Получить загруженный файл",
 *          "post": "Загрузить файл на сервер",
 *          "delete": "Удалить загруженный файл",
 *          "patch": "Обновить загруженный файл"
 *     },
 *     descriptionsMap={
 *          "list": "Получить список загруженных файлов",
 *          "get": "Получить загруженный файл",
 *          "post": "Загрузить файл на сервер",
 *          "delete": "Удалить загруженный файл",
 *          "patch": "Обновить загруженный файл"
 *     }
 * )
 */
class UploadedFileController extends ApiController
{
    use GetListTrait, PatchItemTrait, GetItemTrait, DeleteItemTrait;

    const ENTITY_CLASS = UploadedFile::class;

    private MimeType $mineTypes;

    /**
     * @Route("/", methods={"POST"})
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
     *         description="Успешный ответ сервиса, в результате приходят данные добаленного объекта",
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
     * @param EntityManagerInterface $entityManager
     * @param \App\Service\ValidationService $validationService
     * @return Response
     * @throws \App\Service\HandlerException\Validation\ValidationException
     */
    public function addItemAction(Request $request, BaseResponse $response, TranslatorInterface $translator, EntityManagerInterface $entityManager, ValidationService $validationService): Response
    {
        $file = is_null($request->files->get('file'))? $request->files->get('upload') : $request->files->get('file');

        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
        if (is_null($file)) {
            return $response->statusError()
                            ->addError(new ApiException($translator->trans('file.not_found')))
                            ->send();
        }

        $md5 = md5_file($file->getPathname());

        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $entityManager->getRepository(UploadedFile::class)->findOneBy(
            ['md5' => $md5]
        );

        if($uploadedFile){
            if(!is_null($request->files->get('upload'))){
                return $response->setResponse([
                    "uploaded"  =>  true,
                    'url'   =>  $uploadedFile->getPath().'/'.$uploadedFile->getName()
                ])->setSimpleReturn(true)->send();
            }
            return $response->setResponse($uploadedFile)->setSerializationContext(['groups' => ['default']])->send();
        }

        $array = str_split($md5, strlen($md5) / 4);
        $generatedFileName = $array[count($array) - 1] . '.' . $file->getClientOriginalExtension();
        unset($array[count($array) - 1]);
        $path = Env::getenv('UPLOAD_FILE_PUBLIC_DIR').'/'.implode('/', $array);

        $uploadedFile = new UploadedFile();
        $uploadedFile->setName($generatedFileName);
        $uploadedFile->setMimeType($file->getClientMimeType());
        $uploadedFile->setSize($file->getSize());
        $uploadedFile->setRelativePath($this->generateUrl('app_uploadedfile_view', ['name' => $generatedFileName]));
        $uploadedFile->setUploadedFile($file);
        $uploadedFile->setPath($path);
        $uploadedFile->setMd5($md5);

        $validationService->validate($uploadedFile);

        $file->move($this->getParameter('kernel.project_dir').'/public/'.$path, $generatedFileName);

        $entityManager->persist($uploadedFile);
        $entityManager->flush();

        if(!is_null($request->files->get('upload'))){
            return $response->setResponse([
                "uploaded"  =>  true,
                'url'   =>  $uploadedFile->getPath().'/'.$uploadedFile->getName()
            ])->setSimpleReturn(true)->send();
        }

        return $response->setResponse($uploadedFile)->setSerializationContext(['groups' => ['default']])->send();
    }

    /**
     * @Route("/view/{name}", methods={"GET"})
     * @param string $name
     * @param \App\Repository\UploadedFileRepository $uploadedFileRepository
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     * @return Response
     * @throws \App\Exception\ApiException
     */
    public function view(
        string $name,
        UploadedFileRepository $uploadedFileRepository,
        TokenStorageInterface $tokenStorage
    ): Response
    {
        $fileSource = $this->getParameter('kernel.project_dir').'/public/'.Env::getenv('UPLOAD_FILE_PUBLIC_DIR').'/'.$name;
        if (!file_exists($fileSource)) {
            throw new ApiException('', 'NOT_FOUND', null, Response::HTTP_NOT_FOUND);
        }

        $uploadedFile = $uploadedFileRepository->findOneBy(['name' => $name]);
        if ($uploadedFile && $uploadedFile->getType() == UploadedFile::TYPE_PRINT_FORM) {
            if (!$this->isGranted('ROLE_READ_PRINT_FORM')) {
                throw new ApiException('print_forms.access_denied', 'ACCESS_DENIED', null, Response::HTTP_FORBIDDEN);
            }
        }

        $headers = [];
        $fileInfo = explode('.', $fileSource);
        $ext = array_pop($fileInfo);

        if ($mimeType = $this->mineTypes->fromExtension($ext)) {
            $headers['Content-Type'] = $mimeType;
        }

        return new Response(
            file_get_contents($fileSource),
            200,
            $headers
        );
    }
}

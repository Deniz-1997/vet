<?php

namespace App\Controller\Reference;

use App\Entity\Reference\FormTemplate;
use App\Repository\Form\FormFieldRepository;
use App\Service\FormBuilderService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use App\Controller\ApiController;
use App\Exception\ApiException;
use App\Packages\Response\BaseResponse as ApiResponse;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Annotations as SWG;

/**
 * Class FormTemplateController
 * @package App\Controller\Reference
 * @Route("/api/reference/form-template")
 * @Resource(
 *     description="Main desc",
 *     tags={"FormTemplate"},
 *     summariesMap={
 *          "list": "Получить список формы шаблонов",
 *          "get": "Получить форму шаблона",
 *          "post": "Создать форму шаблона",
 *          "delete": "Удалить форму шаблона",
 *          "patch": "Обновить форму шаблона"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список формы шаблонов",
 *          "get": "Возвращает форму шаблона по идентификатору",
 *          "post": "Создает новую форму шаблона",
 *          "delete": "Удаляет существующую форму шаблона",
 *          "patch": "Обновляет существующую форму шаблона"
 *     }
 * )
 */
class FormTemplateController extends ApiController
{
    public const ENTITY_CLASS = FormTemplate::class;

    /** @var ApiResponse */
    private $response;

    /** @var FormFieldRepository */
    private $formFieldRepository;

    /** @var FormBuilderService */
    private $formBuilderService;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;

    public function __construct(
        ApiResponse $response,
        FormFieldRepository $formFieldRepository,
        FormBuilderService $formBuilderService
    ) {
        $this->response = $response;
        $this->formFieldRepository = $formFieldRepository;
        $this->formBuilderService = $formBuilderService;
    }

    /**
     * @Route("/preview/", methods={"POST"})
     * @SWG\Post(
     *     summary="Просмтотр формы шаблона",
     *     description="Просмтотр формы шаблона",
     *     @SWG\Parameter(
     *          parameter="body",
     *          name="body",
     *          in="body",
     *          @SWG\Schema(
     *              example={}
     *          )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Успешный ответ сервиса, в результате приходят данные запрошенного объекта",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         description="Ошибка выполнения операции",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     )
     * )
     * @param Request $request
     * @return Response
     * @throws ApiException
     */
    public function preview(Request $request)
    {
        return $this->response->setResponse(
            $this->formBuilderService->createFields(json_decode($request->getContent(), true))
        )->send();
    }

    /**
     * @Route("/{id}/clone/", requirements={"id": "[a-z0-9\-\w+]{1,}"}, methods={"GET"})
     * @SWG\Get(
     *     @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="Идентификатор клонируемого шаблона",
     *          @SWG\Schema(
     *              type="string"
     *          )
     *     ),
     *     @SWG\Parameter(
     *          name="fields",
     *          in="query",
     *          @SWG\Schema(
     *              type="string"
     *          ),
     *          description="Json-объект с списком полей необходимых для получения"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         @SWG\JsonContent(),
     *         description="Успешный ответ сервиса, в результате приходят
     *     данные запрошенного объекта",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         @SWG\JsonContent(),
     *         description="Entity not found",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         @SWG\JsonContent(),
     *         description="Ошибка выполнения операции",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     )
     * )
     * @param string         $id
     * @param Request        $request
     *
     * @return Response
     * @throws ApiException
     */
    public function clone(string $id, Request $request)
    {
        return $this->response->setResponse(
            $this->formBuilderService->cloneFormTemplate($id)
        )->send();
    }

    /**
     * @return array
     */
    public function getSerializationContextOptions() : array
    {
        return [
            'getList'    => ['groups' => ['api.formTemplate']],
            'addItem'    => ['groups' => ['api.formTemplate']],
            'getItem'    => ['groups' => ['api.formTemplate']],
            'patchItem'  => ['groups' => ['api.formTemplate']],
            'updateItem' => ['groups' => ['api.formTemplate']],
        ];
    }
}

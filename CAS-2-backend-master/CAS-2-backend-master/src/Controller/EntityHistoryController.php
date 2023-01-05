<?php

namespace App\Controller;

use App\Entity\HistoryEntity;
use App\Service\EntityResolver;
use App\Traits\CreateExceptionTranslationTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;
use App\Packages\Annotation\Resource;
use App\Service\CRUD\GetListService;
use App\Packages\Annotation\History;
use App\Exception\ApiException;
use OpenApi\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * Class EntityHistoryController
 * @Route("/api/history", methods={"GET"})
 * @Resource(
 *    tags={"History"},
 *    description="Бизнес лог сущностей",
 * )
 */
class EntityHistoryController extends AbstractController
{
    use CreateExceptionTranslationTrait;

    const ENTITY_CLASS = HistoryEntity::class;

    /**
     * @var EntityResolver
     */
    private $resolver;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var GetListService
     */
    private $getListService;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * EntityHistoryController constructor.
     *
     * @param EntityResolver      $resolver
     * @param TranslatorInterface $translator
     * @param RequestStack        $request
     * @param GetListService      $getListService
     */
    public function __construct(
        EntityResolver $resolver,
        TranslatorInterface $translator,
        RequestStack $request,
        GetListService $getListService,
        EntityManagerInterface $em
    )
    {
        $this->resolver = $resolver;
        $this->translator = $translator;
        $this->request = $request->getCurrentRequest();
        $this->getListService = $getListService;
        $this->em = $em;
    }

    /**
     * @SWG\Get(
     *     summary="Получить весь бизнес-лог сущности",
     *     description="Получить весь бизнес-лог сущности",
     *     @SWG\Parameter(
     *          name="filter",
     *          in="query",
     *          @SWG\Schema(
     *              type="string"
     *          ),
     *          description="Фильтрация по полям"
     *     ),
     *     @SWG\Parameter(
     *          name="aliasEntity",
     *          in="query",
     *          @SWG\Schema(
     *              type="string"
     *          ),
     *          description="Алиас сущности",
     *          required=false
     *     ),
     *     @SWG\Parameter(
     *          name="id",
     *          in="query",
     *          @SWG\Schema(
     *              type="string"
     *          ),
     *          description="ID сущности",
     *          required=false
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         @SWG\JsonContent(),
     *         description="Успешный ответ сервиса, в результате приходят
     *     данные запрошенного объекта",
     *         @Model(type=EntityHistoryResponse::class)
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         @SWG\JsonContent(),
     *         description="Ошибка выполнения операции",
     *         @Model(type=App\Packages\Response\BaseResponse::class)
     *     )
     * )
     *
     *          examples={
     *              "application/json":{
     *                  "status": false,
     *                  "response": {},
     *                  "errors": null
     *              }
     *         },
     *         examples={
     *              "application/json":{
     *                  "status": false,
     *                  "response": null,
     *                  "errors": {0: {"message": "Текст ошибки", "stringCode":
     *     "ERROR_STRING_CODE", "relatedField": null}}
     *              }
     *         },
     *
     * @Route("/history/", options={}, methods={"GET"})
     * @return Response
     * @throws ApiException
     * @throws \Doctrine\ORM\Mapping\MappingException
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\Query\QueryException
     * @throws \ReflectionException
     */
    public function list()
    {
        $filter = json_decode($this->request->get('filter'), true);
        $aliasEntity = $filter['objectClass'];
        if ($aliasEntity) {
            /** @var History $historyAnnotation */
            if (!$historyAnnotation = $this->resolver->resolve($aliasEntity)) {
                $this->createException('error.alias_entity.not_found', [], Response::HTTP_INTERNAL_SERVER_ERROR, ['{alias}' => $aliasEntity]);
            }
            $filter = array_merge_recursive([
                'objectClass' => $historyAnnotation->entity,
            ], json_decode($this->request->get('filter') ?? '[]', true));
        }

        $this->request->query->add([
            'filter' => json_encode($filter),
        ]);
        return $this->getListService->getList($this->request, self::ENTITY_CLASS)->send();
    }

    /**
     * @param array $items
     * @param array $itemsRelation
     */
    public function addItems($items, &$itemsRelation)
    {
        foreach ($items as $item) {
            if (!empty($item)) {
                $itemsRelation[] = $item;
            }
        }
    }
}

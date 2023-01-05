<?php

namespace App\Controller;

use Doctrine\Common\Collections\Criteria;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use OpenApi\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Service\CRUD\EnumService;
use App\Packages\Response\BaseResponse;

/**
 * Class EnumController
 * @Route("/api/enum", methods={"GET"})
 * @Resource(
 *    tags={"Enum"}
 * )
 */
class EnumController extends AbstractController
{
    /**
     * @var EnumService
     */
    private EnumService $enumService;

    /**
     * EnumController constructor.
     *
     * @param EnumService $enumService
     */
    public function __construct(EnumService $enumService)
    {
        $this->enumService = $enumService;
    }

    /**
     * @Route("/", name="app.enum.list.all", methods={"GET"})
     * @SWG\Get(
     *     operationId="list",
     *     summary="Получить список enum и их значений",
     *     description="Возвращает список enum",
     *     tags={"Enum"},
     *     @SWG\Parameter(
     *          name="filter",
     *          in="query",
     *          @SWG\Schema(
     *              type="string"
     *          ),
     *          description="Json-объект с фильтром записей вида {&quot;column_name&quot;: &quot;value&quot;}"
     *     ),
     *     @SWG\Response(
     *          response=200,
     *         @SWG\JsonContent(),
     *          description="Возвращает коллекцию Enum",
     *          @Model(type=EnumListResponse::class)
     *     ),
     *     @SWG\Response(
     *         @SWG\JsonContent(),
     *          response="default",
     *          description="Error",
     *          @Model(type=BaseResponse::class)
     *     )
     * )
     * @param Request $request
     * @return JsonResponse
     * @throws ReflectionException
     */
    public function getAll(Request $request): JsonResponse
    {
        $filter = $request->get('filter');
        if (!$filter) {
            $items = $this->enumService->getAll();
        } else {
            $items = $this->enumService->getItemsByFilter(json_decode($filter, true));
        }

        return $this->json($items);
    }
}

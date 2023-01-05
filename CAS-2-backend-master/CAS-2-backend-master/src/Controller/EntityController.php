<?php


namespace App\Controller;

use App\Packages\Response\BaseResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Entity\Embeddable\Entity;
use App\Packages\Fetcher\EntityFetcher;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class EntityController
 *
 * @Route("/api/entity")
 */
class EntityController extends AbstractController
{
    /**
     * @SWG\Get(
     *     tags={"Entity"},
     *     summary="Получить список сущностей в системе",
     *     @SWG\Response(
     *         response=200,
     *         description="Успешный ответ сервиса",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="boolean"),
     *              @SWG\Property(property="requestId", type="string"),
     *              @SWG\Property(property="response", type="array", @SWG\Items(ref=@Model(type=Entity::class)))
     *         )
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         description="Ошибка выполнения операции",
     *         @Model(type=Webslon\Library\Api\Response\BaseResponse::class)
     *     )
     * )
     * @Route("/", methods={"GET"})
     * @param EntityFetcher $entityFetcher
     * @param BaseResponse $response
     * @return Response
     */
    public function entity(EntityFetcher $entityFetcher, BaseResponse $response)
    {
        $entities = $entityFetcher->getEntities();

        return $response
            ->setResponse($entities)
            ->setSerializationContext(['groups' => ['default']])
            ->send();
    }
}

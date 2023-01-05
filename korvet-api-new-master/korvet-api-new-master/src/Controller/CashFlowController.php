<?php

namespace App\Controller;

use App\Entity\Cash\CashFlow;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Packages\DBAL\Types\CashReceiptTypeEnum;
use App\Exception\ApiException;
use App\Packages\Response\BaseResponse;

/**
 * Class CashFlowController
 * @Route("/api/cash-flow")
 * @Resource(
 *     description="Кассовый чек",
 *     tags={"CashFlow"},
 *     summariesMap={
 *          "list": "Получить список документов внесения\выплаты",
 *          "get": "Получить документ внесения\выплаты",
 *          "post": "Создать документ внесения\выплаты",
 *          "delete": "Удалить документ внесения\выплаты",
 *          "patch": "Обновить документ внесения\выплаты"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список документов внесения\выплаты",
 *          "get": "Возвращает документ внесения\выплаты по идентификатору",
 *          "post": "Создает новую документ внесения\выплаты",
 *          "delete": "Удаляет существующий документ внесения\выплаты",
 *          "patch": "Обновляет существующий документ внесения\выплаты"
 *     }
 * )
 */
class CashFlowController extends AbstractController
{
    public const ENTITY_CLASS = CashFlow::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}

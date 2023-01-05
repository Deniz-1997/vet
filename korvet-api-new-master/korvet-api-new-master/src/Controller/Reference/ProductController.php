<?php

namespace App\Controller\Reference;

use App\Entity\Reference\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\{AddItemTrait,
    DeleteItemTrait,
    GetItemTrait,
    GetListTrait,
    PatchItemTrait};

/**
 * Class ProductController
 * @package App\Controller/Reference
 * @Route("/api/reference/product")
 * @Resource(
 *     description="Main desc",
 *     tags={"Product"},
 *     summariesMap={
 *          "list": "Получить список номенклатуры",
 *          "get": "Получить номеклатуру",
 *          "post": "Создать номеклатуру",
 *          "delete": "Удалить номеклатуру",
 *          "patch": "Обновить номеклатуру"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список номенклатуры",
 *          "get": "Возвращает номеклатуру по идентификатору",
 *          "post": "Создает новую номеклатуру",
 *          "delete": "Удаляет существующую номеклатуру",
 *          "patch": "Обновляет существующую номеклатуру"
 *     }
 * )
 */
class ProductController extends AbstractController
{
    public const ENTITY_CLASS = Product::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}

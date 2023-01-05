<?php
/**
 * Created by PhpStorm.
 */

namespace App\Controller;

use App\Entity\ProductToService;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ProductToServiceController
 * @package App\Controller
 * @Route("/api/product-to-service")
 * @Resource(
 *     description="Main desc",
 *     tags={"ProductToService"},
 *     summariesMap={
 *          "list": "Получить список привязок продуктов к услугам",
 *          "get": "Получить привязку продукта к услуге",
 *          "post": "Создать привязку продукта к услуге",
 *          "delete": "Удалить привязку продукта к услуге"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список привязок продуктов к услугам",
 *          "get": "Возвращает привязку продукта к услуге по идентификатору",
 *          "post": "Создает новую привязку продукта к услуге",
 *          "delete": "Удаляет существующую привязку продукта к услуге"
 *     }
 * )
 */
class ProductToServiceController extends AbstractController
{
    public const ENTITY_CLASS = ProductToService::class;

    use GetListTrait, GetItemTrait, AddItemTrait, DeleteItemTrait;
}

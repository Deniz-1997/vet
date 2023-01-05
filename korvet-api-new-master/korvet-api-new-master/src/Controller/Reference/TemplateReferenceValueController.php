<?php

namespace App\Controller\Reference;

use App\Entity\Reference\TemplateReferenceValue;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;

/**
 * Class TemplateReferenceValueController
 * @package App\Controller\Reference
 * @Route("/api/reference/template-reference-value")
 * @Resource(
 *     description="Main desc",
 *     tags={"TemplateReferenceValue"},
 *     summariesMap={
 *          "list": "Получить список значений для справочников",
 *          "get": "Получить значение для справочника",
 *          "post": "Создать значение для справочника",
 *          "delete": "Удалить значение для справочника",
 *          "patch": "Обновить значение для справочника"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список значений для справочников",
 *          "get": "Возвращает значение справочника по идентификатору",
 *          "post": "Создает новое значение для справочника",
 *          "delete": "Удаляет существующее значение для справочника",
 *          "patch": "Обновляет существующее значение для справочника"
 *     }
 * )
 */
class TemplateReferenceValueController
{
    public const ENTITY_CLASS = TemplateReferenceValue::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}

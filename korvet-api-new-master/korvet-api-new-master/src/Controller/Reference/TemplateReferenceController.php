<?php

namespace App\Controller\Reference;

use App\Entity\Reference\TemplateReference;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\ApiController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;

/**
 * Class TemplateReferenceController
 * @package App\Controller\Reference
 * @Route("/api/reference/template-reference")
 * @Resource(
 *     description="Main desc",
 *     tags={"TemplateReference"},
 *     summariesMap={
 *          "list": "Получить список справочников",
 *          "get": "Получить справочник",
 *          "post": "Создать справочник",
 *          "delete": "Удалить справочник",
 *          "patch": "Обновить справочник"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список справочников",
 *          "get": "Возвращает справочник по идентификатору",
 *          "post": "Создает новый справочник",
 *          "delete": "Удаляет существующий справочник",
 *          "patch": "Обновляет существующий справочник"
 *     }
 * )
 */
class TemplateReferenceController extends ApiController
{
    public const ENTITY_CLASS = TemplateReference::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}

<?php

namespace App\Controller\Laboratory;

use App\Entity\Laboratory\ResearchDocument;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\Documents\DocumentControllerTrait;

/**
 * Class ResearchDocumentController
 * @Route("/api/laboratory/research-document")
 * @Resource(
 *     description="Main desc",
 *     tags={"ResearchDocument"},
 *     summariesMap={
 *          "list": "Получить список исследований",
 *          "get": "Получить исследование",
 *          "post": "Создать исследование",
 *          "delete": "Удалить исследование",
 *          "patch": "Обновить исследование"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список исследований",
 *          "get": "Возвращает исследование",
 *          "post": "Создает исследование",
 *          "delete": "Удаляет существующую исследование",
 *          "patch": "Обновляет существующую исследование"
 *     }
 * )
 */
class ResearchDocumentController extends AbstractController
{
    public const ENTITY_CLASS = ResearchDocument::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait, DocumentControllerTrait;
}

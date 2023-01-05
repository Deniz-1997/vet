<?php

namespace App\Controller\Reference\Owner;

use App\Entity\Reference\Owner\LegalForm as LegalFormEntity;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class LegalForm
 * @package App\Controller\Reference\Owner
 * @Route("/api/reference/owner-legal-form")
 * @Resource(
 *     description="Main desc",
 *     tags={"OwnerLegalForm"},
 *     summariesMap={
 *          "list": "Получить список видов правовых форм юридических лиц",
 *          "get": "Получить вид правовых форм юридических лиц",
 *          "post": "Создать вид правовых форм юридических лиц",
 *          "delete": "Удалить вид правовых форм юридических лиц",
 *          "patch": "Обновить вид правовых форм юридических лиц"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список видов правовых форм юридических лиц",
 *          "get": "Возвращает вид правовых форм юридических лиц",
 *          "post": "Создает новый вид правовых форм юридических лиц",
 *          "delete": "Удаляет существующий вид правовых форм юридических лиц",
 *          "patch": "Обновляет существующий вид правовых форм юридических лиц"
 *     }
 * )
 */
class LegalForm extends AbstractController
{
    public const ENTITY_CLASS = LegalFormEntity::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}

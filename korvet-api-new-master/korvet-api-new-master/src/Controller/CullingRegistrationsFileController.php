<?php

namespace App\Controller;

use App\Entity\CullingRegistrationFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;

/**
 * Class CullingRegistrationsFileController
 * @package App\Controller
 * @Route("/api/culling-registration-file")
 * @Resource(
 *     tags={"CullingRegistration"},
 *     summariesMap={
 *          "list": "Получить список файлов",
 *          "get": "Получить файл",
 *          "post": "Создать файл",
 *          "delete": "Удалить файл"
 *     },
 *     descriptionsMap={
 *          "list": "Получить список список файлов",
 *          "get": "Возвращает список файлов по идентификатору",
 *          "post": "Создать файл",
 *          "delete": "Удалить файл"
 *     },
 * )
 */
class CullingRegistrationsFileController extends AbstractController
{
    public const ENTITY_CLASS = CullingRegistrationFile::class;

    use GetListTrait, GetItemTrait, AddItemTrait, DeleteItemTrait;
}

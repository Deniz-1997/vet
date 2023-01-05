<?php

namespace App\Controller\Vaccination\Import;

use App\Entity\Import\UploadedVaccinationExcelFileEntry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;

/**
 * Class ImportExcelFileController
 * @Route("/api/import/vaccination/excelFiles")
 * @Resource(tags={"ImportExcelFile"})
 */
class ImportExcelFileController extends AbstractController
{
    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait;

    const ENTITY_CLASS = UploadedVaccinationExcelFileEntry::class;
}

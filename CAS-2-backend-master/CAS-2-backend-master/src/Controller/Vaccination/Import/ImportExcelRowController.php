<?php

namespace App\Controller\Vaccination\Import;

use App\Entity\Import\UploadedVaccinationExcelRowEntry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use App\Entity\ApiData\ApiQueueRow;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;

/**
 * Class BreedController
 * @Route("/api/import/vaccination/rows")
 * @Resource(tags={"ImportExcelFileRow"})
 */
class ImportExcelRowController extends AbstractController
{
    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait;

    const ENTITY_CLASS = ApiQueueRow::class;
}

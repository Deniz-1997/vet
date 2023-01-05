<?php

namespace App\Controller\Vaccination\Import;

use App\Entity\Import\UploadedVaccinationExcelFileEntry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use App\Entity\ApiData\ApiQueue;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;

/**
 * Class ImportVaccinationController
 * @Route("/api/import/vaccination")
 * @Resource(tags={"ImportExcelFile"})
 */
class ImportVaccinationController extends AbstractController
{
    use GetListTrait, GetItemTrait;

    const ENTITY_CLASS = ApiQueue::class;
}

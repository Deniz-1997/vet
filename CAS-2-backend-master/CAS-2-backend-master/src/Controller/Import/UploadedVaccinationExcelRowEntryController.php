<?php

namespace App\Controller\Import;

use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Import\UploadedVaccinationExcelRowEntry;

/**
 * @Route("api/uploaded-vaccination-excel-row-entry", name="uploaded_vaccination_excel_row_entry")
 */
class UploadedVaccinationExcelRowEntryController extends AbstractController
{

    public const ENTITY_CLASS = UploadedVaccinationExcelRowEntry::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}

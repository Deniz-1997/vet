<?php

namespace App\Controller\Vaccination;

use App\Controller\CRUD\DeleteItemTrait;
use App\Entity\Reference\Vaccine\Vaccine;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;

/**
 * Class VaccineController
 * @Route("/api/dictionary/vaccine")
 * @Resource(tags={"Vaccination"})
 */
class VaccineController extends AbstractController
{
    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;

    const ENTITY_CLASS = Vaccine::class;
}

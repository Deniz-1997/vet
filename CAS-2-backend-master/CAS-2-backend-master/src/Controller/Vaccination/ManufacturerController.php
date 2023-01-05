<?php

namespace App\Controller\Vaccination;

use App\Controller\CRUD\DeleteItemTrait;
use App\Entity\Reference\Vaccine\Manufacturer;
use App\Traits\ORMTraits\OrmDeletedTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;

/**
 * Class ManufacturerController
 * @Route("/api/dictionary/manufacturer")
 * @Resource(tags={"Vaccination"})
 */
class ManufacturerController extends AbstractController
{
    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;

    const ENTITY_CLASS = Manufacturer::class;
}

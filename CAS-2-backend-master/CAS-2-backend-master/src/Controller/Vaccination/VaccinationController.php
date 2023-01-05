<?php

namespace App\Controller\Vaccination;

use App\Entity\Reference\Vaccine\Vaccination;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;

/**
 * Class VaccinationController
 * @Route("/api/vaccination")
 * @Resource(tags={"Vaccination"})
 */
class VaccinationController extends AbstractController
{
    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait;
    const ENTITY_CLASS = Vaccination::class;
}

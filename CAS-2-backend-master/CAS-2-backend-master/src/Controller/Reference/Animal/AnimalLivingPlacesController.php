<?php

namespace App\Controller\Reference\Animal;

use App\Entity\Reference\Animal\AnimalLivingPlace;
use App\Traits\ORMTraits\OrmDeletedTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AnimalLivingPlacesController
* @Route("/api/dictionary/animal-living-places")
*/
class AnimalLivingPlacesController extends AbstractController
{
    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, OrmDeletedTrait;

    const ENTITY_CLASS = AnimalLivingPlace::class;
}

<?php

namespace App\Controller\Reference\Animal;

use App\Controller\CRUD\DeleteItemTrait;
use App\Entity\Reference\Animal\Breed;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;

/**
 * @Route("/api/dictionary/breed")
 */
class BreedController extends AbstractController
{
    use GetListTrait, AddItemTrait, PatchItemTrait, GetItemTrait, DeleteItemTrait;

    const ENTITY_CLASS = Breed::class;
}

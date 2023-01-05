<?php

namespace App\Controller\Reference\Animal;

use App\Controller\CRUD\DeleteItemTrait;
use App\Entity\Reference\Animal\Animal;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;

/**
 * Class AnimalController
 * @Route("/api/dictionary/animal")
 * @Resource(tags={"Animal"})
 */
class AnimalController extends AbstractController
{
    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;

    const ENTITY_CLASS = Animal::class;
}

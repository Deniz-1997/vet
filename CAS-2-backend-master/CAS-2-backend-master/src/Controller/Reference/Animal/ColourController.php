<?php

namespace App\Controller\Reference\Animal;

use App\Controller\CRUD\DeleteItemTrait;
use App\Entity\Reference\Animal\Colour;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;

/**
 * Class ColourController
 * @Route("/api/dictionary/colour")
 * @Resource(tags={"Vaccination"})
 */
class ColourController extends AbstractController
{
    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;

    const ENTITY_CLASS = Colour::class;
}

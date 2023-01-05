<?php

namespace App\Controller\Reference;

use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use App\Entity\Reference\Manufacturer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @package App\Controller\Reference
 * @Route("/api/reference/reference-manufacturer")
 */
class ManufacturerController extends AbstractController
{
    public const ENTITY_CLASS = Manufacturer::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}

<?php

namespace App\Controller\Reference\Animal;

use App\Controller\CRUD\DeleteItemTrait;
use App\Entity\Reference\Animal\Kind;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;

/**
 * @Route("/api/dictionary/kind")
 */
class KindController extends AbstractController
{
    const ENTITY_CLASS = Kind::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}

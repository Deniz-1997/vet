<?php

namespace App\Controller;

use App\Entity\ExplanatoryNote;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;

/**
* @Route("/api/reports/explanatory_note", name="explanatory_note")
*/
class ExplanatoryNoteController extends AbstractController
{
    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;

    const ENTITY_CLASS = ExplanatoryNote::class;
}

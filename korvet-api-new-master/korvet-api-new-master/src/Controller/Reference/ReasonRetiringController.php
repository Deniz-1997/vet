<?php

namespace App\Controller\Reference;

use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use App\Entity\Reference\ReasonRetiring;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/reference/reason-retiring")
 */
class ReasonRetiringController extends AbstractController
{
    public const ENTITY_CLASS = ReasonRetiring::class;

    use GetItemTrait, GetListTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;

}

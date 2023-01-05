<?php

namespace App\Controller\Reference\Leaving;

use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use App\Entity\Reference\Leaving\ReasonForLeaving;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api/reference/reason-for-leaving")
 */
class ReasonForLeavingController extends AbstractController
{
    public const ENTITY_CLASS =ReasonForLeaving::class;

    use GetItemTrait, GetListTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;
}

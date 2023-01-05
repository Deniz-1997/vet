<?php

namespace App\Controller\Reference;

use App\EntityOld\Animal\Livestock;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;

/**
 * Class LivestockController
 * @Route("api/dictionary/livestock", name="livestock")
 * @Resource(tags={"Livestock"})
 */
class LivestockController extends AbstractController
{
    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait;

    const ENTITY_CLASS = Livestock::class;
}

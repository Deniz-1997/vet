<?php

namespace App\Controller\Contractors;

use App\EntityOld\Contractors\SupervisoryAuthority;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;

/**
 * Class SupervisoryAuthorityController
 * @Route("/api/dictionary/supervisoryAuthority")
 * @Resource(tags={"SupervisoryAuthority"})
 */
class SupervisoryAuthorityController extends AbstractController
{
    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait;

    const ENTITY_CLASS = SupervisoryAuthority::class;

    const CONNECTION = 'cas';
}

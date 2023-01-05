<?php

namespace App\Controller;

use App\Entity\Settings;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;

/**
 * Class SettingsController
 * @Route("/api/settings")
 * @Resource(tags={"Settings"})
 */
class SettingsController extends AbstractController
{
    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait;

    const ENTITY_CLASS = Settings::class;
}

<?php

namespace App\Controller;

use App\Entity\OAuth\Client;
use Symfony\Component\Routing\Annotation\Route;
use App\Packages\Annotation\Resource;
use App\Controller\ApiController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\PatchItemTrait;
use App\Controller\CRUD\GetListTrait;

/**
 * @Resource(
 *     summariesMap={
 *          "list": "Получить список приложений",
 *          "get": "Получить приложение",
 *          "post": "Добавить приложение",
 *          "patch": "Обновить приложение",
 *          "delete": "Удалить приложение"
 *     },
 *     tags={"Client"}
 * )
 * @Route("/api/client")
 */
class ClientController extends ApiController
{
    use AddItemTrait, GetListTrait, PatchItemTrait, GetItemTrait, DeleteItemTrait;
    const ENTITY_CLASS = Client::class;


    /**
     * @return array
     */
    public function getSerializationContextOptions() : array
    {
        return [
            'addItem'    => ['groups' => ['default', 'api.v1.client.list', 'api.v1.group.list']],
            'getItem'    => ['groups' => ['default', 'api.v1.client.list', 'api.v1.group.list']],
            'getList'    => ['groups' => ['default', 'api.v1.client.list', 'api.v1.group.list']],
            'patchItem'  => ['groups' => ['default', 'api.v1.client.list', 'api.v1.group.list', 'api.v1.group.one']],
            'updateItem' => ['groups' => ['default', 'api.v1.client.list', 'api.v1.group.list', 'api.v1.group.one']],
        ];
    }
}

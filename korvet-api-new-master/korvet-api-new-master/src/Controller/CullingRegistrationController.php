<?php


namespace App\Controller;

use App\Entity\CullingRegistration;
use App\Packages\Annotation\Resource;
use App\Controller\ApiController;
use App\Controller\CRUD\AddItemTrait;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\GetListTrait;
use App\Controller\CRUD\PatchItemTrait;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CullingRegistrationController
 * @package App\Controller
 * @Route("/api/culling-registration")
 * @Resource(
 *     description="Main desc",
 *     tags={"CullingRegistration"},
 *     summariesMap={
 *          "list": "Получить список регистрации отлова",
 *          "get": "Получить регистрацию отлова",
 *          "post": "Создать регистрацию отлова",
 *          "delete": "Удалить регистрацию отлова",
 *          "patch": "Обновить регистрацию отлова"
 *     },
 *     descriptionsMap={
 *          "list": "Возвращает список регистрации отлова",
 *          "get": "Возвращает регистрацию отлова по идентификатору",
 *          "post": "Создает новую регистрацию отлова",
 *          "delete": "Удаляет существующую регистрацию отлова",
 *          "patch": "Обновляет существующую регистрацию отлова"
 *     }
 * )
 */

class CullingRegistrationController extends ApiController
{
    public const ENTITY_CLASS = CullingRegistration::class;

    use GetListTrait, GetItemTrait, AddItemTrait, PatchItemTrait, DeleteItemTrait;

    /**
     * @return array
     */
    public function getSerializationContextOptions() : array
    {
        return [
            'getList'    => ['groups' => ['api.cullingRegistration']],
            'addItem'    => ['groups' => ['api.cullingRegistration']],
            'getItem'    => ['groups' => ['api.cullingRegistration']],
            'patchItem'  => ['groups' => ['api.cullingRegistration']],
            'updateItem' => ['groups' => ['api.cullingRegistration']],
        ];
    }
}

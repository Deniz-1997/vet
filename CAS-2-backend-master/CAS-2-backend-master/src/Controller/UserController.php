<?php

namespace App\Controller;

use App\Entity\User\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Packages\Annotation\Resource;
use App\Controller\ApiController;
use App\Controller\CRUD\DeleteItemTrait;
use App\Controller\CRUD\GetItemTrait;
use App\Controller\CRUD\PatchItemTrait;
use App\Controller\CRUD\GetListTrait;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\User\UserRepository;
use App\Service\ValidationService;
use App\Packages\Response\BaseResponse;
use OpenApi\Annotations as SWG;
use App\Exception\ApiException;

/**
 * @Resource(
 *     summariesMap={
 *          "list": "Получить список пользователей",
 *          "post": "Добавить пользователя",
 *          "get": "Получить пользователя",
 *          "patch": "Обновить пользователя",
 *          "delete": "Удалить пользователя"
 *     },
 *     tags={"User"},
 *     descriptionsMap={
 *          "patch": "
    Пример для обновления пользователя:
    {
        ""email"": ""string@string.com"",
        ""username"": ""root"",
        ""name"": ""string"",
        ""surname"": ""string"",
        ""patronymic"": ""string"",
        ""groups"": [{""id"": 2}, {""id"": 3}],
        ""additionalRestrictions"": {""stores"": [1, 2, 3, 4], ""regions"": [1, 2, 3, 4]}
        ""additionalFields"": {
             ""phone"": ""string"",
             ""hours"":"""",
             ""company"":{
                ""name"":""string"",
                ""address"":""string"".
                ""phone"":""string"".
                ""hours"":""string""
             },
             ""position"": ""string"",
             ""theme"": ""string"",
             ""photoSrc"":""string"",
             ""inn"": ""string""
         }
    }
    "
 *     }
 * )
 * @Route("/api/user")
 */
class UserController extends ApiController
{
    use GetListTrait, PatchItemTrait, GetItemTrait, DeleteItemTrait;
    const ENTITY_CLASS = User::class;

    /**
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserRepository               $userRepository
     * @param BaseResponse                 $response
     *
     * @return Response
     *
     * @SWG\Post(
     *     summary="Регистрация пользователя",
     *     description="
    Пример для создания пользователя
    {
        ""email"":""email@leroymerlin.ru"",
        ""username"":""username"",
        ""plainPassword"":""password"",
        ""name"":""Сергей"",
        ""surname"":""Малахов"",
        ""patronymic"":""Анатольевич"",
        ""additionalRestrictions"": {""stores"": [1, 2, 3, 4], ""regions"": [1, 2, 3, 4]}
        ""additionalFields"": {
            ""phone"": ""string"",
            ""hours"":"""",
            ""company"":{
                ""name"":""string"",
                ""address"":""string"".
                ""phone"":""string"".
                ""hours"":""string""
            },
            ""position"": ""string"",
            ""theme"": ""string"",
            ""photoSrc"":""string"",
            ""inn"": ""string""
        }
    }

    ",
     *     @SWG\Response(
     *         response=200,
     *         @SWG\JsonContent(),
     *         description="Успешный ответ сервиса",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="boolean"),
     *              @SWG\Property(property="requestId", type="string"),
     *              @SWG\Property(property="errors", type="array", @SWG\Items(ref=@Model(type=ApiException::class))),
     *              @SWG\Property(property="response",
     *                  type="object",
     *                  ref=@Model(type=User::class)
     *              )
     *         ),
     *     ),
     *     @SWG\Parameter(
     *          parameter="body",
     *          name="body",
     *          in="body",
     *          description="Данные пользователя",
     *          @SWG\Schema(type="object", ref=@Model(type=User::class), example={
     *               "email":"email@leroymerlin.ru",
     *               "username":"username",
     *               "plainPassword":"password",
     *               "name":"string",
     *               "surname":"string",
     *               "patronymic":"string"
     *          })
     *     )
     * )
     *
     *         examples={
     *              "application/json":{
     *                  "status": true,
     *                  "errors": {},
     *                  "response": {
     *                      "id": 1,
     *                      "email": "leroy@leroy.com",
     *                      "username": "root",
     *                      "groups": {},
     *                      "role_hierarchy": {
     *                          "ROLE_ROOT": {
     *                              "ROLE_ASD_1": {
     *                                  "ROLE_ASD_1_1",
     *                                  "ROLE_ASD_1_2"
     *                              }
     *                          }
     *                      },
     *                      "user": {
     *                          "roles": {
     *                              "ROLE_ROOT"
     *                          }
     *                      }
     *                  }
     *              }
     *         }
     *
     * @Route("/", methods={"POST"})
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository, BaseResponse $response, SerializerInterface $serializer, ValidationService $validator)
    {
        //@todo нужно будет сделать отдельный метод по маппингу сущностей из реквеста

        /** @var User $user */
        $user = $serializer->deserialize($request->getContent(), User::class, 'json', ['groups' => ['registration', 'default']]);

        $user->setSalt(sha1(time()));
        $user->setPassword(
            $passwordEncoder->encodePassword($user, $user->getPlainPassword())
        );

        $validator->validate($user);

        $userRepository->save($user);

        return $response->statusOk()->setResponse($user)->setSerializationContext(['groups' => ['default', 'registration']])->send();
    }

    /**
     * @return array
     */
    public function getSerializationContextOptions() : array
    {
        return [
            'getList'    => ['groups' => ['default', 'api.v1.group.list']],
            'addItem'    => ['groups' => ['default', 'api.v1.user.one']],
            'getItem'    => ['groups' => ['default', 'api.v1.user.one']],
            'patchItem'  => ['groups' => ['default', 'api.v1.user.one']],
            'updateItem' => ['groups' => ['default', 'api.v1.user.one']],
        ];
    }
}

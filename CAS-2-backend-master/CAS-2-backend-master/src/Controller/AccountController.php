<?php

namespace App\Controller;

use App\Packages\DTO\Request\CheckConfirmationCodeRequest;
use App\Packages\DTO\Request\PasswordRecoveryConfirmationCodeRequest;
use App\Packages\DTO\UserDTO;
use App\Entity\Email;
use App\Entity\OAuth\AccessToken;
use App\Entity\Template;
use App\Entity\User\User;
use App\Exception\ApiException;
use App\Repository\OAuth\AccessTokenRepository;
use App\Repository\OAuth\RefreshTokenRepository;
use App\Repository\Security\RoleRepository;
use App\Repository\User\UserRepository;
use App\Packages\Response\BaseResponse;
use App\Packages\Security\RoleManager;
use App\Service\SendEmailService;
use Doctrine\ORM\EntityManager;
use FOS\OAuthServerBundle\Security\Authentication\Token\OAuthToken;
use Nelmio\ApiDocBundle\Annotation\Model;
use phpseclib\Crypt\Base;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use OpenApi\Annotations as SWG;
use App\Entity\Security\Group;
use Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken;


/**
 * Class AccountController.
 *
 * @Route("/api/account")
 */
class AccountController extends Controller
{
    /** @var LoggerInterface */
    private $logger;
    /** @var SendEmailService */
    private $sendEmailService;

    /**
     * AccountController constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger, SendEmailService $sendEmailService)
    {
        $this->logger = $logger;
        $this->sendEmailService = $sendEmailService;
    }

    /**
     * @SWG\Get(
     *     tags={"Account"},
     *     summary="Получить информацию о текущем пользователе",
     *     @SWG\Response(
     *         response=200,
     *         description="Успешный ответ сервиса",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="boolean"),
     *              @SWG\Property(property="requestId", type="string"),
     *              @SWG\Property(property="errors", type="array", @SWG\Items(ref=@Model(type=ApiException::class))),
     *              @SWG\Property(property="response",
     *                  type="object",
     *                  ref=@Model(type=UserDTO::class)
     *              )
     *         ),
     *         @SWG\JsonContent(),
     *     )
     * )
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
     * @Route("/user/", methods={"GET"})
     * @param Request $request
     * @param BaseResponse $response
     * @param AccessTokenRepository $accessTokenRepository
     * @param TokenStorageInterface $tokenStorage
     * @return Response | void
     */
    public function accountAction(Request $request,
                                  BaseResponse $response,
                                  AccessTokenRepository $accessTokenRepository,
                                  TokenStorageInterface $tokenStorage): Response
    {
        $roles = array_map(function ($role) {
            if ($role instanceof Role) {
                return $role->getRole();
            }

            return $role;
        }, $tokenStorage->getToken()->getRoles());
        $preparedUser = $tokenStorage->getToken()->getUser();
        $token = $tokenStorage->getToken();
        if ($token instanceof SwitchUserToken) {
            $token =  $token->getOriginalToken()->getToken();
        } else if ($token instanceof OAuthToken) {
            $token = $token->getToken();
        }
        $accessToken = $accessTokenRepository->findOneBy(array('token' => $token));

        if ($preparedUser instanceof User) {
            $preparedUser->setRoles($roles);
        } else {
            if (is_string($preparedUser)) {
                $applicationName = $preparedUser;
                $preparedUser = new User();
                $preparedUser->setUsername($applicationName);
                $preparedUser->setRoles($roles);
                $preparedUser->setUsername($applicationName);
            } else {
                $preparedUser = null;
            }
        }

        $authenticationInformation = new UserDTO();

        if ($preparedUser) {
            $authenticationInformation->setUser($preparedUser);
            $authenticationInformation->setGroups($preparedUser->getGroups() ? $preparedUser->getGroups()->toArray() : []);
        }

        $client = $accessToken->getClient();
        $authenticationInformation->setClientId(sprintf('%d_%s', $client->getId(), $client->getRandomId()));
        $att_array = json_decode(str_replace("'", '"', $request->get('fields')), true);
        if (is_array($att_array)) {
            $response->setAttributes($att_array);
        }
        return $response->setSerializationContext(['groups' => ['default', 'account']])->setResponse($authenticationInformation)->send();
    }

    /**
     * @SWG\Patch(
     *     tags={"Account"},
     *     description="Обновить текущего пользователя",
     *     summary="Обновить текущего пользователя",
     *     @SWG\Parameter(
     *         in="body",
     *         name="Данные пользователя для обновления",
     *         @SWG\Schema(ref=@Model(type=User::class))
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Успешный ответ сервиса",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="boolean"),
     *              @SWG\Property(property="requestId", type="string"),
     *              @SWG\Property(property="errors", type="array", @SWG\Items(ref=@Model(type=ApiException::class))),
     *              @SWG\Property(property="response", type="object", ref=@Model(type=User::class))
     *         )
     *     )
     * )
     *
     * @Route("/user/", methods={"PATCH"})
     *
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param UserRepository $userRepository
     * @param BaseResponse $response
     *
     * @return Response
     */
    public function patch(Request $request, SerializerInterface $serializer, UserRepository $userRepository, BaseResponse $response)
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $serializer->deserialize($request->getContent(), User::class, 'json', ['object_to_populate' => $user]);

        $userRepository->save($user);

        return $response->setResponse($user)->send();
    }

    /**
     * @param TokenStorageInterface $tokenStorage
     * @param AccessTokenRepository $accessTokenRepository
     * @param RefreshTokenRepository $refreshTokenRepository
     * @param BaseResponse $baseResponse
     *
     * @return Response
     *
     * @SWG\Post(
     *     tags={"Account"},
     *     summary="Logout, завершение сессии пользователя",
     *     @SWG\Response(
     *          response=200,
     *         @SWG\JsonContent(),
     *          description="Успешный ответ сервиса",
     *     )
     * )
     *
     *          examples={
     *              "application/json":{
     *                  "status": true,
     *                  "response": null,
     *                  "errors": null
     *              }
     *          }
     * @Route("/logout/", methods={"POST"})
     */
    public function logout(TokenStorageInterface $tokenStorage, AccessTokenRepository $accessTokenRepository, RefreshTokenRepository $refreshTokenRepository, BaseResponse $baseResponse)
    {
        if (!$token = $tokenStorage->getToken()) {
            return $baseResponse
                ->statusError()
                ->addError(new ApiException('security.logout.failed'))
                ->send();
        }

        if ($token instanceof OAuthToken) {
            /** @var AccessToken $accessToken */
            $accessToken = $accessTokenRepository->findOneBy(['token' => $token->getToken()]);

            if ($accessToken) {
                $accessTokenRepository->remove($accessToken);
                $log = 'token successfully removed';
            } else {
                $log = 'not found access token in database ' . $token->getToken();
            }
        } else {
            $log = 'token not instance of ' . OAuthToken::class;
        }

        $this->logger->info(sprintf('Deactivate session with token %s %s', $token->getToken(), $log));

        return $baseResponse->statusOk()->send();
    }

    /**
     *
     * @param TokenStorageInterface $tokenStorage
     * @param Request $request
     * @param BaseResponse $response
     * @return Response
     *
     * @SWG\Post(
     *     tags={"Account"},
     *     summary="Смена пароля пользователем",
     *     @SWG\Parameter(
     *          in="formData",
     *          name="oldPassword",
     *          description="Старый пароль",
     *          @SWG\Schema(
     *              type="string"
     *          ),
     *          required=true
     *     ),
     *     @SWG\Parameter(
     *          in="formData",
     *          name="password",
     *          description="Новый пароль",
     *          @SWG\Schema(
     *              type="string"
     *          ),
     *          required=true
     *     ),
     *     @SWG\Parameter(
     *          in="formData",
     *          name="passwordConfirm",
     *          description="Подтверждение пароля",
     *          @SWG\Schema(
     *              type="string"
     *          ),
     *          required=true
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="Смена пароля произведена успешно",
     *          @SWG\JsonContent(),
     *          @Model(type=BaseResponse::class)
     *     ),
     *     @SWG\Response(
     *          response="default",
     *          description="Ошибка изменения пароля пользователя",
     *          @SWG\JsonContent(),
     *          @Model(type=BaseResponse::class)
     *     )
     * )
     *,
     *          examples={
     *              "application/json": {
     *                  "status": true
     *              }
     *          },
     *          examples={
     *              "application/json": {
     *                  "status": false,
     *                  "errors": {{"message": "Текст ошибки", "stringCode": "ERROR_STRING_CODE", ""}}
     *              }
     *          }
     * @Route("/password/", methods={"POST"})
     * @throws ApiException
     */
    public function changePassword(TokenStorageInterface $tokenStorage, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository, Request $request, BaseResponse $response): Response
    {
        /** @var User $user */
        if (!($user = $this->getUser())) {
            throw $this->createAccessDeniedException();
        }
        // get request parameters
        $oldPassword = $request->request->get('oldPassword');
        $password = $request->request->get('password');
        $passwordConfirm = $request->request->get('passwordConfirm');
        // check request parameters

        if (!$passwordEncoder->isPasswordValid($user, $oldPassword)) {
            throw new ApiException('Old password is incorrect.', 'OLD_PASSWORD_INCORRECT', null, 400);
        }

        if (!$password) {
            throw new ApiException('Password is empty.', 'EMPTY_PASSWORD_ERROR', null, 400);
        }
        if ($password !== $passwordConfirm) {
            throw new ApiException('Password confirm is wrong.', 'PASSWORD_CONFIRM_ERROR', null, 400);
        }
        // update user password
        $user->setSalt(sha1(time()));
        $user->setPassword(
            $passwordEncoder->encodePassword($user, $password)
        );
        $userRepository->save($user);
        // send response
        return $response->send();
    }

    /**
     * @Route("/password-recovery/", methods={"GET"})
     *
     * @SWG\Get(
     *     tags={"Account"},
     *     summary="Запрос на восстановление пароля по коду подтверждения",
     *     @SWG\Response(
     *          response=200,
     *          @SWG\JsonContent(),
     *          description="Успешный ответ сервиса"
     *     ),
     *     @SWG\Response(
     *          response=403,
     *          @SWG\JsonContent(),
     *          description="Ошибка авторизации"
     *     ),
     *     @SWG\Parameter(
     *          in="query",
     *          name="recipient",
     *          description="Email или SMS",
     *          @SWG\Schema(
     *              type="string"
     *          ),
     *          required=true
     *     )
     * )
     *          examples={
     *              "application/json":{
     *                  "error": "invalid_token",
     *                  "error_description": "OAuth2 authorization required"
     *              }
     *          }
     *          examples={
     *              "application/json":{
     *                  "status": true,
     *                  "response": null,
     *                  "errors": null
     *              }
     *          }
     *
     * @param Request $request
     * @param BaseResponse $response
     * @return Response
     * @throws \Exception
     */
    public function startPasswordRecovery(Request $request, TranslatorInterface $translator, BaseResponse $response, UserRepository $userRepository)
    {
        $recipient = $request->query->get('recipient');
        $isEmail = filter_var($recipient, FILTER_VALIDATE_EMAIL);

        /* @todo пока не появится функционал с SMS */
        if (!$isEmail) return $response->addError(new ApiException(
            $translator->trans('password_confirmation.not_valid_email', [], 'validators'),
            'not_valid_email',
            null,
            Response::HTTP_BAD_REQUEST
        ))->statusError()->send();

        /** @var User $user */
        $user = $userRepository->findOneBy(['email' => $recipient]);
        if (!$user) {
            return $response->addError(new ApiException(
                $translator->trans('password_confirmation.user_not_found_by_email', [], 'validators'),
                'user_not_found_by_email',
                null,
                Response::HTTP_BAD_REQUEST
            ))->statusError()->send();
        }

        $codeLength = ($isEmail) ? getenv('PASSWORD_CONFIRMATION_CODE_EMAIL_LENGTH') : getenv('PASSWORD_CONFIRMATION_CODE_SMS_LENGTH');

        $min = intval('1' . str_repeat('0', $codeLength - 1));
        $max = intval(str_repeat('9', $codeLength));
        $code = (string)mt_rand($min, $max);

        $user->setConfirmationChangePasswordCode($code);
        $user->setConfirmationChangePasswordRecipient($recipient);

        if ($isEmail) {
            /** @var EntityManager $em */
            $em = $this->get('doctrine.orm.entity_manager');
            /** @var Template $template */
            $template = $em->getRepository(Template::class)->findOneBy(['file' => 'password-recovery-code.html.twig']);
            $email = new Email();
            $email
                ->setSubject('Восстановление пароля на сайте kor-vet.ru')
                ->setEmailFrom(getenv('PASSWORD_CONFIRMATION_EMAIL_FROM'))
                ->setEmailTo($recipient)
                ->setTemplate($template)
                ->setParameters([
                    'title' => 'Восстановление пароля',
                    'code' => $code
                ]);

            // непосредственно отправляем сообщение
            $this->sendEmailService->sendEmail($email);

            $em->persist($email);
            $em->flush();
        }

        return $response->send();
    }

    /**
     * @Route("/password-recovery/", methods={"PUT"})
     *
     * @SWG\Put(
     *     tags={"Account"},
     *     summary="Запрос на изменение пароля с кодом подтверждения",
     *     @SWG\Parameter(
     *         in="body",
     *         name="request",
     *         @SWG\Schema(
     *             type="object",
     *             ref=@Model(type=PasswordRecoveryConfirmationCodeRequest::class)
     *         )
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          @SWG\JsonContent(),
     *          description="Успешный ответ сервиса"
     *     )
     * )
     *,
     *          examples={
     *              "application/json":{
     *                  "status": true,
     *                  "response": null,
     *                  "errors": null
     *              }
     *          }
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param BaseResponse $response
     *
     * @return Response
     * @throws \Exception
     *
     */
    public function processChangePassword(Request $request, TranslatorInterface $translator, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository, BaseResponse $response)
    {
        /** @var SerializerInterface $serializer */
        $serializer = $this->get('serializer');
        /** @var PasswordRecoveryConfirmationCodeRequest $requestPasswordRecovery */
        $requestPasswordRecovery = $serializer->deserialize(
            $request->getContent(),
            PasswordRecoveryConfirmationCodeRequest::class,
            'json'
        );

        /** @var User $user */
        $user = $userRepository->findOneBy(['email' => $requestPasswordRecovery->recipient]);
        if (!$user) {
            return $response->addError(new ApiException(
                $translator->trans('password_confirmation.user_not_found_by_email', [], 'validators'),
                'user_not_found_by_email',
                null,
                Response::HTTP_BAD_REQUEST
            ))->statusError()->send();
        }

        $confirmationCodeCreatedAt = $user->getConfirmationChangePasswordCodeCreatedAt();
        $minutes = (new \DateTime())->diff($confirmationCodeCreatedAt)->i;
        $expireLifetime = intval(getenv('PASSWORD_CONFIRMATION_CODE_LIFETIME'));
        if ($minutes > $expireLifetime) {
            return $response->addError(new ApiException(
                $translator->trans('password_confirmation.confirmation_code_expired', [], 'validators'),
                'confirmation_code_expired'
            ))->statusError()->send();
        }

        if ($user->getConfirmationChangePasswordCode() != $requestPasswordRecovery->code) {
            return $response->addError(new ApiException(
                $translator->trans('password_confirmation.incorrect_confirmation_code', [], 'validators'),
                'incorrect_confirmation_code'
            ))->statusError()->send();
        }

        if ($requestPasswordRecovery->newPassword !== $requestPasswordRecovery->newPasswordConfirm) {
            return $response->addError(new ApiException(
                $translator->trans('password_confirmation.incorrect_confirmation_password', [], 'validators'),
                'incorrect_confirmation_password'
            ))->statusError()->send();
        }

        $newPassword = $passwordEncoder->encodePassword($user, $requestPasswordRecovery->newPassword);
        $user->setPassword($newPassword);
        $user->setConfirmationChangePasswordCode(null);

        $em = $this->get('doctrine.orm.entity_manager');
        $em->persist($user);
        $em->flush();

        return $response->send();
    }

    /**
     * @Route("/check-confirmation-code/", methods={"POST"})
     *
     * @SWG\Post(
     *     tags={"Account"},
     *     summary="Проверка правильности кода подтверждения",
     *     @SWG\Parameter(
     *         in="body",
     *         name="request",
     *         @SWG\Schema(
     *             type="object",
     *             ref=@Model(type=CheckConfirmationCodeRequest::class)
     *         )
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          @SWG\JsonContent(),
     *          description="Успешный ответ сервиса",
     *     )
     * )
     *
     *          examples={
     *              "application/json":{
     *                  "status": true,
     *                  "response": null,
     *                  "errors": null
     *              }
     *          }
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param BaseResponse $response
     *
     * @return Response
     * @throws \Exception
     *
     */
    public function checkConfirmationCode(Request $request, TranslatorInterface $translator, UserRepository $userRepository, BaseResponse $response)
    {
        /** @var SerializerInterface $serializer */
        $serializer = $this->get('serializer');
        /** @var PasswordRecoveryConfirmationCodeRequest $requestPasswordRecovery */
        $requestPasswordRecovery = $serializer->deserialize(
            $request->getContent(),
            CheckConfirmationCodeRequest::class,
            'json'
        );

        /** @var User $user */
        $user = $userRepository->findOneBy(['email' => $requestPasswordRecovery->recipient]);
        if (!$user) {
            return $response->addError(new ApiException(
                $translator->trans('password_confirmation.user_not_found_by_email', [], 'validators'),
                'user_not_found_by_email',
                null,
                Response::HTTP_BAD_REQUEST
            ))->statusError()->send();
        }

        $confirmationCodeCreatedAt = $user->getConfirmationChangePasswordCodeCreatedAt();
        $minutes = (new \DateTime())->diff($confirmationCodeCreatedAt)->i;
        $expireLifetime = intval(getenv('PASSWORD_CONFIRMATION_CODE_LIFETIME'));
        if ($minutes > $expireLifetime) {
            return $response->addError(new ApiException(
                $translator->trans('password_confirmation.confirmation_code_expired', [], 'validators'),
                'confirmation_code_expired'
            ))->statusError()->send();
        }

        if ($user->getConfirmationChangePasswordCode() != $requestPasswordRecovery->code) {
            return $response->addError(new ApiException(
                $translator->trans('password_confirmation.incorrect_confirmation_code', [], 'validators'),
                'incorrect_confirmation_code'
            ))->statusError()->send();
        }

        return $response->send();
    }
}

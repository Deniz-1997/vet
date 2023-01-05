<?php

namespace App\Packages\Security\Authenticator\Guard;

use App\Model\User;
use ParagonIE\Paseto\Exception\PasetoException;
use SodiumException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use App\Packages\Security\Encryptor;
use App\Packages\Response\BaseResponse;

/**
 * Class SwaggerPasetoGuardAuthenticator
 */
class SwaggerPasetoGuardAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var Encryptor
     */
    private Encryptor $encryptor;

    /**
     * @var BaseResponse
     */
    private BaseResponse $responseBuilder;

    /**
     * SwaggerPasetoGuardAuthenticator constructor.
     * @param Encryptor $encryptor
     * @param BaseResponse $responseBuilder
     */
    public function __construct (Encryptor $encryptor, BaseResponse $responseBuilder)
    {
        $this->encryptor = $encryptor;
        $this->responseBuilder = $responseBuilder;
    }

    /**
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return null|Response
     */
    public function start(Request $request, AuthenticationException $authException = null): ?Response
    {
        return null;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request): bool
    {
        return $request->headers->has('Authorization');
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getCredentials(Request $request): string
    {
        return $request->headers->get('Authorization');
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return null|UserInterface|User
     * @throws PasetoException
     * @throws SodiumException
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $authorizationData = $this->encryptor->decryptAuthenticationInformation($credentials);

        if (!$authorizationData) {
            return null;
        }

        return $authorizationData->getUser();
    }

    /**
     * @param mixed $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        return $this->responseBuilder
                    ->setHttpResponseCode(401)
                    ->setErrors(['Paseto token is invalid'])
                    ->send();
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return null|Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        return null;
    }

    /**
     * @return bool
     */
    public function supportsRememberMe(): bool
    {
        return false;
    }
}

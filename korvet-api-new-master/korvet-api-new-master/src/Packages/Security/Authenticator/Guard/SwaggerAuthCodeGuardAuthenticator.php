<?php

namespace App\Packages\Security\Authenticator\Guard;

use App\Model\Env;
use App\Packages\Client\AccountClient;
use App\Packages\Client\OAuthClient;
use App\Model\User;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use App\Model\AuthenticationInformation;
use App\Packages\Response\BaseResponse;

/**
 * Class SwaggerAuthCodeGuardAuthenticator
 */
class SwaggerAuthCodeGuardAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var AccountClient
     */
    private AccountClient $accountClient;

    /**
     * @var OAuthClient
     */
    private OAuthClient $oauthClient;

    /**
     * @var BaseResponse
     */
    private BaseResponse $response;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * SwaggerAuthCodeGuardAuthenticator constructor.
     *
     * @param AccountClient   $accountClient
     * @param BaseResponse    $response
     * @param Router          $router
     * @param OAuthClient     $client
     * @param LoggerInterface $logger
     */
    public function __construct(AccountClient $accountClient, BaseResponse $response, Router $router, OAuthClient $client, LoggerInterface $logger)
    {
        $this->accountClient = $accountClient;
        $this->response = $response;
        $this->router = $router;
        $this->oauthClient = $client;
        $this->logger = $logger;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request): bool
    {
        return $request->getSession() && $request->getSession()->has('_swagger_oauth_data');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getCredentials(Request $request): array
    {
        if($credentials = $request->getSession()->get('_swagger_oauth_data')) {
            $credentials = json_decode($credentials, true) ?: [];
        } else {
            $credentials = [];
        }

        if(empty($credentials['access_token'])) {
            return [];
        }

        $token = $credentials['access_token'];
        try {
            $authenticationData = $this->accountClient->getCurrentAuthenticationInfo($token);
            $this->logger->debug('Successfully authorization by access token');
        } catch (Exception $exception) {
            $this->logger->debug('Error authorization by access token: '.$exception->getMessage().' trying refresh');

            try {
                $oauthResponse = $this->oauthClient->refreshAccessToken($credentials['refresh_token']);
                $authenticationData = $this->accountClient->getCurrentAuthenticationInfo($oauthResponse->getAccessToken());

                $tokenInfo = [
                    'access_token' => $oauthResponse->getAccessToken(),
                    'expires_in' => $oauthResponse->getExpiredAt(),
                    'token_type' => $oauthResponse->getTokenType(),
                    'scope' => $oauthResponse->getScope(),
                    'refresh_token' => $oauthResponse->getRefreshToken(),
                ];
                $request->getSession()->set('_swagger_oauth_data', json_encode($tokenInfo));

                $this->logger->debug('Successfully refresh token');
            } catch (Exception $exception) {
                $this->logger->debug('Error refresh token: '.$exception->getMessage().'. Clean session...');
                $request->getSession()->remove('_swagger_oauth_data');

                return []; //Refresh error
            }
        }

        return [
            'authentication_info' => $authenticationData,
        ];
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return null|UserInterface|User
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (!isset($credentials['authentication_info']) || !$credentials['authentication_info'] instanceof AuthenticationInformation) {
            return null;
        }

        return $credentials['authentication_info']->getUser();
    }

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
        return RedirectResponse::create(
            $this->formAuthorizationURL($request->getScheme())
        );
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

    /**
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return Response
     */
    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return RedirectResponse::create(
            $this->formAuthorizationURL($request->getScheme())
        );
    }

    /**
     * @param $scheme
     * @return string
     */
    private function formAuthorizationURL($scheme): string
    {
        return sprintf(
            '%s/api/oauth/v2/auth?response_type=code&client_id=%s&redirect_uri=%s',
            Env::getenv('SECURITY_ADDRESS'),
            Env::getenv('MICROSERVICE_CLIENT_ID'),
            $scheme.':'.$this->router->generate('webslon_auth.swagger.oauth_redirect', [], UrlGeneratorInterface::NETWORK_PATH)
        );
    }
}

<?php

namespace App\Packages\Security;

use App\Packages\Client\OAuthClient;
use App\Packages\Response\OAuthResponse;
use BadMethodCallException;
use Exception;
use Redis;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\RememberMeToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * OAuthTokenManager.
 */
class OAuthTokenManager
{
    const ACCESS_TOKEN_KEY = 'access-token';

    const ACCESS_REFRESH_TOKEN_KEY = 'refresh-token';

    const ACCESS_TOKEN_EXPIRES_IN_KEY = 'expires-in';

    const ACCESS_TOKEN_EXPIRED_AT_KEY = 'expired-at';

    /** @var OAuthClient */
    private OAuthClient $oAuthClient;

    /** @var TokenStorage */
    private TokenStorage $tokenStorage;

    /** @var Redis */
    private Redis $redisClient;

    /** @var string */
    private string $accessToken;

    /** @var string */
    private string $refreshToken;

    /**
     * This variable used as local storage for api refresh token response, when token is refreshed from remember me
     * listener
     *
     * @var OAuthResponse
     */
    private OAuthResponse $refreshedOAuthResponse;

    /** @var UsernamePasswordToken */
    private UsernamePasswordToken $tokenAfterRefresh;

    /**
     * @param OAuthClient  $oAuthClient
     * @param TokenStorage $tokenStorage
     * @param Redis $redisClient
     */
    public function __construct(OAuthClient $oAuthClient, TokenStorage $tokenStorage, Redis $redisClient)
    {
        $this->oAuthClient = $oAuthClient;
        $this->tokenStorage = $tokenStorage;
        $this->redisClient = $redisClient;
    }

    /**
     * @return mixed|null|string
     *
     * @throws Exception
     */
    public function getAccessToken(): ?string
    {
        $token = $this->tokenStorage->getToken();
        $accessToken = null;

        if ($token instanceof UsernamePasswordToken || $token instanceof RememberMeToken) {
            $accessToken = $token->getAttribute(self::ACCESS_TOKEN_KEY);
        } elseif (null !== $this->accessToken) {
            $accessToken = $this->accessToken;
        } else {
            $accessToken = $this->getAccessTokenForClient();
        }

        return $accessToken;
    }

    /**
     * @throws Exception
     */
    public function refreshAccessToken()
    {
        $token = $this->tokenStorage->getToken();

        if ($token instanceof UsernamePasswordToken || $token instanceof RememberMeToken) {
            $refreshToken = $token->getAttribute(self::ACCESS_REFRESH_TOKEN_KEY);
            $oAuthToken = $this->oAuthClient->refreshAccessToken($refreshToken);

            $token->setAttribute(self::ACCESS_TOKEN_KEY, $oAuthToken->getAccessToken());
            $token->setAttribute(self::ACCESS_REFRESH_TOKEN_KEY, $oAuthToken->getRefreshToken());
            $token->setAttribute(self::ACCESS_TOKEN_EXPIRES_IN_KEY, $oAuthToken->getExpiresIn());
            $token->setAttribute(self::ACCESS_TOKEN_EXPIRED_AT_KEY, $oAuthToken->getExpiredAt());

            $this->forceToken($oAuthToken->getAccessToken());
            $this->forceRefreshToken($oAuthToken->getRefreshToken());
            $this->tokenAfterRefresh = $token;
        } elseif ($token instanceof AnonymousToken) {
            $this->getAccessTokenForClient(true);
        } elseif (null === $token && $this->refreshToken) {
            $this->refreshedOAuthResponse = $this->oAuthClient->refreshAccessToken($this->refreshToken);

            $this->forceToken($this->refreshedOAuthResponse->getAccessToken());
            $this->forceRefreshToken($this->refreshedOAuthResponse->getRefreshToken());
        } else {
            throw new BadMethodCallException('Unsupported token for OAuthTokenManager');
        }
    }

    /**
     * Sets force access token when authentication token not created.
     *
     * @param string $accessToken
     */
    public function forceToken(string $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @param string $refreshToken
     */
    public function forceRefreshToken(string $refreshToken)
    {
        $this->refreshToken = $refreshToken;
    }

    /**
     * @return OAuthResponse|null
     */
    public function getRefreshedOAuthResponse(): ?OAuthResponse
    {
        $response = $this->refreshedOAuthResponse;
        $this->refreshedOAuthResponse = null;

        return $response;
    }

    /**
     * @return UsernamePasswordToken
     */
    public function getTokenAfterRefresh(): UsernamePasswordToken
    {
        return $this->tokenAfterRefresh;
    }

    /**
     * @param boolean $forceRefresh If true a new token will be generated and saved to redis.
     *
     * @return string
     * @throws Exception
     */
    public function getAccessTokenForClient($forceRefresh = false): string
    {
        $token = $this->redisClient->get(self::ACCESS_TOKEN_KEY);

        if ($token && !$forceRefresh) {
            return $token;
        }

        $oAuthResponse = $this->oAuthClient->getAccessTokenForClient();
        $this->redisClient->setex(self::ACCESS_TOKEN_KEY, $oAuthResponse->getExpiresIn(), $oAuthResponse->getAccessToken());

        return $oAuthResponse->getAccessToken();
    }
}

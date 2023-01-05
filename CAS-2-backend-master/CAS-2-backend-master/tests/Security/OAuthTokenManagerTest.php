<?php

namespace App\Tests\Security;

use PHPUnit\Framework\TestCase;
use App\Packages\Security\OAuthTokenManager;
use App\Endpoint\Client\OAuthClient;
use App\Endpoint\Client\OAuthResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class OAuthTokenManagerTest.
 */
class OAuthTokenManagerTest extends TestCase
{
    const TEST_ACCESS_TOKEN = 'test-access-token';
    const TEST_REFRESH_TOKEN = 'test-refresh-token';

    /**
     * Test retrieving access token
     */
    public function testGetAccessToken()
    {
        $this->clientSuccessAccessToken();
        $this->clientSuccessAccessTokenFromRedis();
    }

    /**
     * Test refreshing access token
     */
    public function testRefreshAccessToken()
    {
        $this->refreshSuccessUserAccessToken();
        $this->refreshForceAccessToken();
        $this->refreshFailureUserAccessToken();
    }

    /**
     * Test set token by force
     */
    public function testSetForceToken()
    {
        $oAuthClient = $this->getOAuthClient();
        $oAuthClient
            ->expects($this->never())
            ->method('getAccessTokenForClient')
        ;

        $tokenStorage = $this->createMock(TokenStorage::class);
        $tokenStorage
            ->expects($this->once())
            ->method('getToken')
            ->willReturn(self::TEST_ACCESS_TOKEN)
        ;

        $redisClient = $this->getRedisClient();
        $redisClient
            ->expects($this->never())
            ->method(Request::METHOD_GET)
            ->with(OAuthTokenManager::ACCESS_TOKEN_KEY)
        ;

        $redisClient
            ->expects($this->never())
            ->method('setEx')
        ;

        $oAuthTokenManager = new OAuthTokenManager($oAuthClient, $tokenStorage, $redisClient);
        $oAuthTokenManager->forceToken(self::TEST_ACCESS_TOKEN);

        $this->assertEquals(self::TEST_ACCESS_TOKEN, $oAuthTokenManager->getAccessToken());
    }

    /**
     * Test success retrieving access token for client
     *
     * @throws \Exception
     */
    public function clientSuccessAccessToken()
    {
        $oAuthResponse = new OAuthResponse();
        $oAuthResponse
            ->setAccessToken(self::TEST_ACCESS_TOKEN)
            ->setExpiresIn(20)
        ;

        $oAuthClient = $this->getOAuthClient();
        $oAuthClient
            ->expects($this->once())
            ->method('getAccessTokenForClient')
            ->willReturn($oAuthResponse)
        ;

        $tokenStorage = $this->createMock(TokenStorage::class);
        $tokenStorage
            ->expects($this->once())
            ->method('getToken')
            ->willReturn(null)
        ;

        $redisClient = $this->getRedisClient();
        $redisClient
            ->expects($this->once())
            ->method(Request::METHOD_GET)
            ->with($this->equalTo(OAuthTokenManager::ACCESS_TOKEN_KEY))
            ->willReturn(null)
        ;

        $redisClient
            ->expects($this->once())
            ->method('setEx')
            ->with(OAuthTokenManager::ACCESS_TOKEN_KEY, $oAuthResponse->getExpiresIn(), $oAuthResponse->getAccessToken())
        ;

        $oAuthTokenManager = new OAuthTokenManager($oAuthClient, $tokenStorage, $redisClient);
        $oAuthTokenManager->getAccessToken();
    }

    /**
     * Test success retrieving access token fro redis for client
     */
    public function clientSuccessAccessTokenFromRedis()
    {
        $tokenStorage = $this->createMock(TokenStorage::class);
        $tokenStorage
            ->expects($this->once())
            ->method('getToken')
            ->willReturn(null)
        ;

        $redisClient = $this->getRedisClient();
        $redisClient
            ->expects($this->once())
            ->method(Request::METHOD_GET)
            ->with(OAuthTokenManager::ACCESS_TOKEN_KEY)
            ->willReturn(self::TEST_ACCESS_TOKEN)
        ;

        $redisClient
            ->expects($this->never())
            ->method('setEx')
            ->withAnyParameters()
        ;

        $oAuthTokenManager = new OAuthTokenManager($this->getOAuthClient(), $tokenStorage, $redisClient);
        $oAuthTokenManager->getAccessToken();
    }

    /**
     *  Test success retrieving user token
     */
    public function refreshSuccessUserAccessToken()
    {
        $newAccessToken = 'new-access-token';
        $newRefreshToken = 'new-refresh-token';

        $userToken = new UsernamePasswordToken('user', 'password', 'main');
        $userToken->setAttribute(OAuthTokenManager::ACCESS_REFRESH_TOKEN_KEY, self::TEST_REFRESH_TOKEN);

        $oAuthResponse = new OAuthResponse();
        $oAuthResponse
            ->setAccessToken($newAccessToken)
            ->setExpiresIn(20)
            ->setRefreshToken($newRefreshToken)
        ;

        $oAuthClient = $this->getOAuthClient();
        $oAuthClient
            ->expects($this->once())
            ->method('refreshAccessToken')
            ->with(self::TEST_REFRESH_TOKEN)
            ->willReturn($oAuthResponse)
        ;

        $tokenStorage = $this->createMock(TokenStorage::class);
        $tokenStorage
            ->expects($this->once())
            ->method('getToken')
            ->willReturn($userToken)
        ;

        $oAuthTokenManager = new OAuthTokenManager($oAuthClient, $tokenStorage, $this->getRedisClient());
        $oAuthTokenManager->refreshAccessToken();
    }

    /**
     * Tests token refreshing got client
     */
    public function refreshForceAccessToken()
    {
        $newAccessToken = 'new-access-token';
        $anonymousToken = new AnonymousToken('test-secret', 'anon.');

        $oAuthResponse = new OAuthResponse();
        $oAuthResponse
            ->setAccessToken($newAccessToken)
            ->setExpiresIn(20)
        ;

        $oAuthClient = $this->getOAuthClient();
        $oAuthClient
            ->expects($this->once())
            ->method('getAccessTokenForClient')
            ->willReturn($oAuthResponse)
        ;

        $tokenStorage = $this->createMock(TokenStorage::class);
        $tokenStorage
            ->expects($this->once())
            ->method('getToken')
            ->willReturn($anonymousToken)
        ;

        $redisClient = $this->getRedisClient();
        $redisClient
            ->expects($this->once())
            ->method(Request::METHOD_GET)
            ->with(OAuthTokenManager::ACCESS_TOKEN_KEY)
            ->willReturn(self::TEST_ACCESS_TOKEN)
        ;

        $redisClient
            ->expects($this->once())
            ->method('setEx')
            ->with(OAuthTokenManager::ACCESS_TOKEN_KEY, $oAuthResponse->getExpiresIn(), $oAuthResponse->getAccessToken())
        ;

        $oAuthTokenManager = new OAuthTokenManager($oAuthClient, $tokenStorage, $redisClient);
        $oAuthTokenManager->refreshAccessToken();
    }

    /**
     * Test if access token retrieving is failed
     *
     * @throws \Exception
     */
    public function refreshFailureUserAccessToken()
    {
        $unknownToken = new \stdClass();

        $tokenStorage = $this->createMock(TokenStorage::class);
        $tokenStorage
            ->expects($this->once())
            ->method('getToken')
            ->willReturn($unknownToken)
        ;

        $this->expectException(\Exception::class);

        $oAuthTokenManager = new OAuthTokenManager($this->getOAuthClient(), $tokenStorage, $this->getRedisClient());
        $oAuthTokenManager->refreshAccessToken();
    }

    /**
     * Creates mock object for OAuthClient class
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|OAuthClient
     */
    private function getOAuthClient()
    {
        return $this
            ->getMockBuilder(OAuthClient::class)
            ->disableOriginalConstructor()
            ->setMethods(['refreshAccessToken', 'getAccessTokenForClient'])
            ->getMock()
            ;
    }

    /**
     * Creates mock object for \Redis class
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Redis
     */
    private function getRedisClient()
    {
        return $this
            ->getMockBuilder(\Redis::class)
            ->disableOriginalConstructor()
            ->setMethods([Request::METHOD_GET, 'setex'])
            ->getMock()
            ;
    }
}

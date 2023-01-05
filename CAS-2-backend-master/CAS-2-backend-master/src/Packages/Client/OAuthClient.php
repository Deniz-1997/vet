<?php

namespace App\Packages\Client;

use App\Packages\Debug\DebugAwareTrait;
use App\Packages\Response\OAuthResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CredentialsExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * OAuthClient.
 */
class OAuthClient
{
    use DebugAwareTrait;

    const OAUTH2_TOKEN_URL = 'oauth/v2/token/';
    const OPTIONS_TIMEOUT = 30;

    /** @var string */
    private $clientId;

    /** @var string */
    private $clientSecret;

    /** @var string */
    private $scope;

    /** @var GuzzleClient */
    private $httpClient;

    /** @var TranslatorInterface */
    private $translator;

    /**
     * @param Client $httpClient HTTP client
     * @param string $clientId OAuth client ID
     * @param string $clientSecret OAuth client secret
     * @param string $scope OAuth scope
     * @param TranslatorInterface $translator translator
     */
    public function __construct(
        Client $httpClient,
        $clientId,
        $clientSecret,
        $scope,
        TranslatorInterface $translator
    )
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->scope = $scope;
        $this->httpClient = $httpClient;
        $this->translator = $translator;
    }

    /**
     * @return OAuthResponse
     *
     * @throws \Exception
     */
    public function getAccessTokenForClient()
    {
        $formParams = [
            'grant_type' => 'client_credentials',
            'scope' => $this->scope,
        ];

        $content = $this->makePostRequest($formParams);

        return (new OAuthResponse())
            ->setAccessToken($content['access_token'])
            ->setTokenType($content['token_type'])
            ->setScope($content['scope'])
            ->setExpiresIn($content['expires_in']);
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return OAuthResponse
     *
     * @throws \Exception
     */
    public function getAccessTokenForUser($username, $password)
    {
        $formParams = [
            'grant_type' => 'password',
            'username' => $username,
            'password' => $password,
            'scope' => $this->scope,
        ];

        $content = $this->makePostRequest($formParams);

        $oauthResponse = (new OAuthResponse())
            ->setAccessToken($content['access_token'])
            ->setTokenType($content['token_type'])
            ->setRefreshToken($content['refresh_token'])
            ->setExpiresIn($content['expires_in']);

        if (isset($content['scope'])) {
            $oauthResponse->setScope($content['scope']);
        }

        return $oauthResponse;
    }

    /**
     * @param string $refreshToken
     *
     * @return OAuthResponse
     */
    public function refreshAccessToken($refreshToken)
    {
        $formParams = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'scope' => $this->scope,
        ];

        try {
            $content = $this->makePostRequest($formParams);
        } catch (AuthenticationException $exception) {
            throw new CredentialsExpiredException();
        }

        $oauthResponse = (new OAuthResponse())
            ->setAccessToken($content['access_token'])
            ->setTokenType($content['token_type'])
            ->setRefreshToken($content['refresh_token'])
            ->setExpiresIn($content['expires_in']);

        if (isset($content['scope'])) {
            $oauthResponse->setScope($content['scope']);
        }

        return $oauthResponse;
    }

    /**
     * @param array $formParams
     *
     * @return array
     *
     * @throws \Exception
     */
    private function makePostRequest(array $formParams)
    {
        $baseFormParams = [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
            ],
        ];

        $options = array_merge_recursive($baseFormParams, ['form_params' => $formParams]);
        $options['timeout'] = self::OPTIONS_TIMEOUT;

        try {
            $response = $this->httpClient->post('', $options);
        } catch (ClientException $e) {
            $response = $e->getResponse();
        }

        if ($response->hasHeader('Content-Type') && 'application/json' === $response->getHeader('Content-Type')[0]) {
            $content = $this->decodeJsonResponse($response);
        } else {
            $this->logger->warning($response->getBody()->getContents());

            $headers = [];
            foreach ($response->getHeaders() as $name => $values) {
                $headers[] = $name . ':' . implode(',', $values);
            }

            $this->logger->debug(implode(PHP_EOL, $headers));
            throw new \Exception('OAuth response is not a JSON.');
        }

        if ($response->getStatusCode() != Response::HTTP_OK) {
            $this->logger->warning('Incorrect security response: ' . is_array($content) ? json_encode($content) : $content);

            throw new CustomUserMessageAuthenticationException($this->formatMessage($content));
        }

        return $content;
    }

    /**
     * @param $responseContent
     *
     * @return string
     */
    private function formatMessage($responseContent)
    {
        $translationKey = 'error.authentication.' . $responseContent['error'];

        return $this->translator->trans($translationKey);
    }

    /**
     * @param ResponseInterface $response
     *
     * @return array
     */
    private function decodeJsonResponse(ResponseInterface $response)
    {
        $content = $response->getBody()->getContents();

        return json_decode($content, true);
    }
}

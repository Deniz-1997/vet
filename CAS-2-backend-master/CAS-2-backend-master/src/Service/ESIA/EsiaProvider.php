<?php

namespace App\Service\ESIA;

use InvalidArgumentException;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\Plain;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\HttpClient;
use Ramsey\Uuid\Uuid;
use App\Exception\ApiException;
use \Exception;

class EsiaProvider
{
    /**
     * @var HttpClientInterface
     */
    protected HttpClientInterface $httpClientInterface;

    protected $defaultScopes = ['openid', 'fullname'];
    protected $remoteUrl = 'https://esia.gosuslugi.ru';
    protected $clientId = '';
    protected $redirectUrl = '';

    /**
     * @var OpensslPkcs7
     */
    private $signer;

    /**
     * @var JoseEncoder
     */
    private $encoder;

    public function __construct(array $options = [], array $collaborators = [])
    {
        $this->httpClientInterface = HttpClient::create(['verify_peer' => false, 'verify_host' => false]);

        if (isset($options['remoteUrl'])) {
            $this->remoteUrl = $options['remoteUrl'];
        }

        if (isset($options['defaultScopes'])) {
            $this->defaultScopes = $options['defaultScopes'];
        }

        if (isset($options['clientId'])) {
            $this->clientId = $options['clientId'];
        }

        if (isset($options['redirectUri'])) {
            $this->redirectUrl = $options['redirectUri'];
        }

        if (isset($options['remoteCertificatePath'])) {
            $options['remotePublicKey'] = $options['remoteCertificatePath'];
        }

        if (!filter_var($this->remoteUrl, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Remote URL is not provided!');
        }

        if (isset($collaborators['signer']) && $collaborators['signer'] instanceof OpensslPkcs7) {
            $this->signer = $collaborators['signer'];
            $this->encoder = new JoseEncoder();
        } else {
            throw new InvalidArgumentException('Signer is not provided!');
        }
    }

    private function getBaseAuthorizationUrl()
    {
        return $this->getUrl('/aas/oauth2/ac');
    }

    private function getAuthorizationParameters()
    {
        $options = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUrl,
            'scope' => implode(' ', $this->defaultScopes),
            'state' => $this->generateState(),
            'timestamp' => $this->getTimeStamp(),
        ];

        return $this->withClientSecret($options);
    }

    private function withClientSecret(array $params)
    {
        $message = $params['scope'] . $params['timestamp'] . $params['client_id'] . $params['state'];
        $signature = $this->signer->sign($message);
        $params['client_secret'] = $this->encoder->base64UrlEncode($signature);

        return $params;
    }

    private function getRandomState($length = 32)
    {
        return Uuid::uuid4()->toString();
    }

    private function generateState()
    {
        return $this->getRandomState();
    }

    private function getTimeStamp()
    {
        return date('Y.m.d H:i:s O');
    }

    private function getBaseAccessTokenUrl()
    {
        return $this->getUrl('/aas/oauth2/te');
    }

    private function getResourceOwnerDetailsUrl(string $ownerId)
    {
        return $this->getUrl('/rs/prns/' . $ownerId);
    }

    private function getResourceOwnerContactsUrl(string $ownerId)
    {
        return $this->getUrl('/rs/prns/' . $ownerId . '/ctts');
    }

    private function getOwnerOrganisationUrl(string $ownerId)
    {
        return $this->getUrl("/rs/prns/{$ownerId}/roles");
    }

    private function getUrl($path)
    {
        return $this->remoteUrl . $path;
    }

    private function buildQueryString(array $params)
    {
        return http_build_query($params, '', '&', \PHP_QUERY_RFC3986);
    }

    private function getOwnerId(string $token)
    {
        /** @var Plain */
        $parsedToken = (new Parser($this->encoder))->parse($token);
        $resourceOwnerId = $parsedToken->claims()->get('urn:esia:sbj_id');
        return $resourceOwnerId;
    }

    public function getAuthorizationUrl()
    {
        $base   = $this->getBaseAuthorizationUrl();
        $params = $this->getAuthorizationParameters();
        $params['access_type'] = 'online';
        $params['response_type'] = 'code';
        $query  = $this->buildQueryString($params);

        return "{$base}?{$query}";
    }

    public function getAccessToken(string $code)
    {
        $base   = $this->getBaseAccessTokenUrl();
        $params = $this->getAuthorizationParameters();
        $params['grant_type'] = 'authorization_code';
        $params['code'] = $code;
        $params['token_type'] = 'Bearer';
        $query  = $this->buildQueryString($params);

        $requestUrl = "{$base}?{$query}";
        try {
            $response = $this->httpClientInterface->request(
                'POST',
                $requestUrl
            );
            if ($response) {
                $content = json_decode($response->getContent(), true);
                if ($content != null && $content["access_token"]) {
                    return $content["access_token"];
                }
            }
        } catch (Exception $e) {
            throw new ApiException($e->getMessage(), 'ESIA_ERROR', null, 400);
        }
    }

    public function getOwnerInformation(string $token)
    {
        $ownerId = $this->getOwnerId($token);

        $requestUrl = $this->getResourceOwnerDetailsUrl($ownerId);
        try {
            $response = $this->httpClientInterface->request(
                'GET',
                $requestUrl,
                [
                    'headers' => [
                        'Authorization' => "Bearer {$token}"
                    ]
                ]
            );
            if ($response) {
                $content = json_decode($response->getContent(), true);
                if ($content != null) {
                    $content['contacts'] = $this->getOwnerContacts($token);
                    $content['resourceOwnerId'] = $ownerId;
                    return $content;
                }
            }
        } catch (Exception $e) {
            throw new ApiException($e->getMessage(), 'ESIA_ERROR', null, 400);
        }
    }

    public function getOwnerContacts(string $token)
    {
        $ownerId = $this->getOwnerId($token);

        $requestUrl = $this->getResourceOwnerContactsUrl($ownerId);
        try {
            $response = $this->httpClientInterface->request(
                'GET',
                $requestUrl,
                [
                    'headers' => [
                        'Authorization' => "Bearer {$token}"
                    ]
                ]
            );
            if ($response) {
                $content = json_decode($response->getContent(), true);
                if (isset($content) && isset($content["elements"])) {
                    $contacts = [];
                    foreach ($content['elements'] as $url) {
                        array_push($contacts, $this->getOwnerContactByUrl($url, $token));
                    }
                    $content['elements'] = $contacts;
                }
                if ($content != null) {
                    return $content;
                }
            }
        } catch (Exception $e) {
            throw new ApiException($e->getMessage(), 'ESIA_ERROR', null, 400);
        }
    }

    private function getOwnerContactByUrl(string $url, string $token)
    {
        try {
            $response = $this->httpClientInterface->request(
                'GET',
                $url,
                [
                    'headers' => [
                        'Authorization' => "Bearer {$token}"
                    ]
                ]
            );
            if ($response) {
                $content = json_decode($response->getContent(), true);
                return $content;
            }
        } catch (Exception $e) {
            throw new ApiException($e->getMessage(), 'ESIA_ERROR', null, 400);
        }
    }

    public function getOwnerOrganization(string $esiaToken)
    {
        $ownerId = $this->getOwnerId($esiaToken);
        $requestUrl = $this->getOwnerOrganisationUrl($ownerId);
        try {

            $response = $this->httpClientInterface->request(
                'GET',
                $requestUrl,
                [
                    'headers' => ['Authorization' => 'Bearer ' . $esiaToken],
                ],
            );
            if ($response) {
                $content = json_decode($response->getContent(), true);
                if ($content != null) {
                    return $content;
                }
            }
        } catch (Exception $e) {
            throw new ApiException($e->getMessage(), 'ESIA_ERROR', null, 400);
        }
    }
}

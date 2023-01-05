<?php

namespace App\Service\CasApi;

use App\Entity\ApiData\ApiToken;
use App\Interfaces\CAS\CasApiAuthInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Packages\DBAL\Types\TokenTypeEnum;
use App\Repository\ApiData\ApiTokenRepository;
use App\Exception\ApiException;

class ApiAuthService extends ApiBaseConnector implements CasApiAuthInterface
{
    public string $serverCode = 'CAS';
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManagerInterface;

    /**
     * @var ApiTokenRepository
     */
    private ApiTokenRepository $apiTokenRepository;


    public function __construct(EntityManagerInterface $entityManagerInterface, ApiTokenRepository $apiTokenRepository)
    {
        $this->entityManagerInterface = $entityManagerInterface;
        $this->apiTokenRepository = $apiTokenRepository;
        $this->initialize();
    }

    public function getAuthToken(bool $refresh = false): ?string
    {
        if ($refresh == false) {
            /** @var ApiToken */
            $token = $this->apiTokenRepository->findOneBy(['name' => $this->serverCode], ['id' => 'DESC']);
            if ($token != null) {
                return $token->getAccessToken();
            }
        }
        $body = [
            "grant_type" => "password",
            "username" => $this->apiLogin,
            "password" => $this->apiPassword
        ];
        if ($this->apiClientId != null) {
            $body["client_id"] = $this->apiClientId;
        }
        if ($this->casApiClientSecret != null) {
            $body["client_secret"] = $this->casApiClientSecret;
        }
        return $this->getToken($body);
    }

    public function refreshAuthToken(): ?string
    {
        $accessToken = null;
        /** @var ApiToken */
        $token = $this->apiTokenRepository->findOneBy(['name' => $this->serverCode], ['id' => 'DESC']);
        if ($token != null) {
            $body = [
                "grant_type" => "refresh_token",
                "refresh_token" =>  $token->getRefreshToken()
            ];
            if ($this->apiClientId != null) {
                $body["client_id"] = $this->apiClientId;
            }
            if ($this->casApiClientSecret != null) {
                $body["client_secret"] = $this->casApiClientSecret;
            }
            $accessToken = $this->getToken($body);
        }

        if ($accessToken == null) {
            $accessToken = $this->getAuthToken(true);
        }
        if ($accessToken == null) {
            throw new ApiException('Не удалось авторизоваться на сервере', 'NOT_AUTHORIZED', null, 400);
        }

        return $accessToken;
    }

    private function getToken($body)
    {
        if (!$this->apiAuthUrl || !$this->apiLogin || !$this->apiPassword) {
            throw new ApiException("Не заполнены данные подключения", 'SETTINGS_ERROR', null, 400);
        }

        $response = $this->httpClientInterface->request(
            'POST',
            $this->apiAuthUrl,
            [
                'headers' => [
                    'User-Agent' => 'PHP console app',
                    'Content-type' => 'application/json',
                    'Host' => array_key_exists('HTTP_HOST', $_SERVER) ? $_SERVER['HTTP_HOST'] : 'localhost',
                    'Content-Length' =>strlen(json_encode($body))
                ],
                'body' => json_encode($body) 
            ]
        );

        if ($response) {
            if ($response->getStatusCode() == 400) {
                return null;
            }
            $content = json_decode($response->getContent(), true);
            if (isset($content['access_token']) && isset($content["refresh_token"]) && isset($content["token_type"])) {
                $token = new ApiToken();
                $token->setAccessToken($content['access_token']);
                $token->setRefreshToken($content['refresh_token']);
                $token->setTokenType($this->getTokenType($content['token_type']));
                $token->setExpiredIn((new \DateTime($content['expires_in'])));
                $token->setName($this->serverCode);

                $this->entityManagerInterface->persist($token);
                $this->entityManagerInterface->flush();

                return $token->getAccessToken();
            }
        }
        return null;
    }

    private function getTokenType(string $tokenType): TokenTypeEnum
    {
        $tt = strtoupper($tokenType);
        switch (strtoupper($tokenType)) {
            case 'BEARAR':
                return TokenTypeEnum::getItem(TokenTypeEnum::BEARER);
            default:
                return TokenTypeEnum::getItem(TokenTypeEnum::BEARER);
        }
    }
}

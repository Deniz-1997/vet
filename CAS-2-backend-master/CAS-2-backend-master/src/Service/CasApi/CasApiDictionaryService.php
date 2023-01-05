<?php

namespace App\Service\CasApi;

use App\Entity\ApiData\ApiToken;
use App\Entity\Reference\Animal\Kind;
use App\Interfaces\CAS\CasApiAuthInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\ApiException;
use App\Exception\ApiQueueException;
use App\Repository\Animal\KindRepository;
use App\Repository\Animal\BreedRepository;
use App\Repository\Animal\ColourRepository;

class CasApiDictionaryService extends ApiBaseConnector
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManagerInterface;

    /**
     * @var KindRepository
     */
    private KindRepository $kindRepository;
    /**
     * @var BreedRepository
     */
    private BreedRepository $breedRepository;

    /**
     * @var ColourRepository
     */
    private ColourRepository $colourRepository;



    public function __construct(
        CasApiAuthInterface $casApiAuthService,
        EntityManagerInterface $entityManagerInterface,
        KindRepository $kindRepository,
        BreedRepository $breedRepository,
        ColourRepository $colourRepository
    ) {
        parent::__construct($casApiAuthService);
        $this->entityManagerInterface = $entityManagerInterface;
        $this->kindRepository = $kindRepository;
        $this->breedRepository = $breedRepository;
        $this->colourRepository = $colourRepository;
    }

    public function getDictionaryByName(string $dictionary, string $name)
    {
        switch ($dictionary) {
            case 'kind':
                $item = $this->kindRepository->findOneByName($name);
                if ($item != null && $item->getExternalId() != null) {
                    return $item->getExternalId();
                }
                break;
            case 'breed':
                $item = $this->breedRepository->findOneByName($name);
                if ($item != null && $item->getExternalId() != null) {
                    return $item->getExternalId();
                }
                break;
            case 'colour':
                $item = $this->colourRepository->findOneByName($name);
                if ($item != null && $item->getExternalId() != null) {
                    return $item->getExternalId();
                }
                break;

            case 'vaccine-series':
                $item = $this->colourRepository->findOneByName($name);
                if ($item != null && $item->getExternalId() != null) {
                    return $item->getExternalId();
                }
                break;
        }

        $id = $this->loadDictionaryFromApi($dictionary, $name);

        if ($id == null) {
            throw new ApiQueueException($dictionary, "Не найдено значение \"{$name}\"");
        }

        return $id;
    }
    private function loadDictionaryFromApi(string $dictionary, string $name)
    {
        if (
            !$this->apiAuthUrl
            || !$this->apiClientId || !$this->casApiClientSecret
            || !$this->apiLogin || !$this->apiPassword
        ) {
            return null;
        }

        $response = $this->httpClientInterface->request(
            'GET',
            "{$this->apiUrl}dictionary/{$dictionary}",
            [
                'headers' => [
                    'Host' => 'localhost:4200',
                    'User-Agent' => 'PHP console app',
                    'Authorization' => "Bearer {$this->casApiAuthService->getAuthToken()}"
                ],
                'query' => [
                    'name' => $name
                ]
            ]
        );

        if ($response) {
            if ($response->getStatusCode() == 401) {
                $this->casApiAuthService->refreshAuthToken();
                return $this->LoadDictionaryFromApi($dictionary, $name);
            }
            $content = json_decode($response->getContent(), true);
            if (isset($content['status']) && isset($content["data"]) && $content["status"] == true && $content["data"]["total"] > 0) {
                $item = $content["data"]["items"][0];
                return $item["id"];
            }
        }
        return null;
    }

    public function getVaccineSeria(string $name, string $seria)
    {
        if (
            !$this->apiAuthUrl
            || !$this->apiClientId || !$this->casApiClientSecret
            || !$this->apiLogin || !$this->apiPassword
        ) {
            return null;
        }

        $response = $this->httpClientInterface->request(
            'GET',
            "{$this->apiUrl}dictionary/vaccine-series",
            [
                'headers' => [
                    'Host' => 'localhost:4200',
                    'User-Agent' => 'PHP console app',
                    'Authorization' => "Bearer {$this->casApiAuthService->getAuthToken()}"
                ],
                'query' => [
                    'vaccine_name' => $name,
                    'serial_number' => $seria
                ]
            ]
        );

        if ($response) {
            if ($response->getStatusCode() == 401) {
                $this->casApiAuthService->refreshAuthToken();
                return $this->getVaccineSeria($name, $seria);
            }
            $content = json_decode($response->getContent(), true);
            if (isset($content['status']) && isset($content["data"]) && $content["status"] == true && $content["data"]["total"] > 0) {
                $item = $content["data"]["items"][0];
                return $item;
            }
        }
        return null;
    }

    public function getLocation(string $address)
    {
        if (
            !$this->apiAuthUrl
            || !$this->apiClientId || !$this->casApiClientSecret
            || !$this->apiLogin || !$this->apiPassword
        ) {
            return null;
        }

        $response = $this->httpClientInterface->request(
            'POST',
            "{$this->apiUrl}location/create",
            [
                'headers' => [
                    'User-Agent' => 'PHP console app',
                    'Authorization' => "Bearer {$this->casApiAuthService->getAuthToken()}"
                ],
                'body' => [
                    'address' => $address
                ]
            ]
        );

        if ($response) {
            if ($response->getStatusCode() == 401) {
                $this->casApiAuthService->refreshAuthToken();
                return $this->getLocation($address);
            }
            $content = json_decode($response->getContent(), true);
            if (isset($content['status']) && isset($content["data"]) && $content["status"] == true) {
                return $content["data"]["id"];
            }
        }
        return null;
    }
}

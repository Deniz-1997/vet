<?php

namespace App\Service\CasApi;

use Doctrine\ORM\EntityManagerInterface;
use App\Packages\DBAL\Types\ApiQueueStatusEnum;
use App\Interfaces\CAS\CasApiAuthInterface;
use App\Entity\ApiData\ApiQueueRow;

class CasCheckVaccinationStatusService extends ApiBaseConnector
{
    private int $maxCount = 50;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManagerInterface;

    public function __construct(
        CasApiDictionaryService $casApiDictionaryService,
        CasApiAuthInterface $casApiAuthService,
        EntityManagerInterface $entityManagerInterface
    ) {
        parent::__construct($casApiAuthService);
        $this->casApiDictionaryService = $casApiDictionaryService;
        $this->entityManagerInterface = $entityManagerInterface;
    }

    public function checkData($idArray = null)
    {
        if ($idArray == null || count($idArray) == 0) {
            $idArray = [];
            $conn = $this->entityManagerInterface->getConnection();

            $request = "SELECT external_id FROM import.api_queue_row where status = 'pending' OR status='uploaded'";
            $stmt = $conn->prepare($request);
            $apiQueues = $stmt->executeQuery()->fetchAllAssociative();
            foreach ($apiQueues as $item) {
                array_push($idArray, $item["external_id"]);
            }
        }
        if (count($idArray) > $this->maxCount) {
            $count = 0;
            do {
                $slicedArray = array_slice($idArray, $this->maxCount * $count, $this->maxCount);
                $this->checkData($slicedArray);
                $count++;
            } while ($this->maxCount*$count < count($idArray));
        }

        $response = $this->httpClientInterface->request(
            'POST',
            "{$this->apiUrl}vaccination/check",
            [
                'headers' => [
                    'User-Agent' => 'PHP console app',
                    'Authorization' => "Bearer {$this->casApiAuthService->getAuthToken()}"
                ],
                'body' => $idArray
            ]
        );
        if ($response) {
            if ($response->getStatusCode() == 401) {
                $this->casApiAuthService->refreshAuthToken();
                return $this->checkData($idArray);
            }
            $content = json_decode($response->getContent(), true);
            if (isset($content['status']) && $content["status"] == true) {
                foreach ($content['message'] as $item) {
                    /** @var ApiQueueRow*/
                    $apiQueueRow = $this->entityManagerInterface->getRepository(ApiQueueRow::class)->findOneBy(['externalId' => $item['id']]);
                    $apiQueueRow->setStatus($this->getStatus($item['record']['status']));

                    $this->entityManagerInterface->persist($apiQueueRow);
                    $this->entityManagerInterface->flush();
                }
            }
            return $content['status'];
        }
    }

    private function getStatus(string $statusMessage): ApiQueueStatusEnum
    {
        switch ($statusMessage) {
            case 'В ожидании':
                return ApiQueueStatusEnum::getItem(ApiQueueStatusEnum::PENDING);
            case 'Успешно загружена в систему':
                return ApiQueueStatusEnum::getItem(ApiQueueStatusEnum::FINISHED);
            case 'Заверешенно с ошибкой':
                return ApiQueueStatusEnum::getItem(ApiQueueStatusEnum::FINISHED_WITH_ERRORS);
            default:
                return ApiQueueStatusEnum::getItem(ApiQueueStatusEnum::UPLOADED);
        }
    }
}

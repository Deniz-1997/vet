<?php

namespace App\Service\CasApi;

use App\Entity\ApiData\ApiQueue;
use App\Entity\ApiData\ApiQueueRow;
use App\Exception\ApiException;
use App\Interfaces\CAS\CasExcelUploadInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\User\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Interfaces\CAS\CasApiAuthInterface;
use App\Service\CasApi\CasApiDictionaryService;
use App\Service\CasApi\ApiBaseConnector;
use App\Packages\DBAL\Types\ApiQueueStatusEnum;
use App\Entity\Reference\BusinesEntity;
use App\Entity\Reference\Station;
use App\Entity\Reference\SupervisedObjects;
use App\Exception\ApiQueueException;
use App\Entity\Reference\Subdivision;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpClient\Exception\ServerException;

class CasExcelUploadService extends ApiBaseConnector implements CasExcelUploadInterface
{
    /** @var User|null */
    private $currentUser = null;

    /** @var CasApiDictionaryService */
    private $casApiDictionaryService;



    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManagerInterface;
    private string $hashFile;
    private string $fileName;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        CasApiDictionaryService $casApiDictionaryService,
        CasApiAuthInterface $casApiAuthService,
        EntityManagerInterface $entityManagerInterface
    ) {
        parent::__construct($casApiAuthService);
        $this->currentUser = $tokenStorage == null || $tokenStorage->getToken() == null ? null : $tokenStorage->getToken()->getUser();
        $this->casApiDictionaryService = $casApiDictionaryService;
        $this->entityManagerInterface = $entityManagerInterface;
    }

    public function UploadApiQueueRow(ApiQueueRow $row)
    {
        try {
            $importData = array();
            $item = $row->getData();
            $animal = null;
            $vaccination = null;
            $animal['owner'] = $this->getFio($item['owner'], 'owner');
            $animal['location_id'] = $this->casApiDictionaryService->getLocation($item['address']);
            $animal['kind_id'] = $this->casApiDictionaryService->getDictionaryByName('kind', $item['kind']);
            $animal['name'] = $item['name'];
            $animal['gender'] = $this->getGender($item['gender']);
            $animal['colour_id'] = $this->casApiDictionaryService->getDictionaryByName('colour', $item['colour']);
            $animal['breed_id'] = $this->casApiDictionaryService->getDictionaryByName('breed', $item['breed']);
            $animal['chip'] = $item['chip'];
            $animal['birthdate'] = $item['birthdate'];
            $animal['id'] = $this->getAnimalId($animal);
            $vaccination['vaccination']['date'] = $item['vaccinationDate'];
            $vaccine = $this->casApiDictionaryService->getVaccineSeria($item['vaccine'], $item['vaccineSeria']);
            if ($vaccine != null) {
                $vaccination['vaccine_serial']['id'] = $vaccine['id'];
            } else {
                throw new ApiQueueException('vaccineSeria', 'Данная серия вакцины не найдена');
            }
            $vaccination['doctor'] = $this->getFio($item['doctor'], 'doctor');

            array_push($importData, ['animal' => $animal, 'vaccination' => $vaccination]);

            if (count($importData) > 0) {
                $this->sendData($importData, $row);
            }
        } catch (ApiQueueException $ex) {
            $row->setError($ex->getExceptionArray());
            $row->setStatus(ApiQueueStatusEnum::getItem(ApiQueueStatusEnum::FINISHED_WITH_ERRORS));
            $this->entityManagerInterface->persist($row);
            $this->entityManagerInterface->flush();
        }
    }

    public function SaveFile(UploadedFile $file, ?Station $station, ?BusinesEntity $businesEntity, ?SupervisedObjects $supervisedObject, ?Subdivision $subdivision)
    {
        $this->hashFile = md5_file($file->getPathname());
        $this->fileName = $file->getClientOriginalName();

        /** @var ApiQueue*/
        $apiQueue = $this->entityManagerInterface->getRepository(ApiQueue::class)->findOneBy(['hash' => $this->hashFile]);
        if ($apiQueue == null) {
            $apiQueue = new ApiQueue();
            $apiQueue->setCreatedAt(new DateTime());
        }
        if ($businesEntity != null) {
            $apiQueue->setBusinesEntity($businesEntity);
        }
        if ($station != null) {
            $apiQueue->setStation($station);
        }
        if ($supervisedObject != null) {
            $apiQueue->setSupervisedObject($supervisedObject);
        }
        if ($subdivision != null) {
            $apiQueue->setSubdivision($subdivision);
        }
        $apiQueue->setUser($this->currentUser);
        $apiQueue->setUpdatedAt(new DateTime());
        $apiQueue->setHash($this->hashFile);
        $apiQueue->setName($this->fileName);

        $this->entityManagerInterface->persist($apiQueue);
        $this->entityManagerInterface->flush();

        $noNewRows = true;

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            foreach ($sheetData as $item) {
                if (
                    $item[0] != null && $item[1] != null && $item[2] != null && $item[3] != null
                    && $item[0] != 'Владелец' && $item[1] != 'Адрес содержания' && $item[2] != 'Вид животного' && $item[3] != 'Кличка'
                ) {
                    $this->validateRow($item);

                    $flatData = [
                        'owner' => isset($item[0]) ? $item[0] : null,
                        'address' => isset($item[1]) ? $item[1] : null,
                        'kind' => isset($item[2]) ? $item[2] : null,
                        'name' => isset($item[3]) ? $item[3] : null,
                        'gender' => isset($item[4]) ? $item[4] : null,
                        'colour' => isset($item[5]) ? $item[5] : null,
                        'breed' => isset($item[6]) ? $item[6] : null,
                        'chip' => isset($item[7]) ? $item[7] : null,
                        'stamps' => isset($item[8]) ? $item[8] : null,
                        'birthdate' => isset($item[9]) ? $this->getDate($item[9]) : null,
                        'vaccinationDate' => isset($item[10]) ? $this->getDate($item[10]) : null,
                        'vaccine' => isset($item[11]) ? $item[11] : null,
                        'vaccineSeria' => isset($item[12]) ? $item[12] : null,
                        'vaccineDate' => isset($item[13]) ? $this->getDate($item[13]) : null,
                        'vaccineExpired' => isset($item[14]) ? $this->getDate($item[14]) : null,
                        'doctor' => isset($item[15]) ? $item[15] : null,
                    ];
                    $hash = $this->getRowHash($flatData);
                    /** @var ApiQueueRow*/
                    $apiQueueRow = $this->entityManagerInterface->getRepository(ApiQueueRow::class)->findOneBy(['hash' => $hash]);

                    if ($apiQueueRow != null) {
                        continue;
                    }
                    $apiQueueRow = new ApiQueueRow();
                    $apiQueueRow->setCreatedAt(new DateTime());
                    $apiQueueRow->setStatus(ApiQueueStatusEnum::getItem(ApiQueueStatusEnum::SAVED));
                    $apiQueueRow->setUpdatedAt(new DateTime());
                    $apiQueueRow->setHash($hash);
                    $apiQueueRow->setApiQueue($apiQueue);
                    $apiQueueRow->setData($flatData);

                    $this->entityManagerInterface->persist($apiQueueRow);
                    $this->entityManagerInterface->flush();
                    $noNewRows = false;
                }
            }
        } catch (ServerException $ex) {
            throw new ApiException($ex->getMessage(), 'SERVER_ERROR', null, 400);
        } catch (ApiException $ex) {
            throw $ex;
        } catch (Exception $ex) {
            throw new ApiException("Данные в файле заполненны не верно", 'FILE_ERROR', null, 400);
        }
        if ($noNewRows) {
             /** @var ApiQueue*/
            $apiQueue = $this->entityManagerInterface->getRepository(ApiQueue::class)->findOneBy(['id' => $apiQueue->getId()]);/*  */
            if (count($apiQueue->getRows()) == 0) {
                $this->entityManagerInterface->remove($apiQueue);
                $this->entityManagerInterface->flush();
            }
            throw new ApiException("Данные были загружены ранее", 'FILE_ERROR', null, 400);
        }
    }

    private function getDate(string $data)
    {
        try {
            if (str_contains($data, '.')) {
                $strArray = explode('.', $data);
                if (strlen($strArray[0]) <= 2) {
                    return DateTime::createFromFormat('d.m.Y', $data)->format('d.m.Y');
                } else {
                    return DateTime::createFromFormat('Y.m.d', $data)->format('d.m.Y');
                }
            } else if (str_contains($data, '-')) {
                $strArray = explode('-', $data);
                if (strlen($strArray[0]) <= 2) {
                    return DateTime::createFromFormat('d-m-Y', $data)->format('d.m.Y');
                } else {
                    return DateTime::createFromFormat('Y-m-d', $data)->format('d.m.Y');
                }
            } else {
                $strArray = explode('/', $data);
                if (strlen($strArray[0]) <= 2) {
                    return DateTime::createFromFormat('d/m/Y', $data)->format('d.m.Y');
                } else {
                    return DateTime::createFromFormat('Y/m/d', $data)->format('d.m.Y');
                }
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    private function getFio(string $data, string $field)
    {
        $dataArray = explode(' ', $data);
        // Убираем пустые элементы пробелы, например для записи 'Иванов    Иван       Иванович'
        $dataWithOutEmptyElements = [];
        foreach ($dataArray as $dataItem) {
            if ($dataItem != '' && $dataItem != ' ') {
                array_push($dataWithOutEmptyElements, $dataItem);
            }
        }
        $dataArray = $dataWithOutEmptyElements;
        $person = null;

        if ($dataArray != null && isset($dataArray[0])) {
            $person['surname'] = $dataArray[0];
        } else {
            throw new ApiQueueException($field, "Не заполнена фамилия");
        }

        if ($dataArray != null && isset($dataArray[1])) {
            $person['name'] = $dataArray[1];
        } else {
            throw new ApiQueueException($field, "Не заполнено имя");
        }

        if ($dataArray != null && isset($dataArray[2])) {
            $person['patronymic'] = $dataArray[2];
        } else {
            throw new ApiQueueException($field, "Не заполнено отчетсво");
        }
        return  $person;
    }

    private function getGender(string $gender)
    {
        if (trim(mb_strtolower($gender)) == 'самка') {
            return 'MALE';
        } elseif (trim(mb_strtolower($gender)) == 'самец') {
            return 'FEMALE';
        }
    }

    private function sendData($importData, ApiQueueRow $row)
    {
        $response = $this->httpClientInterface->request(
            'POST',
            "{$this->apiUrl}vaccination/add",
            [
                'headers' => [
                    'User-Agent' => 'PHP console app',
                    'Authorization' => "Bearer {$this->casApiAuthService->getAuthToken()}"
                ],
                'body' => $importData
            ]
        );
        if ($response) {
            if ($response->getStatusCode() == 401) {
                $this->casApiAuthService->refreshAuthToken();
                return $this->sendData($importData, $row);
            }
            $content = json_decode($response->getContent(), true);
            if (isset($content['status']) && $content["status"] == false && $content["errors"]) {
                $tt = $content["errors"][0]['fields'];
                $key = array_keys($tt)[0];
                $value = $tt[$key][0]['message'];
                throw new ApiQueueException($key, $value);
            }
            if (isset($content['status']) && $content["status"] == true && $content["data"]) {
                $row->setExternalId($content["data"][0]['record']['id']);
                $row->setStatus(ApiQueueStatusEnum::getItem(ApiQueueStatusEnum::UPLOADED));

                $this->entityManagerInterface->persist($row);
                $this->entityManagerInterface->flush();
            }
        }
    }

    private function getAnimalId($animal)
    {
        $response = $this->httpClientInterface->request(
            'POST',
            "{$this->apiUrl}animal/create",
            [
                'headers' => [
                    'User-Agent' => 'PHP console app',
                    'Authorization' => "Bearer {$this->casApiAuthService->getAuthToken()}"
                ],
                'body' => $animal
            ]
        );
        if ($response) {
            if ($response->getStatusCode() == 401) {
                $this->casApiAuthService->refreshAuthToken();
                return $this->getAnimalId($animal);
            }
            $content = json_decode($response->getContent(), true);
            if (isset($content['status']) && $content["status"] == false && count($content["errors"]) > 0) {
                $tt = $content["errors"][0]['fields'];
                $key = array_keys($tt)[0];
                $value = $tt[$key][0]['message'];
                throw new ApiQueueException($key, $value);
            }
            if (isset($content['status']) && $content["status"] == true && $content["data"]) {
                return $content["data"]["id"];
            }
        }
        return null;
    }

    public function validateRow($row)
    {
        if (!isset($row[0]) || $row[0] == null) {
            throw new ApiException("Заполните поле \"Владелец\"", 'UPLOAD_ERROR', null, 400);
        }
        if (!isset($row[1]) || $row[1] == null) {
            throw new ApiException("Заполните поле \"Адрес содержания\"", 'UPLOAD_ERROR', null, 400);
        }
        if (!isset($row[2]) || $row[2] == null) {
            throw new ApiException("Заполните поле \"Вид животного\"", 'UPLOAD_ERROR', null, 400);
        }
        if (!isset($row[3]) || $row[3] == null) {
            throw new ApiException("Заполните поле \"Кличка\"", 'UPLOAD_ERROR', null, 400);
        }
        if (!isset($row[4]) || $row[4] == null) {
            throw new ApiException("Заполните поле \"Пол\"", 'UPLOAD_ERROR', null, 400);
        }
        if (!isset($row[5]) || $row[5] == null) {
            throw new ApiException("Заполните поле \"Масть\"", 'UPLOAD_ERROR', null, 400);
        }
        if (!isset($row[6]) || $row[6] == null) {
            throw new ApiException("Заполните поле \"Порода\"", 'UPLOAD_ERROR', null, 400);
        }
        if (!isset($row[9]) || $row[9] == null) {
            throw new ApiException("Заполните поле \"Дата рождения\"", 'UPLOAD_ERROR', null, 400);
        }
        if (!isset($row[10]) || $row[10] == null) {
            throw new ApiException("Заполните поле \"Дата вакцинации\"", 'UPLOAD_ERROR', null, 400);
        }
        if (!isset($row[11]) || $row[11] == null) {
            throw new ApiException("Заполните поле \"Вакцина\"", 'UPLOAD_ERROR', null, 400);
        }
        if (!isset($row[12]) || $row[12] == null) {
            throw new ApiException("Заполните поле \"Серия\"", 'UPLOAD_ERROR', null, 400);
        }
        if (!isset($row[13]) || $row[13] == null) {
            throw new ApiException("Заполните поле \"Дата изготовления\"", 'UPLOAD_ERROR', null, 400);
        }
        if (!isset($row[14]) || $row[14] == null) {
            throw new ApiException("Заполните поле \"Срок годности (дата)\"", 'UPLOAD_ERROR', null, 400);
        }
        if (!isset($row[15]) || $row[15] == null) {
            throw new ApiException("Заполните поле \"Врач\"", 'UPLOAD_ERROR', null, 400);
        }
    }

    private function getRowHash($item)
    {
        $hash = md5(
            $item['owner']
                . $item['address']
                . $item['kind']
                . $item['name']
                . $item['gender']
                . $item['colour']
                . $item['breed']
                . $item['chip']
                . $item['stamps']
                . $item['birthdate']
                . $item['vaccinationDate']
                . $item['vaccine']
                . $item['vaccineSeria']
                . $item['vaccineDate']
                . $item['vaccineExpired']
                . $item['doctor']
        );
        return $hash;
    }
}

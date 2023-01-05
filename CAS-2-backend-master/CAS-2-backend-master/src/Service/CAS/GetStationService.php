<?php

namespace App\Service\CAS;

use App\Entity\Reference\Station;
use Doctrine\ORM\EntityManagerInterface;

class GetStationService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManagerInterface;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $casEntityManager;

    public function __construct(EntityManagerInterface       $entityManagerInterface,
                                EntityManagerInterface $casEntityManager)
    {
        $this->entityManagerInterface = $entityManagerInterface;
        $this->casEntityManager = $casEntityManager;
    }


    public function getUserStationById(string $person_id): ?Station
    {
        $conn = $this->casEntityManager->getConnection();
        $stationRequest = "select distinct s.id, s.parent_id, s.name
                            from auth.fos_user u join auth.person p on p.id = '" . $person_id . "'
                              join contractors.supervisory_authorities s on s.id = p.supervisory_authority_id";
        $stmt = $conn->prepare($stationRequest);
        $stations = $stmt->executeQuery()->fetchAllAssociative();
        foreach ($stations as $casStation) {
            if ($casStation !== null) {
                return $this->GetStation($casStation['id']);
            }
        }
        return null;
    }
    public function getChildStation(int $id): array
    {
        $stations = [];
        $connStation = $this->entityManagerInterface->getConnection();
        $requestChildStation = "select * from reference.reference_station where parent_id ='" . $id . "'";
        $stmtStation = $connStation->prepare($requestChildStation);
        $childStation = $stmtStation->executeQuery()->fetchAllAssociative();
        $stations = array_merge($stations, $childStation);
        foreach ($childStation as $station) {
            $stations = array_merge($stations, $this->getChildStation($station['id']));
        }
        return $stations;
    }

    private function GetStation(string $stationId): ?Station
    {
        $station = $this->entityManagerInterface->getRepository(Station::class)->findOneBy(["externalId" => $stationId]);
        if ($station == null) {
            $casStationRequest = "select * from contractors.supervisory_authorities where id = '" . $stationId . "'";
            $conn = $this->casEntityManager->getConnection();
            $stmt = $conn->prepare($casStationRequest);
            $casStations = $stmt->executeQuery()->fetchAllAssociative();
            foreach ($casStations as $casStation) {
                /** @var Station*/
                $station = $this->entityManagerInterface->getRepository(Station::class)->findOneBy(['name' => $casStation['name']]);
                if ($station != null) {
                    $station->setExternalId($casStation['id']);
                    $this->entityManagerInterface->flush();
                }
                else {
                    $station = new Station();
                    $station->setName($casStation['name']);
                    $station->setExternalId($casStation['id']);
                    $station->setDeleted(false);
                    if (isset($casStation['parent_id'])) {
                        $parentStation = $this->GetStation($casStation['parent_id']);
                        if (isset($parentStation)) {
                            $station->setParent($parentStation);
                        }
                    }
                    $this->entityManagerInterface->persist($station);
                    $this->entityManagerInterface->flush();
                }
            }
        }
        return $station;
    }
}

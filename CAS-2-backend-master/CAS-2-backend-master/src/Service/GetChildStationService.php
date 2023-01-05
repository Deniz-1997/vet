<?php

namespace App\Service;



use App\Entity\Reference\Station;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class GetChildStationService
 */
class GetChildStationService
{
    private EntityManagerInterface $defaultEntityManger;

    public function __construct(EntityManagerInterface $defaultEntityManger)
    {
        $this->defaultEntityManger = $defaultEntityManger;
    }

    public function getChildStation(int $id): array
    {
        $stations = [];
        $childStation = $this->defaultEntityManger->getRepository(Station::class)->findBy(['parent' => $id]);
        $stations = array_merge($stations, $childStation);
        foreach ($childStation as $station) {
            $stations = array_merge($stations, $this->getChildStation($station->getId()));
        }
        return $stations;
    }
}

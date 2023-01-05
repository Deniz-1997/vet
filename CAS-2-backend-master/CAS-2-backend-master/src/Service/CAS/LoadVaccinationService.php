<?php

namespace App\Service\CAS;

use Exception;
use App\Entity\Import\UploadedVaccinationExcelFileEntry;
use App\Entity\Import\UploadedVaccinationExcelRowEntry;
use App\Packages\DBAL\Types\VaccinationUploadStatusEnum;
use App\Entity\Reference\Vaccine\Vaccination;
use App\Entity\Reference\Animal\Animal;
use App\Entity\Reference\Animal\Kind;
use App\Entity\Reference\Animal\Breed;
use App\Entity\Reference\Animal\Colour;
use App\Entity\Reference\Countries;
use App\Entity\Reference\Disease;
use App\Entity\Reference\Location\Circle;
use App\Entity\Reference\Location\Location;
use App\Entity\Reference\Location\Path;
use App\Entity\Reference\Vaccine\Manufacturer;
use App\Entity\Reference\Vaccine\Vaccine;
use App\Entity\Reference\Vaccine\VaccineSeries;
use App\Entity\User\User;
use DateTime;
use App\Entity\Reference\Station;
use App\Packages\DBAL\Types\AnimalGenderEnum;
use Doctrine\ORM\EntityManagerInterface;

class LoadVaccinationService
{
    private int $percent = 75;

    private EntityManagerInterface $casEntityManager;
    private EntityManagerInterface $defaultEntityManager;

    public function __construct(EntityManagerInterface $casEntityManager, EntityManagerInterface $defaultEntityManager)
    {
        $this->casEntityManager = $casEntityManager;
        $this->defaultEntityManager = $defaultEntityManager;
    }

    public function AddExcelFile($casExcelFile): UploadedVaccinationExcelFileEntry
    {
        /** @var UploadedVaccinationExcelFileEntry */
        $excelFile = $this->defaultEntityManager->getRepository(UploadedVaccinationExcelFileEntry::class)->findOneBy(['externalId' => $casExcelFile['id']]);
        if ($excelFile == null) {
            $excelFile = new UploadedVaccinationExcelFileEntry();
            $excelFile->setExternalId($casExcelFile['id']);
            $excelFile->setStatus($this->GetStatusCode($casExcelFile['status_code']));
            $excelFile->setStatusMsg($casExcelFile['status_msg']);
            $excelFile->setSourceName($casExcelFile['source_name']);
            $excelFile->setStation($this->GetStation($casExcelFile['station_id']));
            $excelFile->setLock(new DateTime($casExcelFile['lock']));
            $excelFile->setUploadedAt(new DateTime($casExcelFile['uploaded_at']));
            $excelFile->setHash($casExcelFile['hash']);
            $user = $this->GetUser($casExcelFile['uploaded_by']);
            if ($user) {
                $excelFile->setUser($user);
            }
            try {
                $this->defaultEntityManager->persist($excelFile);
                $this->defaultEntityManager->flush();
            } catch (Exception $exception) {
                throw $exception;
            }
            $excelFile->setRows($this->GetImportRows($casExcelFile['id'], $excelFile));
        }
        return $excelFile;
    }

    private function GetUser($userId)
    {
        /** @var User */
        $user = $this->defaultEntityManager->getRepository(User::class)->findOneBy(['externalId' => $userId]);
        if ($user != null) {
            return $user;
        }
        return  null;
    }

    private function GetImportRows($fileExternalId, $excelFile)
    {
        $resultRows = [];
        $conn = $this->casEntityManager->getConnection();
        $requestExcelRows = "select * from import.uploaded_vaccination_excel_row where excel_file_id='{$fileExternalId}'";
        $stmt = $conn->prepare($requestExcelRows);
        $casExcelRows = $stmt->executeQuery()->fetchAllAssociative();
        foreach ($casExcelRows as $casExcelRow) {
            /** @var UploadedVaccinationExcelRowEntry */
            $excelRow = $this->defaultEntityManager->getRepository(UploadedVaccinationExcelRowEntry::class)->findOneBy(['externalId' => $casExcelRow['id']]);
            if ($excelRow == null) {
                $excelRow = new UploadedVaccinationExcelRowEntry();
                $excelRow->setExternalId($casExcelRow['id']);
                $excelRow->setStatus($this->GetStatusCode($casExcelRow['status_code']));
                $excelRow->setStatusMsg($casExcelRow['status_msg']);
                $excelRow->setParsedAt(new DateTime($casExcelRow['parsed_at']));
                $excelRow->setProcessedAt(new DateTime($casExcelRow['processed_at']));
                $excelRow->setRowNumber($casExcelRow['row_number']);
                $excelRow->setData($casExcelRow['data']);
                $excelRow->setExcelFile($excelFile);
                if ($casExcelRow['vaccination_id']) {
                    $excelRow->setVaccination($this->GetVaccination($casExcelRow['vaccination_id']));
                }
                try {
                    $this->defaultEntityManager->persist($excelRow);
                    $this->defaultEntityManager->flush();
                } catch (Exception $exception) {
                    throw $exception;
                }
            }
            array_push($resultRows, $excelRow);
        }
        return $resultRows;
    }

    private function GetStation(string $stationId): ?Station
    {
        $station = $this->defaultEntityManager->getRepository(Station::class)->findOneBy(["externalId" => $stationId]);
        if ($station == null) {
            $casStationRequest = "select * from contractors.supervisory_authorities where id = '" . $stationId . "'";
            $conn = $this->casEntityManager->getConnection();
            $stmt = $conn->prepare($casStationRequest);
            $casStations = $stmt->executeQuery()->fetchAllAssociative();
            foreach ($casStations as $casStation) {
                /** @var Station*/
                $station = $this->defaultEntityManager->getRepository(Station::class)->findOneBy(['name' => $casStation['name']]);
                if ($station != null) {
                    $station->setExternalId($casStation['id']);
                    $this->defaultEntityManager->flush();
                } else {
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
                    $this->defaultEntityManager->persist($station);
                    $this->defaultEntityManager->flush();
                }
            }
        }
        return $station;
    }

    public function GetVaccination($casVaccinationId): ?Vaccination
    {
        /** @var Vaccination */
        $vaccination = $this->defaultEntityManager->getRepository(Vaccination::class)->findOneBy(["externalId" => $casVaccinationId]);
        if ($vaccination == null || $vaccination->getDoctor() == null) {
            if ($vaccination == null) {
                $vaccination = new Vaccination();
            }
            $conn = $this->casEntityManager->getConnection();
            $requestVaccination = "select * from vaccination.vaccination where id='{$casVaccinationId}'";
            $stmt = $conn->prepare($requestVaccination);
            $casVaccination = $stmt->executeQuery()->fetchAllAssociative()[0];
            $vaccination->setExternalId($casVaccination['id']);
            $vaccination->setStation($this->GetStation($casVaccination['station']));
            $vaccination->setCreatedAt(new DateTime($casVaccination['createdat']));
            $vaccination->setUpdatedAt(new DateTime($casVaccination['createdat']));
            $vaccination->setDate(new DateTime($casVaccination['date']));
            $vaccination->setAnimals($this->GetVaccinationAnimals($casVaccination['id']));
            $vaccination->setVaccineSeries($this->GetVaccineSeries($casVaccination['id']));
            $vaccination->setDoctor($this->GetDoctor($casVaccination['id']));
            try {
                $this->defaultEntityManager->persist($vaccination);
                $this->defaultEntityManager->flush();
            } catch (Exception $exception) {
                throw $exception;
            }
        }
        return $vaccination;
    }

    private function GetVaccinationAnimals($casVaccinationId)
    {
        $resultAnimals = [];
        $conn = $this->casEntityManager->getConnection();
        $requestAnimalsRows = "select * from animal.animal a
                             join vaccination.vaccination_animal v on v.animal_id = a.id 
                             where v.vaccination_id='{$casVaccinationId}'";
        $stmt = $conn->prepare($requestAnimalsRows);
        $casAnimals = $stmt->executeQuery()->fetchAllAssociative();
        foreach ($casAnimals as $casAnimal) {
            /** @var Animal */
            $animal = $this->defaultEntityManager->getRepository(Animal::class)->findOneBy(['externalId' => $casAnimal['id']]);
            if ($animal == null) {
                $animal = new Animal();
                $animal->setExternalId($casAnimal['id']);
                $animal->setName($casAnimal['name']);
                $animal->setChip($casAnimal['chip']);
                $animal->setOwner($casAnimal['owner']);
                $animal->setBirthdate(new DateTime($casAnimal['birthdate']));
                $animal->setCreatedAt(new DateTime($casAnimal['createdat']));
                $animal->setUpdatedAt(new DateTime($casAnimal['updatedat']));
                $animal->setKind($this->GetKind($casAnimal['kind_id']));
                $animal->setBreed($this->GetBreed($casAnimal['breed_id'], $animal->getKind()));
                $animal->setColour($this->GetColour($casAnimal['colour_id']));
                $animal->setLocation($this->GetLocation($casAnimal['location_id']));
                $animal->setGender($this->GetAnimalGender($casAnimal['gender']));

                try {
                    $this->defaultEntityManager->persist($animal);
                    $this->defaultEntityManager->flush();
                } catch (Exception $exception) {
                    throw $exception;
                }
            }
            array_push($resultAnimals, $animal);
        }
        return $resultAnimals;
    }

    private function GetVaccineSeries($casVaccinationId)
    {
        $resultVaccineSerias = [];
        $conn = $this->casEntityManager->getConnection();
        $requestVaccineSerias = "select * from dictionary.vaccine_series vs
                               join vaccination.vaccination_vaccine_series vvs on vs.id = vvs.vaccine_series_id
                               where vvs.vaccination_id='{$casVaccinationId}'";
        $stmt = $conn->prepare($requestVaccineSerias);
        $casVaccineSerias = $stmt->executeQuery()->fetchAllAssociative();
        foreach ($casVaccineSerias as $casVaccineSeria) {
            $vaccineSeria = $this->GetSeria($casVaccineSeria['id']);
            if ($vaccineSeria != null) {
                array_push($resultVaccineSerias, $vaccineSeria);
            }
        }
        return $resultVaccineSerias;
    }

    private function GetSeria($vaccineSeriaId)
    {
        /** @var VaccineSeries */
        $vaccineSeria = $this->defaultEntityManager->getRepository(VaccineSeries::class)->findOneBy(['externalId' => $vaccineSeriaId]);
        if ($vaccineSeria == null) {
            $conn = $this->casEntityManager->getConnection();
            $requestvaccineSeria = "select * from dictionary.vaccine_series where id='{$vaccineSeriaId}'";
            $stmt = $conn->prepare($requestvaccineSeria);
            $casVaccineSeria = $stmt->executeQuery()->fetchAllAssociative()[0];
            $vaccineSeria = new VaccineSeries();
            $vaccineSeria->setExternalId($casVaccineSeria['id']);
            $vaccineSeria->setDeleted(false);
            $vaccineSeria->setSerialNumber($casVaccineSeria['serial_number']);
            if ($casVaccineSeria['parent_id']) {
                $vaccineSeria->setParent($this->GetSeria($casVaccineSeria['parent_id']));
            }
            $vaccineSeria->setExpires(new DateTime($casVaccineSeria['expires']));
            $vaccineSeria->setCreatedAt(new DateTime($casVaccineSeria['createdat']));
            $vaccineSeria->setUpdatedAt(new DateTime($casVaccineSeria['updatedat']));
            $vaccineSeria->setVaccine($this->GetVaccine($casVaccineSeria['vaccine_id']));
            $vaccineSeria->setProduced(new DateTime($casVaccineSeria['produced']));

            try {
                $this->defaultEntityManager->persist($vaccineSeria);
                $this->defaultEntityManager->flush();
            } catch (Exception $exception) {
                throw $exception;
            }
        }
        return  $vaccineSeria;
    }

    private function GetVaccine($vaccineId)
    {
        /** @var Vaccine */
        $vaccine = $this->defaultEntityManager->getRepository(Vaccine::class)->findOneBy(['externalId' => $vaccineId]);

        if ($vaccine == null) {
            $conn = $this->casEntityManager->getConnection();
            $requestvaccine = "select * from dictionary.vaccine where id='{$vaccineId}'";
            $stmt = $conn->prepare($requestvaccine);
            $casVaccine = $stmt->executeQuery()->fetchAllAssociative()[0];
            $vaccine = $this->GetSimilarVaccine($casVaccine["name"]);
            if ($vaccine != null) {
                return $vaccine;
            }
            $vaccine = new Vaccine();
            $vaccine->setExternalId($casVaccine['id']);
            $vaccine->setDeleted(false);
            $vaccine->setCreatedAt(new DateTime($casVaccine['createdat']));
            $vaccine->setUpdatedAt(new DateTime($casVaccine['updatedat']));
            $vaccine->setActivityDuration($casVaccine["activity_duration"]);
            $vaccine->setName($casVaccine["name"]);
            if ($casVaccine['manufacturer_id']) {
                $vaccine->setManufacturer($this->GetManufacturer($casVaccine['manufacturer_id']));
            }

            try {
                $this->defaultEntityManager->persist($vaccine);
                $this->defaultEntityManager->flush();
            } catch (Exception $exception) {
                throw $exception;
            }
            $desises = $this->GetDiseases($casVaccine['id'], $vaccine);
            if ($desises != null && is_array($desises)) {
                $vaccine->setDiseases($desises);
            }
        }
        return  $vaccine;
    }

    private function GetSimilarVaccine($vaccineName)
    {
        $vaccines = $this->defaultEntityManager->getRepository(Vaccine::class)->findAll();
        foreach ($vaccines as $vaccine) {
            similar_text($vaccine->getName(), $vaccineName, $percent);
            if ($percent >= $this->percent) {
                return $vaccine;
            }
        }
        return null;
    }

    private function GetDiseases($casVaccineId, $vaccine)
    {
        $resultDiseases = [];
        $conn = $this->casEntityManager->getConnection();
        $requestDiseases = "select * from dictionary.disease d
                                 join dictionary.vaccine_disease vd on vd.disease_id = d.id
                                 where vd.vaccine_id='{$casVaccineId}'";
        $stmt = $conn->prepare($requestDiseases);
        $casDiseases = $stmt->executeQuery()->fetchAllAssociative();
        foreach ($casDiseases as $casDisease) {
            /** @var Disease */
            $disease = $this->defaultEntityManager->getRepository(Disease::class)->findOneBy(['externalId' => $casDisease['id']]);
            if ($disease == null) {
                $disease = $this->GetSimilarDisease($casDisease["name"]);
                if ($disease == null) {
                    $disease = new Disease();
                    $disease->setDeleted(false);
                    $disease->setName($casDisease['name']);
                    if ($vaccine) {
                        $disease->addVaccine($vaccine);
                    }
                }
                $disease->setExternalId($casDisease['id']);
                try {
                    $this->defaultEntityManager->persist($disease);
                    $this->defaultEntityManager->flush();
                } catch (Exception $exception) {
                    throw $exception;
                }
            }
            array_push($resultDiseases, $disease);
        }
        return $resultDiseases;
    }

    private function GetSimilarDisease($diseaseName)
    {
        $diseases = $this->defaultEntityManager->getRepository(Disease::class)->findAll();
        foreach ($diseases as $disease) {
            similar_text($disease->getName(), $diseaseName, $percent);
            if ($percent >= $this->percent) {
                return $disease;
            }
        }
        return null;
    }

    private function GetManufacturer($manufacturerId)
    {
        /** @var Manufacturer */
        $manufacturer = $this->defaultEntityManager->getRepository(Manufacturer::class)->findOneBy(['externalId' => $manufacturerId]);
        if ($manufacturer == null) {
            $conn = $this->casEntityManager->getConnection();
            $requestManufacturer = "select * from dictionary.manufacturer where id='{$manufacturerId}'";
            $stmt = $conn->prepare($requestManufacturer);
            $casManufacturer = $stmt->executeQuery()->fetchAllAssociative()[0];
            $manufacturer = $this->GetSimilarManufacturer($casManufacturer["name"]);
            if ($manufacturer != null) {
                return $manufacturer;
            }
            $manufacturer = new Manufacturer();
            $manufacturer->setExternalId($casManufacturer['id']);
            $manufacturer->setName($casManufacturer['name']);
            $manufacturer->setDeleted(false);
            $manufacturer->setCreatedAt(new DateTime($casManufacturer['createdat']));
            $manufacturer->setUpdatedAt(new DateTime($casManufacturer['updatedat']));
            $manufacturer->setCountry($this->GetCountry($casManufacturer['country_iso']));

            try {
                $this->defaultEntityManager->persist($manufacturer);
                $this->defaultEntityManager->flush();
            } catch (Exception $exception) {
                throw $exception;
            }
        }
        return  $manufacturer;
    }

    private function GetSimilarManufacturer($manufacturerName)
    {
        $manufacturers = $this->defaultEntityManager->getRepository(Manufacturer::class)->findAll();
        foreach ($manufacturers as $manufacturer) {
            similar_text($manufacturer->getName(), $manufacturerName, $percent);
            if ($percent >= $this->percent) {
                return $manufacturer;
            }
        }
        return null;
    }

    private function GetCountry($countryIso)
    {
        /** @var Countries */
        $country = $this->defaultEntityManager->getRepository(Countries::class)->findOneBy(['iso' => $countryIso]);
        if ($country == null) {
            $conn = $this->casEntityManager->getConnection();
            $requestManufacturer = "select * from dictionary.country where iso='{$countryIso}'";
            $stmt = $conn->prepare($requestManufacturer);
            $casCountry = $stmt->executeQuery()->fetchAllAssociative()[0];
            $country = $this->GetSimilarCountry($casCountry["name"]);
            if ($country == null) {
                $country = new Countries();
                $country->setName($casCountry['name']);
                $country->setDeleted(false);
                $country->setCreatedAt(new DateTime($casCountry['createdat']));
                $country->setUpdatedAt(new DateTime($casCountry['updatedat']));
            }
            $country->setIso($casCountry['iso']);

            try {
                $this->defaultEntityManager->persist($country);
                $this->defaultEntityManager->flush();
            } catch (Exception $exception) {
                throw $exception;
            }
        }
        return  $country;
    }

    private function GetSimilarCountry($countryName)
    {
        $countries = $this->defaultEntityManager->getRepository(Countries::class)->findAll();
        foreach ($countries as $country) {
            similar_text($country->getName(), $countryName, $percent);
            if ($percent >= $this->percent) {
                return $country;
            }
        }
        return null;
    }

    private function GetKind($casKindId)
    {
        /** @var Kind */
        $kind = $this->defaultEntityManager->getRepository(Kind::class)->findOneBy(["externalId" => $casKindId]);
        if ($kind == null) {
            $conn = $this->casEntityManager->getConnection();
            $requestKind = "select * from dictionary.kind where id='{$casKindId}'";
            $stmt = $conn->prepare($requestKind);
            $casKind = $stmt->executeQuery()->fetchAllAssociative()[0];
            $kind = $this->GetSimilarKind($casKind["name"]);
            if ($kind != null) {
                return $kind;
            }
            $kind = new Kind();
            $kind->setExternalId($casKind['id']);
            $kind->setName($casKind['name']);
            $kind->setDeleted(false);
            $kind->setUpdatedAt(new DateTime($casKind['createdat']));
            $kind->setCreatedAt(new DateTime($casKind['updatedat']));
            try {
                $this->defaultEntityManager->persist($kind);
                $this->defaultEntityManager->flush();
            } catch (Exception $exception) {
                throw $exception;
            }
        }
        return $kind;
    }

    private function GetSimilarKind($kindName)
    {
        $kinds = $this->defaultEntityManager->getRepository(Kind::class)->findAll();
        foreach ($kinds as $kind) {
            similar_text($kind->getName(), $kindName, $percent);
            if ($percent >= $this->percent) {
                return $kind;
            }
        }
        return null;
    }

    private function GetBreed($casBreedId, $kind)
    {
        /** @var Breed */
        $breed = $this->defaultEntityManager->getRepository(Breed::class)->findOneBy(["externalId" => $casBreedId]);
        if ($breed == null && $casBreedId != null) {
            $conn = $this->casEntityManager->getConnection();
            $requestBreed = "select * from dictionary.breed where id='{$casBreedId}'";
            $stmt = $conn->prepare($requestBreed);
            $casBreed = $stmt->executeQuery()->fetchAllAssociative()[0];
            $breed = $this->GetSimilarBreed($casBreed["name"], $kind);
            if ($breed != null) {
                return $breed;
            }
            $breed = new Breed();
            $breed->setExternalId($casBreed['id']);
            $breed->setName($casBreed['name']);
            $breed->setDeleted(false);
            $breed->setUpdatedAt(new DateTime($casBreed['createdat']));
            $breed->setCreatedAt(new DateTime($casBreed['updatedat']));
            $breed->setKind($kind);
            try {
                $this->defaultEntityManager->persist($breed);
                $this->defaultEntityManager->flush();
            } catch (Exception $exception) {
                throw $exception;
            }
        }
        return $breed;
    }

    private function GetSimilarBreed($breedName, $kindId)
    {
        $breeds = $this->defaultEntityManager->getRepository(Breed::class)->findAll(["kindId" => $kindId]);
        foreach ($breeds as $breed) {
            similar_text($breed->getName(), $breedName, $percent);
            if ($percent >= $this->percent) {
                return $breed;
            }
        }
        return null;
    }

    private function GetColour($casColourId)
    {
        /** @var Colour */
        $colour = $this->defaultEntityManager->getRepository(Colour::class)->findOneBy(["externalId" => $casColourId]);
        if ($colour == null) {
            $conn = $this->casEntityManager->getConnection();
            $requestColour = "select * from dictionary.colour where id='{$casColourId}'";
            $stmt = $conn->prepare($requestColour);
            $casColour = $stmt->executeQuery()->fetchAllAssociative()[0];
            $colour = $this->GetSimilarColour($casColour["name"]);
            if ($colour != null) {
                return $colour;
            }
            $colour = new Colour();
            $colour->setExternalId($casColour['id']);
            $colour->setName($casColour['name']);
            $colour->setDeleted(false);
            $colour->setUpdatedAt(new DateTime($casColour['createdat']));
            $colour->setCreatedAt(new DateTime($casColour['updatedat']));

            try {
                $this->defaultEntityManager->persist($colour);
                $this->defaultEntityManager->flush();
            } catch (Exception $exception) {
                throw $exception;
            }
        }
        return $colour;
    }

    private function GetSimilarColour($colourName)
    {
        $colours = $this->defaultEntityManager->getRepository(Colour::class)->findAll();
        foreach ($colours as $colour) {
            similar_text($colour->getName(), $colourName, $percent);
            if ($percent >= $this->percent) {
                return $colour;
            }
        }
        return null;
    }

    private function GetLocation($casLocationId)
    {
        /** @var Location */
        $location = $this->defaultEntityManager->getRepository(Location::class)->findOneBy(["externalId" => $casLocationId]);
        if ($location == null) {
            $conn = $this->casEntityManager->getConnection();
            $requestLocation = "select * from mart_info_geo.locations where id='{$casLocationId}'";
            $stmt = $conn->prepare($requestLocation);
            $casLocation = $stmt->executeQuery()->fetchAllAssociative()[0];
            $location = new Location();
            $location->setExternalId($casLocation['id']);
            if ($casLocation['name']) {
                $location->setName($casLocation['name']);
            }
            $location->setDeleted(false);
            $location->setUpdatedAt(new DateTime($casLocation['createdat']));
            $location->setCreatedAt(new DateTime($casLocation['updatedat']));
            $location->setAddress($casLocation['address']);
            $location->setCenter($casLocation['center']);
            $location->setFiasId($casLocation['fias_id']);
            $location->setRegionFiasId($casLocation['region_fias_id']);
            $location->setAreaFiasId($casLocation['area_fias_id']);
            $location->setCityFiasId($casLocation['city_fias_id']);
            $location->setCityDistrictFiasId($casLocation['city_district_fias_id']);
            $location->setSettlementFiasId($casLocation['settlement_fias_id']);
            $location->setStreetFiasId($casLocation['street_fias_id']);
            $location->setHouseFiasId($casLocation['house_fias_id']);
            if ($casLocation['parent_id']) {
                $location->setParent($this->GetLocation($casLocation['parent_id']));
            }

            try {
                $this->defaultEntityManager->persist($location);
                $this->defaultEntityManager->flush();
            } catch (Exception $exception) {
                throw $exception;
            }
            $location->setPaths($this->GetPaths($casLocation['id'], $location));
            $location->setCircles($this->GetCircles($casLocation['id'], $location));
        }
        return $location;
    }

    private function GetPaths($casLocationId, $location)
    {
        $resultPaths = [];
        $conn = $this->casEntityManager->getConnection();
        $requestPaths = "select * from mart_info_geo.paths where location_id='{$casLocationId}'";
        $stmt = $conn->prepare($requestPaths);
        $casPaths = $stmt->executeQuery()->fetchAllAssociative();
        foreach ($casPaths as $casPath) {
            /** @var Path */
            $path = $this->defaultEntityManager->getRepository(Path::class)->findOneBy(['externalId' => $casPath['id']]);
            if ($path == null) {
                $path = new Path();
                $path->setExternalId($casPath['id']);
                $path->setDeleted(false);
                $path->setLocation($location);
                $path->setGroupNum($casPath['group_num']);
                $path->setBuildOrder($casPath['build_order']);
                $path->setIncluded($casPath['included']);
                $path->setCreatedAt(new DateTime($casPath['createdAt']));
                $path->setUpdatedAt(new DateTime($casPath['updatedAt']));
                $path->setData($casPath['data']);

                try {
                    $this->defaultEntityManager->persist($path);
                    $this->defaultEntityManager->flush();
                } catch (Exception $exception) {
                    throw $exception;
                }
            }
            array_push($resultAnimals, $path);
        }
        return $resultPaths;
    }

    private function GetCircles($casLocationId, $location)
    {
        $resultCircles = [];
        $conn = $this->casEntityManager->getConnection();
        $requestCircles = "select * from mart_info_geo.circles where location_id='{$casLocationId}'";
        $stmt = $conn->prepare($requestCircles);
        $casCircles = $stmt->executeQuery()->fetchAllAssociative();
        foreach ($casCircles as $casCircle) {
            /** @var Circle */
            $circle = $this->defaultEntityManager->getRepository(Circle::class)->findOneBy(['externalId' => $casCircle['id']]);
            if ($circle == null) {
                $circle = new Circle();
                $circle->setExternalId($casCircle['id']);
                $circle->setDeleted(false);
                $circle->setCreatedAt(new DateTime($casCircle['createdAt']));
                $circle->setUpdatedAt(new DateTime($casCircle['updatedAt']));
                $circle->setLocation($location);
                $circle->setGroupNum($casCircle['group_num']);
                $circle->setBuildOrder($casCircle['build_order']);
                $circle->setIncluded($casCircle['included']);
                $circle->setCenter($casCircle['center']);
                $circle->setRadius($casCircle['radius']);

                try {
                    $this->defaultEntityManager->persist($circle);
                    $this->defaultEntityManager->flush();
                } catch (Exception $exception) {
                    throw $exception;
                }
            }
            array_push($resultCircles, $circle);
        }
        return $resultCircles;
    }

    private function GetStatusCode(string $code): VaccinationUploadStatusEnum
    {
        switch (strtoupper($code)) {
            case 'finished':
                return VaccinationUploadStatusEnum::getItem(VaccinationUploadStatusEnum::STATUS_FINISHED);
            case 'with_errors':
                return VaccinationUploadStatusEnum::getItem(VaccinationUploadStatusEnum::STATUS_FINISHED_WITH_ERRORS);
            case 'forming_response':
                return VaccinationUploadStatusEnum::getItem(VaccinationUploadStatusEnum::STATUS_FORMING_RESPONSE);
            case 'pending':
                return VaccinationUploadStatusEnum::getItem(VaccinationUploadStatusEnum::STATUS_PENDING);
            case 'response_formed':
                return VaccinationUploadStatusEnum::getItem(VaccinationUploadStatusEnum::STATUS_RESPONSE_FORMED);
            case 'response_uploaded':
                return VaccinationUploadStatusEnum::getItem(VaccinationUploadStatusEnum::STATUS_RESPONSE_UPLOADED);
            case 'sys_error':
                return VaccinationUploadStatusEnum::getItem(VaccinationUploadStatusEnum::STATUS_SYS_ERROR);
            default:
                return VaccinationUploadStatusEnum::getItem(VaccinationUploadStatusEnum::STATUS_UPLOADED);
        }
    }

    private function GetAnimalGender(string $gender = null): AnimalGenderEnum
    {
        switch (strtoupper($gender)) {
            case 'f':
                return AnimalGenderEnum::getItem(AnimalGenderEnum::FEMALE);
            case 'm':
                return AnimalGenderEnum::getItem(AnimalGenderEnum::MALE);
            default:
                return AnimalGenderEnum::getItem(AnimalGenderEnum::MALE);
        }
    }

    private function GetDoctor(string $vaccinationId)
    {
        $conn = $this->casEntityManager->getConnection();
        $request = "SELECT p.name, p.surname, p.patronymic FROM auth.person p 
                            JOIN vaccination.vaccination_person v ON p.id = v.person_id
                            WHERE v.vaccination_id ='{$vaccinationId}' LIMIT 1";
        $stmt = $conn->prepare($request);
        $casDoctor = $stmt->executeQuery()->fetchAllAssociative();

        $doctor = "{$casDoctor[0]['surname']} {$casDoctor[0]['name']} {$casDoctor[0]['patronymic']}";
        return $doctor;
    }
}

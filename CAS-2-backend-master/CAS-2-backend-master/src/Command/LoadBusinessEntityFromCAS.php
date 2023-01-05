<?php

namespace App\Command;

use App\Packages\Client\OAuthClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Reference\BusinesEntity;
use App\Packages\DBAL\Types\LegalFormsEnum;
use DateTime;
use App\Entity\Reference\SupervisedObjects;
use App\Entity\Reference\Station;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Exception;

class LoadBusinessEntityFromCAS extends Command
{
    use OutputStringCommandTrait;
    
    private bool $replace = false;
    protected static $defaultName = 'app:business-entity-load';

    private EntityManagerInterface $casEntityManager;
    private EntityManagerInterface $defaultEntityManager;

    public function __construct(EntityManagerInterface $casEntityManager, EntityManagerInterface $defaultEntityManager)
    {
        $this->casEntityManager = $casEntityManager;
        $this->defaultEntityManager = $defaultEntityManager;
        parent::__construct(self::$defaultName);
    }

    protected function configure()
    {
        $this->setDescription('Load business entity from CAS')
            ->addArgument('replace', InputArgument::OPTIONAL, 'Replace existed data?');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->replace = $input->getArgument('replace') == null ? false : true;

        if (!$output instanceof ConsoleOutputInterface) {
            throw new \LogicException('Эта команда принимает только экземпляр "ConsoleOutputInterface".');
        }

        $conn = $this->casEntityManager->getConnection();

        $requestCountEntities = 'select count(*) from structure.enterprise';
        $stmt = $conn->prepare($requestCountEntities);
        $total = $stmt->executeQuery()->fetchNumeric()[0];

        $requestEntities = 'select * from structure.enterprise';
        $stmt = $conn->prepare($requestEntities);
        $entities = $stmt->executeQuery()->fetchAllAssociative();

        $currentIteration = 1;
        $outputSection = $output->section();
        foreach ($entities as $businessEntity) {
            $outputSection->overwrite($this->GetOutputString($total, $currentIteration));
            $entity = $this->AddBusinesEntity($businessEntity);
            $enterpriseRequest = "select * from structure.supervised_object where enterprise_id = '" . $entity->getExternalId() . "'";
            $stmt = $conn->prepare($enterpriseRequest);
            $enterprises = $stmt->executeQuery()->fetchAllAssociative();

            foreach ($enterprises as $enterprise) {
                $this->AddSupervisedObjects($entity, $enterprise);
            }
            $currentIteration++;
        }
    }

    private function GetLegalForm(?string $form): LegalFormsEnum
    {
        switch (strtoupper($form)) {
            case 'ООО':
                return LegalFormsEnum::getItem(LegalFormsEnum::OOO);
            case 'ИП':
                return LegalFormsEnum::getItem(LegalFormsEnum::IP);
            case 'АО':
                return LegalFormsEnum::getItem(LegalFormsEnum::AO);
            case 'ПАО':
                return LegalFormsEnum::getItem(LegalFormsEnum::PAO);
            case 'НКО':
                return LegalFormsEnum::getItem(LegalFormsEnum::NKO);
            case 'ОП':
                return LegalFormsEnum::getItem(LegalFormsEnum::OP);
            case 'ЗАО':
                return LegalFormsEnum::getItem(LegalFormsEnum::ZAO);
            default:
                return LegalFormsEnum::getItem(LegalFormsEnum::OOO);
        }
    }

    private function AddBusinesEntity($businessEntity): BusinesEntity
    {
        /** @var BusinesEntity*/
        $entity = $this->defaultEntityManager->getRepository(BusinesEntity::class)->findOneBy(['externalId' => $businessEntity['id']]);
        if ($entity == null || $this->replace) {
            if ($entity == null) {
                $entity = new BusinesEntity();
            }
            $entity->setExternalId($businessEntity['id']);
            $entity->setName($businessEntity['name']);
            $entity->setLegalForms($this->GetLegalForm($businessEntity['opf_']));
            $entity->setInn($businessEntity['inn']);
            $entity->setOgrn($businessEntity['ogrn']);
            $entity->setLegalAddres($businessEntity['legal_address']);
            $entity->setRegistrationDate(new DateTime($businessEntity['registration_date']));
            $entity->setLiquidationDate(new DateTime($businessEntity['liquidation_date']));
            $entity->setHeadFullName($businessEntity['head']);
            $entity->setHeadOffice($businessEntity['head_office']);
            $entity->setComment($businessEntity['comment']);
            $entity->setKpp($businessEntity['kpp']);
            $entity->setPlanMonth($businessEntity['plan_month']);
            $entity->setPlanSkipYear($businessEntity['plan_skip_year']);
            $entity->setCheckingAccount($businessEntity['checking_account']);
            $entity->setCorAccount($businessEntity['cor_account']);
            $entity->setBank($businessEntity['bank']);
            $entity->setBik($businessEntity['bik']);
            $entity->setCreatedAt(new DateTime($businessEntity['createdat']));
            $entity->setUpdatedAt(new DateTime());
            $entity->setBusinessSize($businessEntity['business_size']);
            $entity->setWebsite($businessEntity['website']);
            $entity->setWorkingWithSocialObj($businessEntity['working_with_social_obj']);
            $entity->setLastCheck($businessEntity['last_check']);
            $entity->setRiskPoints($businessEntity['risk_points']);
            $entity->setDeleted(false);
            try {
                $this->defaultEntityManager->persist($entity);
                $this->defaultEntityManager->flush();
            } catch (Exception $exception) {
                var_dump($businessEntity);
                throw $exception;
            }
        }
        return $entity;
    }

    private function AddSupervisedObjects(BusinesEntity $entity, $enterprise): ?SupervisedObjects
    {
        /** @var SupervisedObjects*/
        $ent = $this->defaultEntityManager->getRepository(SupervisedObjects::class)->findOneBy(['externalId' => $enterprise['id']]);
        if ($ent == null || $this->replace) {
            $station = $this->GetStation($enterprise['station_id']);
            if ($station == null) return $ent;
            if ($ent == null) {
                $ent = new SupervisedObjects();
            }
            $ent->setExternalId($enterprise['id']);
            $ent->setName($enterprise['name']);
            $adress = $enterprise['address_'];
            if (isset($enterprise['location_id']) && $enterprise['location_id'] != null) {
                $adress_location = $this->GetAdress($enterprise['location_id']);
                $adress = $adress_location == null ? $adress : $adress_location;
            }
            $ent->setAddress($adress);
            $ent->setHeadFullName($enterprise['head']);
            $ent->setLatitude($enterprise['latitude_']);
            $ent->setLongitude($enterprise['longitude_']);
            $ent->setStation($station);
            $ent->setBusinessEntity($entity);
            $ent->setActivityKind($enterprise['activity_kind']);
            $ent->setComment($enterprise['comment']);
            $ent->setKpp($enterprise['kpp']);
            $ent->setHeadOffice($enterprise['head_office']);
            $ent->setCreatedAt(new DateTime($enterprise['createdat']));
            $ent->setUpdatedAt(new DateTime($enterprise['updatedat']));
            $ent->setCompartment($enterprise['compartment']);
            $ent->setEmail($enterprise['email']);
            $ent->setTelephoneNumber($enterprise['contact']);
            $ent->setDeleted(false);
            try {
                $this->defaultEntityManager->persist($ent);
                $this->defaultEntityManager->flush();
            } catch (Exception $exception) {
                var_dump($enterprise);
                throw $exception;
            }
        }
        return $ent;
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
                    $this->defaultEntityManager->persist($station);
                    $this->defaultEntityManager->flush();
                }
            }
        }
        return $station;
    }

    private function GetAdress(string $location): ?string
    {
        $casLocationRequest = "select * from mart_info_geo.locations where id = '" . $location . "'";
        $conn = $this->casEntityManager->getConnection();
        $stmt = $conn->prepare($casLocationRequest);
        $casLocations = $stmt->executeQuery()->fetchAllAssociative();
        if (is_array($casLocations) && count($casLocations) > 0) {
            return $casLocations[0]['address'];
        }
        return null;
    }
}

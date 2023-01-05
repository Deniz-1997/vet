<?php

namespace App\Entity\Reference;

use App\Entity\User\User;
use App\Packages\DBAL\Types\LegalFormsEnum;
use App\Repository\Reference\SupervisedObjectsRepository;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmUpdatedAtTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\OrmExternalIdTrait;
use App\Traits\ORMTraits\OrmIdTrait;

/**
 * @ORM\Table(schema="structure")
 * @ORM\Entity(repositoryClass=SupervisedObjectsRepository::class)
 */
class SupervisedObjects
{
    use OrmIdTrait, OrmNameTrait, OrmCreatedAtTrait, OrmDeletedTrait, OrmUpdatedAtTrait, OrmExternalIdTrait;

    /**
     * @var User|null
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @ORM\JoinColumn(name="user_id", nullable=true)
     */
    private ?User $user;

    /**
     * @var string|null Полный адрес строкой
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $address;

    /**
     * @var integer|null
     * @Groups({"default",})
     * @ORM\Column(type="integer", nullable=true)
     */
    private  ?int $latitude;

    /**
     * @var integer|null
     * @Groups({"default",})
     * @ORM\Column(type="integer", nullable=true)
     */
    private  ?int $longitude;

    /**
     * @var string|null КПП
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $kpp;

    /**
     * @var string|null Полноре имя  руководителя
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $headFullName;

    /**
     * @var string|null Должность
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $headOffice;

    /**
     * @var Station|null
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Station")
     * @ORM\JoinColumn(name="station_id", nullable=true)
     */
    private ?Station $station;

    /**
     * @var BusinesEntity|null
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\BusinesEntity")
     * @ORM\JoinColumn(name="businessEntity_id", nullable=true)
     */
    private ?BusinesEntity $businessEntity;

    /**
     * @var string|null емаил
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $email;

    /**
     * @var string|null Род деятельности
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true, length=2000)
     */
    private ?string $activityKind;

    /**
     * @var string|null Комментарий
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true, length=1000)
     */
    private ?string $comment;

    /**
     * @var string|null Телефон
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $telephoneNumber;

    /**
     * @var bool|null
     * @Groups({"default"})
     * @ORM\Column(type="boolean", nullable=true, options={"default":false})
     */
    private ?bool $internetConnection;

    /**
     * @var bool|null
     * @Groups({"default"})
     * @ORM\Column(type="boolean", nullable=true, options={"default":false})
     */
    private ?bool $issuesCertificates;

    /**
     * @var bool|null
     * @Groups({"default"})
     * @ORM\Column(type="boolean", nullable=true, options={"default":false})
     */
    private ?bool $pushingAvailable;

    /**
     * @var integer|null
     * @Groups({"default",})
     * @ORM\Column(type="integer", nullable=true, options={"default":0})
     */
    private  ?int $compartment;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user):self
    {
        $this->user = $user;
        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function  setAddress(?string $address): self
    {
        $this->address = $address;
        return $this;
    }

    public function getCompartment(): ?int
    {
        return $this->compartment;
    }

    public function  setCompartment(?int $compartment): self
    {
        $this->compartment = $compartment;
        return $this;
    }

    public function getLongitude(): ?int
    {
        return $this->longitude;
    }


    public function  setLongitude(?int $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function getLatitude(): ?int
    {
        return $this->latitude;
    }

    public function  setLatitude(?int $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getKpp(): ?string
    {
        return $this->kpp;
    }

    public function  setKpp(?string $kpp): self
    {
        $this->kpp = $kpp;
        return $this;
    }


    public function getHeadFullName(): ?string
    {
        return $this->headFullName;
    }

    public function  setHeadFullName(?string $headFullName): self
    {
        $this->headFullName = $headFullName;
        return $this;
    }

    public function getHeadOffice(): ?string
    {
        return $this->headOffice;
    }

    public function  setHeadOffice(?string $headOffice): self
    {
        $this->headOffice = $headOffice;
        return $this;
    }

    public function getStation(): ?Station
    {
        return $this->station;
    }

    public function setStation(?Station $station):self
    {
        $this->station = $station;
        return $this;
    }

    public function getBusinessEntity(): ?BusinesEntity
    {
        return $this->businessEntity;
    }

    public function setBusinessEntity(?BusinesEntity $businessEntity):self
    {
        $this->businessEntity = $businessEntity;
        return $this;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function  setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getActivityKind(): ?string
    {
        return $this->activityKind;
    }

    public function  setActivityKind(?string $activityKind): self
    {
        $this->activityKind = $activityKind;
        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;
        return $this;
    }

    public function getTelephoneNumber(): ?string
    {
        return $this->telephoneNumber;
    }

    public function  setTelephoneNumber(?string $telephoneNumber): self
    {
        $this->telephoneNumber = $telephoneNumber;
        return $this;
    }


    public function getInternetConnection(): ?bool
    {
        return $this->internetConnection;
    }

    public function  setInternetConnection(?bool $internetConnection): self
    {
        $this->internetConnection = $internetConnection;
        return $this;
    }

    public function getIssuesCertificates(): ?bool
    {
        return $this->issuesCertificates;
    }

    public function  setIssuesCertificates(?bool $issuesCertificates): self
    {
        $this->issuesCertificates = $issuesCertificates;
        return $this;
    }

    public function getPushingAvailable(): ?bool
    {
        return $this->pushingAvailable;
    }

    public function  setPushingAvailable(?bool $pushingAvailable): self
    {
        $this->pushingAvailable = $pushingAvailable;
        return $this;
    }

}

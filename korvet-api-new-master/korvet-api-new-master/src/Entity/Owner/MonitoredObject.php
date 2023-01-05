<?php

namespace App\Entity\Owner;


use App\Entity\Owner;
use App\Entity\Owner\Embeddable\Person;
use App\Entity\Reference\Owner\Activity;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;
use App\Entity\Embeddable\Address;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Owner\MonitoredObjectRepository")
 * @ORM\Table("monitored_objects")
 */
class MonitoredObject
{
    use OrmReferenceTrait;

    /**
     * @var Owner Владелец
     * @Groups({"detail", "post", "put", "patch"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Owner", fetch="EAGER")
     * @SWG\Property(description="Владелец")
     */
    private $owner;

    /**
     * @var string|null Телефон
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true, options={"default"=""})
     * @SWG\Property(type="string", description="Телефон")
     */
    private $phone;

    /**
     * @var string|null Email
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true, options={"default"=""})
     * @SWG\Property(type="string", description="Email")
     */
    private $email;

    /**
     * @var Address Адрес
     * @Groups({"default"})
     * @ORM\Embedded(class="App\Entity\Embeddable\Address", columnPrefix="address_")
     * @SWG\Property(ref=@Model(type="App\Entity\Embeddable\Address"), description="Адрес")
     */
    private $address;

    /**
     * @var bool Уполномоченным лицом является глава владельца
     * @Groups({"default"})
     * @ORM\Column(type="boolean", options={"default"=false})
     * @SWG\Property(type="boolean", description="Уполномоченным лицом является глава владельца")
     */
    private $responsibleIsOwnerHead = false;

    /**
     * @var Person|null Уполномоченное лицо
     * @Groups({"default"})
     * @ORM\Embedded(class="App\Entity\Owner\Embeddable\Person", columnPrefix="responsible_")
     * @SWG\Property(ref=@Model(type=App\Entity\Owner\Embeddable\Person::class), description="Уполномоченное лицо")
     */
    private $responsible;

    /**
     * @var Activity[] Виды деятельности
     * @Groups({"default"})
     * @ORM\ManyToMany(targetEntity="App\Entity\Reference\Owner\Activity")
     * @SWG\Property(description="Виды деятельности")
     */
    private $activities;

    /**
     * @var string Описание видов деятельности
     * @Groups({"default"})
     * @ORM\Column(type="string", options={"default"=""})
     * @SWG\Property(description="Описание видов деятельности")
     */
    private $customActivities = "";

    /**
     * @return Owner
     */
    public function getOwner(): Owner
    {
        return $this->owner;
    }

    /**
     * @param Owner $owner
     * @return MonitoredObject
     */
    public function setOwner(Owner $owner): MonitoredObject
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param null|string $phone
     * @return MonitoredObject
     */
    public function setPhone(?string $phone): MonitoredObject
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param null|string $email
     * @return MonitoredObject
     */
    public function setEmail(?string $email): MonitoredObject
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * @param Address $address
     * @return MonitoredObject
     */
    public function setAddress(Address $address): MonitoredObject
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return bool
     */
    public function isResponsibleIsOwnerHead(): bool
    {
        return $this->responsibleIsOwnerHead;
    }

    /**
     * @param bool $responsibleIsOwnerHead
     * @return MonitoredObject
     */
    public function setResponsibleIsOwnerHead(bool $responsibleIsOwnerHead): MonitoredObject
    {
        $this->responsibleIsOwnerHead = $responsibleIsOwnerHead;
        return $this;
    }

    /**
     * @return Person|null
     */
    public function getResponsible(): ?Person
    {
        return $this->responsible;
    }

    /**
     * @param Person|null $responsible
     * @return MonitoredObject
     */
    public function setResponsible(?Person $responsible): MonitoredObject
    {
        $this->responsible = $responsible;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getActivities()
    {
        return $this->activities;
    }

    /**
     * @param mixed $activities
     * @return MonitoredObject
     */
    public function setActivities($activities)
    {
        $this->activities = $activities;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomActivities(): string
    {
        return $this->customActivities;
    }

    /**
     * @param string $customActivities
     * @return MonitoredObject
     */
    public function setCustomActivities(string $customActivities): MonitoredObject
    {
        $this->customActivities = $customActivities;
        return $this;
    }


}

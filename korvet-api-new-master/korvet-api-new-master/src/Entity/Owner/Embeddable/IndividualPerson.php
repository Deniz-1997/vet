<?php

namespace App\Entity\Owner\Embeddable;


use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Embeddable()
 */
class IndividualPerson
{
    /**
     * @var bool|null Является ЛПХ (личным подсобным хозяйством)
     * @Groups({"default"})
     * @ORM\Column(type="boolean", nullable=true)
     * @SWG\Property(type="boolean", description="Является ЛПХ (личным подсобным хозяйством)")
     */
    private $household;

    /**
     * @var string|null Адрес содержания животных
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     * @SWG\Property(type="string", description="Адрес содержания животных")
     */
    private $householdAddress;

    /**
     * @var bool|null Адрес содержания животных совпадает с адресом проживания
     * @Groups({"default"})
     * @ORM\Column(type="boolean", nullable=true)
     * @SWG\Property(type="boolean", description="Адрес содержания животных совпадает с адресом проживания")
     */
    private $householdAddressIsPersonAddress;

    /**
     * @var bool|null Земля в аренде
     * @Groups({"default"})
     * @ORM\Column(type="boolean", nullable=true)
     * @SWG\Property(type="boolean", description="Земля в аренде")
     */
    private $householdInRent;

    /**
     * @var \DateTime|null Дата окончания аренды на землю
     * @Groups({"default"})
     * @ORM\Column(type="date", nullable=true)
     * @SWG\Property(type="string", description="Дата окончания аренды на землю", format="date", example="31.12.2050")
     */
    private $householdRentExpirationDate;

    /**
     * @return bool|null
     */
    public function getHousehold(): ?bool
    {
        return $this->household;
    }

    /**
     * @param bool|null $household
     */
    public function setHousehold(?bool $household): void
    {
        $this->household = $household;
    }

    /**
     * @return null|string
     */
    public function getHouseholdAddress(): ?string
    {
        return $this->householdAddress;
    }

    /**
     * @param null|string $householdAddress
     */
    public function setHouseholdAddress(?string $householdAddress): void
    {
        $this->householdAddress = $householdAddress;
    }

    /**
     * @return bool|null
     */
    public function getHouseholdAddressIsPersonAddress(): ?bool
    {
        return $this->householdAddressIsPersonAddress;
    }

    /**
     * @param bool|null $householdAddressIsPersonAddress
     */
    public function setHouseholdAddressIsPersonAddress(?bool $householdAddressIsPersonAddress): void
    {
        $this->householdAddressIsPersonAddress = $householdAddressIsPersonAddress;
    }

    /**
     * @return bool|null
     */
    public function getHouseholdInRent(): ?bool
    {
        return $this->householdInRent;
    }

    /**
     * @param bool|null $householdInRent
     */
    public function setHouseholdInRent(?bool $householdInRent): void
    {
        $this->householdInRent = $householdInRent;
    }

    /**
     * @return \DateTime|null
     */
    public function getHouseholdRentExpirationDate(): ?\DateTime
    {
        return $this->householdRentExpirationDate;
    }

    /**
     * @param \DateTime|null $householdRentExpirationDate
     */
    public function setHouseholdRentExpirationDate(?\DateTime $householdRentExpirationDate): void
    {
        $this->householdRentExpirationDate = $householdRentExpirationDate;
    }


}

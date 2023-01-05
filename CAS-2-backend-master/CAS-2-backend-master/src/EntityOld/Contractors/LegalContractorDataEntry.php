<?php

namespace App\EntityOld\Contractors;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="LegalContractorDataEntryRepository")
 * @ORM\Table(
 *     name="legal_contractor_data_entries",
 *     schema="contractors",
 * )
 */
class LegalContractorDataEntry
{
    /**
     *
     * @ORM\Id
     * @ORM\OneToOne(
     *     targetEntity="Contractor",
     *     inversedBy="legalDataEntry"
     *)
     * @ORM\JoinColumn(name="contractor_id", referencedColumnName="id")
     * @ORM\GeneratedValue(strategy="NONE")
     * @var string
     */
    private $contractor;

    /**
     *
     * @ORM\Column(name="full_name", type="string", length=512, nullable=false)
     * @Assert\Length(max="256")
     * @Assert\NotBlank()
     * @var string
     */
    private $fullName;

    /**
     *
     * @ORM\Column(name="short_name", type="string", length=16, nullable=true)
     * @Assert\Length(max="256")
     * @var string
     */
    private $shortName;

    /**
     *
     * @ORM\Column(name="ogrn", type="string", length=13, nullable=false)
     * @Assert\Length(max="13")
     * @Assert\NotBlank()
     * @var string
     */
    private $ogrn;

    /**
     *
     * @ORM\Column(name="inn", type="string", length=10, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(min="10", max="10")
     * @Assert\Regex("/^\d+$/", message="legal_data_entries.inn_value_failed")
     * @var string
     */
    private $inn;

    /**
     *
     * @ORM\Column(name="kpp", type="string", length=9, nullable=true)
     * @Assert\Length(max="9")
     * @var string
     */
    private $kpp;

    /**
     *
     * @ORM\Column(name="website", type="string", length=128, nullable=true)
     * @Assert\Length(max="128")
     * @var string
     */
    private $website;

    /**
     * @ORM\Column(name="registration_date", type="date", nullable=false)
     * @Assert\Date(message="common.this_is_not_date")
     * @Assert\NotBlank()
     */
    private $registrationDate;

    /**
     * @ORM\Column(name="liquidation_date", type="date", nullable=true)
     * @Assert\Date(message="common.this_is_not_date")
     */
    private $liquidationDate;

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->shortName ? $this->shortName : $this->fullName;
    }

    /**
     * Set contractor
     *
     * @param \MartInfo\VeterinaryBundle\EntityOld\Contractors\Contractor $contractor
     * @return LegalContractorDataEntry
     */
    public function setContractor(Contractor $contractor)
    {
        $this->contractor = $contractor;

        return $this;
    }

    /**
     * Get contractor
     *
     * @return \MartInfo\VeterinaryBundle\EntityOld\Contractors\Contractor
     */
    public function getContractor()
    {
        return $this->contractor;
    }

    /**
     * Set fullName
     *
     * @param string $fullName
     *
     * @return LegalContractorDataEntry
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set shortName
     *
     * @param string $shortName
     *
     * @return LegalContractorDataEntry
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;

        return $this;
    }

    /**
     * Get shortName
     *
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * Set ogrn
     *
     * @param string $ogrn
     *
     * @return LegalContractorDataEntry
     */
    public function setOgrn($ogrn)
    {
        $this->ogrn = $ogrn;

        return $this;
    }

    /**
     * Get ogrn
     *
     * @return string
     */
    public function getOgrn()
    {
        return $this->ogrn;
    }

    /**
     * Set inn
     *
     * @param string $inn
     *
     * @return LegalContractorDataEntry
     */
    public function setInn($inn)
    {
        $this->inn = $inn;

        return $this;
    }

    /**
     * Get inn
     *
     * @return string
     */
    public function getInn()
    {
        return $this->inn;
    }

    /**
     * Set kpp
     *
     * @param string $kpp
     *
     * @return LegalContractorDataEntry
     */
    public function setKpp($kpp)
    {
        $this->kpp = $kpp;

        return $this;
    }

    /**
     * Get kpp
     *
     * @return string
     */
    public function getKpp()
    {
        return $this->kpp;
    }

    /**
     * Set website
     *
     * @param string $website
     *
     * @return LegalContractorDataEntry
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set registrationDate
     *
     * @param \DateTime $registrationDate
     *
     * @return LegalContractorDataEntry
     */
    public function setRegistrationDate($registrationDate)
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    /**
     * Get registrationDate
     *
     * @return \DateTime
     */
    public function getRegistrationDate()
    {
        return $this->registrationDate;
    }

    /**
     * Set liquidationDate
     *
     * @param \DateTime $liquidationDate
     *
     * @return LegalContractorDataEntry
     */
    public function setLiquidationDate($liquidationDate)
    {
        $this->liquidationDate = $liquidationDate;

        return $this;
    }

    /**
     * Get liquidationDate
     *
     * @return \DateTime
     */
    public function getLiquidationDate()
    {
        return $this->liquidationDate;
    }
}

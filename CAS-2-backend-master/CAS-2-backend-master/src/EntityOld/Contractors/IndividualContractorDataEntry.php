<?php

namespace App\EntityOld\Contractors;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="IndividualContractorDataEntryRepository")
 * @ORM\Table(
 *     name="individual_contractor_data_entries",
 *     schema="contractors",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="individual_contractor_data_entries",
 *              columns={"passport_serial", "passport_number"}
 *          )
 *      }
 * )
 * @UniqueEntity(
 *     fields={"passportSerial", "passportNumber"},
 *     message="individual_data_entries.unique.failed",
 *     errorPath="passportNumber",
 *     repositoryMethod="findInterferingIndividualContractorDataEntriesUnique"
 * )
 * @UniqueEntity(
 *     fields={"snils"},
 *     message="individual_data_entries.unique.snils_failed",
 *     errorPath="snils",
 *     repositoryMethod="findInterferingSnilsIndividualContractorDataEntriesUnique"
 * )
 * @UniqueEntity(
 *     fields={"inn"},
 *     message="individual_data_entries.unique.inn_failed",
 *     errorPath="inn",
 *     repositoryMethod="findInterferingInnIndividualContractorDataEntriesUnique"
 * )
 */
class IndividualContractorDataEntry
{

    /**
     *
     * @ORM\Id
     * @ORM\OneToOne(
     *     targetEntity="Contractor",
     *     inversedBy="individualDataEntry"
     * )
     * @ORM\JoinColumn(name="contractor_id", referencedColumnName="id")
     * @ORM\GeneratedValue(strategy="NONE")
     * @var Contractor
     */
    private $contractor;

    /**
     *
     * @ORM\Column(name="surname", type="string", length=32, nullable=false)
     * @Assert\Length(max="32")
     * @Assert\NotBlank
     * @var string
     */
    private $surname;

    /**
     *
     * @ORM\Column(name="name", type="string", length=32, nullable=false)
     * @Assert\Length(max="32")
     * @Assert\NotBlank
     * @var string
     */
    private $name;

    /**
     *
     * @ORM\Column(name="patronymic", type="string", length=32, nullable=true)
     * @Assert\Length(max="32")
     * @var string
     */
    private $patronymic;

    /**
     *
     * @ORM\Column(name="passport_number", type="string", length=10, nullable=true)
     * @Assert\Length(max="10")
     * @var string
     */
    private $passportNumber;

    /**
     *
     * @ORM\Column(name="passport_serial", type="string", length=8, nullable=true)
     * @Assert\Length(max="8")
     * @var string
     */
    private $passportSerial;

    /**
     *
     * @ORM\Column(name="snils", type="string", length=11, nullable=true)
     * @Assert\Length(max="11")
     * @var string
     */
    private $snils;

    /**
     *
     * @ORM\Column(name="inn", type="string", length=12, nullable=true)
     * @Assert\Length(min="12", max="12")
     * @Assert\Regex("/^\d+$/", message="individual_data_entries.inn_value_failed")
     * @var string
     */
    private $inn;

    /**
     *
     * @ORM\Column(name="passport_date", type="date", nullable=true)
     * @Assert\Date(message="common.this_is_not_date")
     * @var \DateTime
     */
    private $passportDate;

    /**
     *
     * @ORM\Column(name="passport_division", type="string", length=256, nullable=true)
     * @Assert\Length(max="256")
     * @var string
     */
    private $passportDivision;

    /**
     *
     * @ORM\Column(name="passport_division_code", type="string", length=6, nullable=true)
     * @Assert\Length(max="6")
     * @var string
     */
    private $passportDivisionCode;

    /**
     * @ORM\Column(name="is_run_lph", type="boolean", nullable=true)
     * @var bool
     */
    private $isRunLph;

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return trim($this->surname  . ' ' . $this->name . ' ' . $this->patronymic);
    }

    /**
     * Set surname
     *
     * @param string $surname
     *
     * @return IndividualContractorDataEntry
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return IndividualContractorDataEntry
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set patronymic
     *
     * @param string $patronymic
     *
     * @return IndividualContractorDataEntry
     */
    public function setPatronymic($patronymic)
    {
        $this->patronymic = $patronymic;

        return $this;
    }

    /**
     * Get patronymic
     *
     * @return string
     */
    public function getPatronymic()
    {
        return $this->patronymic;
    }

    /**
     * Set passportNumber
     *
     * @param string $passportNumber
     *
     * @return IndividualContractorDataEntry
     */
    public function setPassportNumber($passportNumber)
    {
        $this->passportNumber = $passportNumber;

        return $this;
    }

    /**
     * Get passportNumber
     *
     * @return string
     */
    public function getPassportNumber()
    {
        return $this->passportNumber;
    }

    /**
     * Set passportSerial
     *
     * @param string $passportSerial
     *
     * @return IndividualContractorDataEntry
     */
    public function setPassportSerial($passportSerial)
    {
        $this->passportSerial = $passportSerial;

        return $this;
    }

    /**
     * Get passportSerial
     *
     * @return string
     */
    public function getPassportSerial()
    {
        return $this->passportSerial;
    }

    /**
     * Set snils
     *
     * @param string $snils
     *
     * @return IndividualContractorDataEntry
     */
    public function setSnils($snils)
    {
        $this->snils = $snils;

        return $this;
    }

    /**
     * Get snils
     *
     * @return string
     */
    public function getSnils()
    {
        return $this->snils;
    }

    /**
     * Set inn
     *
     * @param string $inn
     *
     * @return IndividualContractorDataEntry
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
     * Set passportDate
     *
     * @param \DateTime $passportDate
     *
     * @return IndividualContractorDataEntry
     */
    public function setPassportDate($passportDate)
    {
        $this->passportDate = $passportDate;

        return $this;
    }

    /**
     * Get passportDate
     *
     * @return \DateTime
     */
    public function getPassportDate()
    {
        return $this->passportDate;
    }

    /**
     * Set passportDivision
     *
     * @param string $passportDivision
     *
     * @return IndividualContractorDataEntry
     */
    public function setPassportDivision($passportDivision)
    {
        $this->passportDivision = $passportDivision;

        return $this;
    }

    /**
     * Get passportDivision
     *
     * @return string
     */
    public function getPassportDivision()
    {
        return $this->passportDivision;
    }

    /**
     * Set passportDivisionCode
     *
     * @param string $passportDivisionCode
     *
     * @return IndividualContractorDataEntry
     */
    public function setPassportDivisionCode($passportDivisionCode)
    {
        $this->passportDivisionCode = $passportDivisionCode;

        return $this;
    }

    /**
     * Get passportDivisionCode
     *
     * @return string
     */
    public function getPassportDivisionCode()
    {
        return $this->passportDivisionCode;
    }

    /**
     * Set contractor
     *
     * @param \MartInfo\VeterinaryBundle\EntityOld\Contractors\Contractor $contractor
     *
     * @return IndividualContractorDataEntry
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
     * @return bool
     */
    public function isRunLph()
    {
        return $this->isRunLph;
    }

    /**
     * @param bool $isRunLph
     * @return IndividualContractorDataEntry
     */
    public function setIsRunLph($isRunLph)
    {
        $this->isRunLph = $isRunLph;
        return $this;
    }


}

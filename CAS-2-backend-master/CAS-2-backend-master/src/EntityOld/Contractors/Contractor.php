<?php

namespace App\EntityOld\Contractors;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use MartInfo\SupervisedObjectRegistryBundle\Entity\SupervisedObject;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="ContractorRepository")
 * @ORM\Table(
 *     name="contractors",
 *     schema="contractors",
 * )
 */
class Contractor
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    const CONTRACTOR_PER_PAGE = 50;
    const INDIVIDUAL_CONTRACTOR_TYPE = 0;
    const INDIVIDUAL_ENTREPRENEUR_CONTRACTOR_TYPE = 1;
    const LEGAL_CONTRACTOR_TYPE = 2;

    /**
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @var string
     */
    private $id;

    /**
     * @ORM\Column(name="comment", type="string", length=2000)
     * @Assert\Length(max=2000)
     */
    private $comment;

    /**
     * @ORM\Column(name="type", type="integer", nullable=false)
     * @Assert\NotBlank()
     * @Assert\Choice(
     *     choices = { "0", "1", "2" },
     *     message = "contractor.error_type"
     * )
     */
    private $type;

    /**
     * @ORM\Column(name="name", type="string", length=512)
     * @Assert\NotBlank()
     * @var string
     */
    private $name;

    /**
     * @ORM\OneToOne(
     *     targetEntity="IndividualContractorDataEntry",
     *     cascade={"persist", "remove"},
     *     mappedBy="contractor"
     * )
     * @Assert\Valid
     * @var IndividualContractorDataEntry
     */
    private $individualDataEntry;

    /**
     * @ORM\OneToOne(
     *     targetEntity="IndividualEntrepreneurDataEntry",
     *     cascade={"persist", "remove"},
     *     mappedBy="contractor"
     * )
     * @Assert\Valid
     * @var IndividualEntrepreneurDataEntry
     */
    private $individualEntrepreneurDataEntry;

    /**
     * @ORM\OneToOne(
     *     targetEntity="LegalContractorDataEntry",
     *     cascade={"persist", "remove"},
     *     mappedBy="contractor"
     * )
     * @Assert\Valid
     * @var LegalContractorDataEntry
     */
    private $legalDataEntry;

    /**
     * @ORM\OneToMany(targetEntity="Contact", mappedBy="contractor", cascade={"persist", "remove"})
     */
    private $contacts;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contacts = new ArrayCollection();
    }

    public function __toString()
    {
        if ($this->name === null) {
            $this->setName();
        }

        $caption = $this->name;

        if (!is_null($this->individualDataEntry)) {
            $caption .= ' ' . $this->individualDataEntry->getInn();
        }

        if (!is_null($this->individualEntrepreneurDataEntry)) {
            $caption .= ' ' . $this->individualEntrepreneurDataEntry->getInn();
        }

        if (!is_null($this->legalDataEntry)) {
            $caption .= ' ' . $this->legalDataEntry->getInn();
        }

        return trim($caption);
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return Contractor
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getTypeName()
    {
        switch ($this->type) {
            case self::INDIVIDUAL_CONTRACTOR_TYPE :
                return 'Физическое лицо';
            case self::INDIVIDUAL_ENTREPRENEUR_CONTRACTOR_TYPE :
                return 'Индивидуальный предприниматель';
            case self::LEGAL_CONTRACTOR_TYPE :
                return 'Юридическое лицо';
            default :
                return null;
        }

    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Contractor
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Contractor
     */
    public function setName($name = null)
    {
        if (empty($name)) {

            if ($this->individualDataEntry) {
                $this->name = $this->individualDataEntry->__toString();
            } else if ($this->individualEntrepreneurDataEntry) {
                $this->name = $this->individualEntrepreneurDataEntry->__toString();
            } else if ($this->legalDataEntry) {
                $this->name = $this->legalDataEntry->__toString();
            }

            return $this;
        }

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
     * Get individualDataEntry
     *
     * @return \MartInfo\VeterinaryBundle\EntityOld\Contractors\IndividualContractorDataEntry
     */
    public function getIndividualDataEntry()
    {
        return $this->individualDataEntry;
    }

    /**
     * Set individualEntrepreneurDataEntry
     *
     * @param IndividualEntrepreneurDataEntry $individualEntrepreneurDataEntry
     *
     * @return Contractor
     */
    public function setIndividualEntrepreneurDataEntry(
        IndividualEntrepreneurDataEntry $individualEntrepreneurDataEntry = null)
    {
        $this->individualEntrepreneurDataEntry = $individualEntrepreneurDataEntry;
        if ($individualEntrepreneurDataEntry) {
            $individualEntrepreneurDataEntry->setContractor($this);
        }

        return $this;
    }

    /**
     * Get individualEntrepreneurDataEntry
     *
     * @return \MartInfo\VeterinaryBundle\EntityOld\Contractors\IndividualEntrepreneurDataEntry
     */
    public function getIndividualEntrepreneurDataEntry()
    {
        return $this->individualEntrepreneurDataEntry;
    }

    /**
     * Set legalDataEntry
     * @param \MartInfo\VeterinaryBundle\EntityOld\Contractors\LegalContractorDataEntry $legalDataEntry
     *
     * @return Contractor
     */
    public function setLegalDataEntry(LegalContractorDataEntry $legalDataEntry = null)
    {
        $this->legalDataEntry = $legalDataEntry;
        if ($legalDataEntry) {
            $legalDataEntry->setContractor($this);
        }

        return $this;
    }

    /**
     * Get legalDataEntry
     *
     * @return \MartInfo\VeterinaryBundle\EntityOld\Contractors\LegalContractorDataEntry
     */
    public function getLegalDataEntry()
    {
        return $this->legalDataEntry;
    }

    /**
     * Add contract
     *
     * @param \MartInfo\VeterinaryBundle\EntityOld\Contractors\Contact $contact
     *
     * @return Contractor
     */
    public function addContact(Contact $contact)
    {
        $this->contacts[] = $contact;

        return $this;
    }

    /**
     * Remove contract
     *
     * @param \MartInfo\VeterinaryBundle\EntityOld\Contractors\Contact $contact
     */
    public function removeContact(Contact $contact)
    {
        $this->contacts->removeElement($contact);
    }

    /**
     * Get contracts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContacts()
    {
        return $this->contacts;
    }


    /**
     * Add bankAccount
     *
     * @param \MartInfo\VeterinaryBundle\EntityOld\Contractors\BankAccount $bankAccount
     *
     * @return Contractor
     */
    public function addBankAccount(BankAccount $bankAccount)
    {
        $this->bankAccounts[] = $bankAccount;

        return $this;
    }

    /**
     * Remove bankAccount
     *
     * @param \MartInfo\VeterinaryBundle\EntityOld\Contractors\BankAccount $bankAccount
     */
    public function removeBankAccount(BankAccount $bankAccount)
    {
        $this->bankAccounts->removeElement($bankAccount);
    }

    /**
     * Get bankAccounts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBankAccounts()
    {
        return $this->bankAccounts;
    }
}

<?php

namespace App\EntityOld\Contractors;

use Doctrine\ORM\Mapping as ORM;
use MartInfo\VeterinaryBundle\EntityOld\Dictionary\Bank;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="BankAccountRepository")
 * @ORM\Table(
 *     name="bank_accounts",
 *     schema="contractors",
 * )
 */
class BankAccount
{
    /**
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @var string
     */
    private $id;

    /**
     * @ORM\Column(name="bank_account", type="string", length=32)
     * @Assert\Length(max="32")
     * @var string
     */
    private $bankAccount;

//    /**
//     * @ORM\ManyToOne(
//     *     targetEntity="MartInfo\VeterinaryBundle\EntityOld\Contractors\Contractor",
//     *     inversedBy="bankAccounts"
//     * )
//     * @ORM\JoinColumn(name="contractor_id", referencedColumnName="id", nullable=false)
//     */
//    private $contractor;

//    /**
//     * @ORM\ManyToOne(targetEntity="MartInfo\VeterinaryBundle\EntityOld\Dictionary\Bank")
//     * @ORM\JoinColumn(name="bank_id", referencedColumnName="id", nullable=false)
//     * @Assert\NotBlank
//     */
//    private $bank;

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
     * Set bankAccount
     *
     * @param string $bankAccount
     *
     * @return BankAccount
     */
    public function setBankAccount($bankAccount)
    {
        $this->bankAccount = $bankAccount;

        return $this;
    }

    /**
     * Get bankAccount
     *
     * @return string
     */
    public function getBankAccount()
    {
        return $this->bankAccount;
    }

    /**
     * Set contractor
     *
     * @param \MartInfo\VeterinaryBundle\EntityOld\Contractors\Contractor $contractor
     *
     * @return BankAccount
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


   //  /**
 //    * Set bank
 //    *
 //    * @param \MartInfo\VeterinaryBundle\EntityOld\Dictionary\Bank $bank
 //    *
  //   * @return BankAccount
 //    */
 //   public function setBank(Bank $bank)
 //   {
 //       $this->bank = $bank;
//
 //       return $this;
 //   }

 //   /**
 //    * Get bank
  //   *
 //    * @return \MartInfo\VeterinaryBundle\EntityOld\Dictionary\Bank
  //   */
  //  public function getBank()
  //  {
 //       return $this->bank;
 //   }
}

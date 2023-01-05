<?php

namespace App\Entity\Cash;

use OpenApi\Annotations as SWG;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmCreatedAtTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmDeletedTrait;
use Symfony\Component\Validator\Constraints as Assert;
use App\Packages\Annotation\ExternalEntityReference;
use App\Interfaces\CashierUserInterface;

/**
 * @ORM\Table("cash_receipt_changes", schema="cash")
 * @ORM\Entity(repositoryClass="App\Repository\Cash\CashReceiptChangesRepository")
 */
class CashReceiptChanges
{
    use OrmIdTrait, OrmCreatedAtTrait;

    /**
     * @var CashierUserInterface 
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User", inversedBy="user", cascade={"detach"})
     * @ORM\JoinColumn(nullable=false, name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var CashReceipt 
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Cash\CashReceipt", inversedBy="сashReceipt", cascade={"detach"})
     * @ORM\JoinColumn(nullable=false, name="cash_receipt_id", referencedColumnName="id")
     */
    private CashReceipt $сashReceipt;

    /**
     * @var string 
     * @ORM\Column(type="text")
     * @SWG\Property(description="Тип изменения")
     */
    private string $type;

    /**
     * @return CashierUserInterface
     */
    public function getUser(): CashierUserInterface
    {
        return $this->user;
    }

    /**
     * @param \App\Interfaces\CashierUserInterface $user
     * @return CashReceiptChanges
     */
    public function setUser(CashierUserInterface $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return CashReceipt
     */
    public function getCashReceipt(): CashReceipt
    {
        return $this->сashReceipt;
    }

    /**
     * @param CashReceipt
     * @return CashReceiptChanges
     */
    public function setCashReceipt(CashReceipt $сashReceipt): self
    {
        $this->сashReceipt = $сashReceipt;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return CashReceiptChanges
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }
}

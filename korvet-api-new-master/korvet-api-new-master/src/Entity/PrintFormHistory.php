<?php

namespace App\Entity;

use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmUpdatedAtTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as SWG;
use App\Entity\User\User;

/**
 * @ORM\Table (schema="public")
 * @ORM\Entity(repositoryClass="App\Repository\PrintFormHistoryRepository")
 */
class PrintFormHistory
{
    use OrmDeletedTrait, OrmIdTrait, OrmUpdatedAtTrait;

    /**
     * @var string тип печатной формы
     * @Groups({"default"})
     * @ORM\Column(type="text", nullable=false)
     * @SWG\Property(description="Печатная форма")
     */
    private $printForm;

     /**
     * @var User
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @ORM\JoinColumn(nullable=true)
     * @SWG\Property(description="Пользователь")
     */
    private $user;

    public function __construct()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return string
     */
    public function getPrintForm()
    {
        return $this->printReport;
    }

    /**
     * @param string $printForm
     */
    public function setPrintForm(string $printForm): self
    {
        $this->printForm = $printForm;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
    
}

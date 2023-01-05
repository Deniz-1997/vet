<?php


namespace App\Entity\Reference\Appointment;

use App\Entity\Reference\Unit;
use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(schema="appointment")
 * @ORM\Entity(repositoryClass="App\Repository\Reference\Appointment\AppointmentTemplateRepository")
 */
class AppointmentTemplate
{
    use OrmReferenceTrait, OrmSortTrait;

    /**
     * @var array
     * @Groups({"default"})
     * @ORM\Column(type="json", options={"jsonb":true}, nullable=true)
     * @SWG\Property(description="Список номенклатур")
     */
    private $products;

    /**
     * @var Collection |Unit[] Клиника
     * @Groups({"default"})
     * @Assert\NotBlank()
     * @SWG\Property(description="Клиника")
     * @ORM\ManyToMany (targetEntity="App\Entity\Reference\Unit")
     */
    private $unit;

    public function __construct()
    {
        $this->unit = new ArrayCollection();

    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @param array $products
     * @return AppointmentTemplate
     */
    public function setProducts(array $products): AppointmentTemplate
    {
        $this->products = $products;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getUnit(): Collection
    {
        return $this->unit;
    }


    /**
     * @param Unit $unit
     * @return AppointmentTemplate
     */
    public function addUnit(Unit $unit): self
    {
        if (!$this->unit->contains($unit)) {
            $this->unit[] = $unit;
        }

        return $this;
    }

    /**
     * @param Unit $unit
     * @return AppointmentTemplate
     */
    public function removeUnit(Unit $unit): self
    {
        if ($this->unit->contains($unit)) {
            $this->unit->removeElement($unit);
        }

        return $this;
    }

}

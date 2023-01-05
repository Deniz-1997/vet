<?php

namespace App\Entity\Reference\Pet;

use App\Entity\Reference\Breed;
use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\Complex\OrmReferenceTrait;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Annotations as SWG;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Pet\PetLearRepository")
 * @ORM\Table(name="reference_pet_lear", schema="reference")
 */
class PetLear
{
    use OrmReferenceTrait, OrmSortTrait;

    /**
     * @var Breed|null
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Breed")
     * @SWG\Property(
     *     ref=@Model(type=\App\Entity\Reference\Breed::class),
     *     description="Тип животного"
     * )
     */
    private $breed;

    /**
     * @var boolean
     * @Groups("default")
     * @SWG\Property(description="Удален", type="boolean", default=false)
     */
    private $isDeleted = false;

    /**
     * @return Breed|null
     */
    public function getBreed(): ?Breed
    {
        return $this->breed;
    }

    /**
     * @param Breed|null $breed
     * @return PetLear
     */
    public function setBreed(?Breed $breed): PetLear
    {
        $this->breed = $breed;
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsDeleted(): bool
    {
        return $this->deleted;
    }
}

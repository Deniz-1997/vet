<?php

namespace App\Entity\Pet;

use App\Entity\Owner;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Validator\Constraint\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Pet\PetToOwnerRepository")
 * @ORM\Table("pet_to_owner", schema="pet")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"owner", "pet"}, message="petToOwner.unique_pet")
 */
class PetToOwner
{
    use OrmIdTrait, OrmDeletedTrait;

    /**
     * @var Owner Владелец
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Owner", inversedBy="pets")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     * @SWG\Property(description="Владелец")
     */
    private $owner;

    /**
     * @var Pet Животное
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Pet\Pet", inversedBy="owners")
     * @ORM\JoinColumn(name="pet_id", referencedColumnName="id")
     * @SWG\Property(description="Животное")
     */
    private $pet;

    /**
     * @var bool Основной владелец
     * @Groups({"default"})
     * @ORM\Column(type="boolean", nullable=false, options={"default": false})
     * @SWG\Property(description="Основной владелец", type="boolean")
     */
    private $mainOwner = false;

    // ORM EVENT LISTENERS

    /**
     * @ORM\PreUpdate()
     * @ORM\PrePersist()
     * @throws \Doctrine\ORM\ORMException
     */
    public function preSave(LifecycleEventArgs $event)
    {
        // получим всех владельцев этого животного
        $em = $event->getEntityManager();
        $items = $em->getRepository(static::class)->findBy([
            'pet' => $event->getEntity()->getPet()
        ]);

        /** @var PetToOwner $item */
        foreach ($items as $item) {
            // если уже есть такой владелец - выкидываем исключение
            if (!$item->isDeleted() && $item !== $event->getEntity() && $item->getOwner() === $event->getEntity()->getOwner()) {
                throw new \RuntimeException('pet_to_owner.owner_already_exists');
            }
        }

        if ($event->getEntity()->isMainOwner()) {
            // снимаем галочку "Основной владелец", если текущий установлен основным
            /** @var PetToOwner $item */
            foreach ($items as $item) {
                if ($item !== $event->getEntity() && $item->isMainOwner()) {
                    $em->persist($item);
                    $item->setMainOwner(false);
                    $em->flush();
                }
            }
        } elseif (count($items) == 0) {
            // если это единственный и первый владелец - назначаем его основным принудительно
            $event->getEntity()->setMainOwner(true);
        }
    }

    // GETTERS AND SETTERS

    /**
     * @return \App\Entity\Owner
     */
    public function getOwner(): \App\Entity\Owner
    {
        return $this->owner;
    }

    /**
     * @param \App\Entity\Owner $owner
     * @return PetToOwner
     */
    public function setOwner(\App\Entity\Owner $owner): PetToOwner
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * @return Pet
     */
    public function getPet(): Pet
    {
        return $this->pet;
    }

    /**
     * @param Pet $pet
     * @return PetToOwner
     */
    public function setPet(Pet $pet): PetToOwner
    {
        $this->pet = $pet;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMainOwner(): bool
    {
        return $this->mainOwner;
    }

    /**
     * @param bool $mainOwner
     * @return PetToOwner
     */
    public function setMainOwner(bool $mainOwner): PetToOwner
    {
        $this->mainOwner = $mainOwner;
        return $this;
    }

    
}

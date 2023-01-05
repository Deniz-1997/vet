<?php

namespace App\Packages\EventSubscriber;

use App\Entity\Owner;
use App\Entity\Pet\Pet;
use App\Entity\Pet\PetToOwner;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Packages\EventDispatcher\EventRequest;

class PetSameAddressSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * PetSameAddressSubscriber constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param EventRequest $event
     */
    public function onChangeOwner(EventRequest $event)
    {
        /** @var Owner $owner */
        $owner = $event->getData();

        if (!$owner->getPets()) {
            return;
        }

        /** @var PetToOwner $petToOwner */
        foreach ($owner->getPets() as $petToOwner) {
            $pet = $petToOwner->getPet();

            if (!$petToOwner->isMainOwner()) {
                continue;
            }

            if (!$pet->isSameAddress()) {
                continue;
            }

            $pet->setAddress($owner->getAddress());

            $this->entityManager->persist($pet);
        }

        $this->entityManager->flush();
    }

    /**
     * @param EventRequest $event
     */
    public function onChangePet(EventRequest $event)
    {
        /** @var Pet $pet */
        $pet = $event->getData();

        if (!$pet->isSameAddress()) {
            return;
        }

        /** @var PetToOwner $petToOwner */
        foreach ($pet->getOwners() as $petToOwner) {
            if ($petToOwner->isMainOwner()) {
                $pet->setAddress($petToOwner->getOwner()->getAddress());

                $this->entityManager->persist($pet);
            }
        }

        $this->entityManager->flush();
    }

    /**
     * @param EventRequest $event
     */
    public function onChangePetToOwner(EventRequest $event)
    {
        /** @var PetToOwner $petToOwner */
        $petToOwner = $event->getData();

        if ($petToOwner->isMainOwner() && $petToOwner->getPet() && $petToOwner->getPet()->isSameAddress()) {
            $pet = $petToOwner->getPet();
            $pet->setAddress($petToOwner->getOwner()->getAddress());

            $this->entityManager->persist($pet);
            $this->entityManager->flush();
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onAfterProcessAppEntityPetPetPost'  => 'onChangePet',
            'onAfterProcessAppEntityPetPetToOwnerPost'  => 'onChangePetToOwner',
            'onAfterProcessAppEntityPetPetToOwnerPatch'  => 'onChangePetToOwner',
            'onAfterProcessAppEntityPetPetToOwnerPut'  => 'onChangePetToOwner',
            'onAfterProcessAppEntityOwnerPost'  => 'onChangeOwner',
            'onAfterProcessAppEntityOwnerPatch' => 'onChangeOwner',
            'onAfterProcessAppEntityOwnerPut'   => 'onChangeOwner',
        ];
    }
}

<?php

namespace App\Packages\EventSubscriber;

use App\Entity\Pet\Pet;
use App\Entity\Reference\VeterinaryPassportType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Packages\EventDispatcher\EventRequest;
use App\Exception\ApiException;
use App\Service\DeserializeService;

class PetSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;
      /** @var DeserializeService */
      private $deserializeService;

    /**
     * PetSameAddressSubscriber constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager,  DeserializeService $deserializeService)
    {
        $this->entityManager = $entityManager;
        $this->deserializeService = $deserializeService;
    }

    /**
     * @param EventRequest $event
     * @throws \Exception
     */
    public function onChangePet(EventRequest $event)
    {
        /** @var Pet $pet */
        $pet = $event->getData();

        if ($pet->getIsDead()) {
            if (!$pet->getIsDeadCreatedAt()) {
                $pet->setIsDeadCreatedAt(new \DateTime());
            }
        } else {
            //если животное не мертво обнуляем параметры
            $pet->setDateOfDeath(null);
            $pet->setAnimalDeath(null);
            $pet->setIsDeadCreatedAt(null);
        }

        $this->entityManager->flush();
    }

    /**
     * @param EventRequest $event
     *
     * @throws ApiException
     */
    public function onBeforeChangePet(EventRequest $event)
    {
        $petData = $event->getData();
        if(is_array($petData) && $petData['content']){
            $pet = $this->deserializeService->deserialize($petData['content'], Pet::class, 'json');
            $this->validatePetVeterinaryPasswordType($pet);
        }
    }

    /**
     * @param EventRequest $event
     *
     * @throws ApiException
     */
    public function onBeforeCreatePet(EventRequest $event)
    {
        /** @var Pet $pet */
        $pet = $this->deserializeService->deserialize($event->getData(), Pet::class, 'json');
        $this->validatePetVeterinaryPasswordType($pet);
    }

    /**
     * @param Pet $pet
     *
     * @throws ApiException
     */
    private function validatePetVeterinaryPasswordType(Pet $pet)
    {
        if ($pet->getVeterinaryPassportNumber() && $pet->getVeterinaryPassportType() instanceof VeterinaryPassportType && ($mask = $pet->getVeterinaryPassportType()->getNumberMask())) {
            $mask = trim($mask, '/');
            if (!preg_match("/$mask/", $pet->getVeterinaryPassportNumber())) {
                throw new ApiException('error.pet.vaterinaryPassword.regexMask', 'Error_400', 'veterinaryPassportNumber', Response::HTTP_BAD_REQUEST);
            }
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onAfterProcessAppEntityPetPetPost'  => 'onChangePet',
            'onAfterProcessAppEntityPetPetPatch'  => 'onChangePet',
            'onBeforeProcessAppEntityPetPetPost'  => 'onBeforeCreatePet',
            'onBeforeProcessAppEntityPetPetPatch'  => 'onBeforeChangePet',
            'onBeforeProcessAppEntityPetPetPut'  => 'onBeforeChangePet',
        ];
    }
}

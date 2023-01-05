<?php

namespace App\Packages\EventSubscriber;

use App\Entity\Reference\VeterinaryPassportType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Packages\EventDispatcher\EventRequest;

/**
 * Class VaterinaryPasswordTypeSubscriber
 */
class VaterinaryPasswordTypeSubscriber implements EventSubscriberInterface
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
     * @throws Exception
     */
    public function onBeforeChange(EventRequest $event)
    {
        /** @var VeterinaryPassportType $veterinaryPasswordType */
        $veterinaryPasswordType = $event->getData();

        if ($veterinaryPasswordType->getIsDefault()) {
            $this->controllIsDefault();
        }
    }

    private function controllIsDefault()
    {
        $listEntities = $this->entityManager->getRepository(VeterinaryPassportType::class)->findBy(['isDefault' => true]);
        /** @var VeterinaryPassportType $entity */
        foreach ($listEntities as $entity) {
            $entity->setIsDefault(false);
            $this->entityManager->persist($entity);
        }
        $this->entityManager->flush();
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onBeforeProcessAppEntityReferenceVeterinaryPassportTypePatch'  => 'onBeforeChange',
            'onBeforeProcessAppEntityReferenceVeterinaryPassportTypePut'  => 'onBeforeChange',
            'onBeforeProcessAppEntityReferenceVeterinaryPassportTypePost'  => 'onBeforeChange',
        ];
    }
}

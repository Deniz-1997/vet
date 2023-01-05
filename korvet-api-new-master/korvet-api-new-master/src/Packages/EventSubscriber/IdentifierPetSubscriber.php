<?php

namespace App\Packages\EventSubscriber;

use App\Entity\Pet\Identifier;
use App\Entity\Pet\IdentifierHistory;
use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Packages\EventDispatcher\EventRequest;
use App\Packages\Response\BaseResponse;

/**
 * Class IdentifierPetSubscriber
 */
class IdentifierPetSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * IdentifierPetSubscriber constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct (EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            "onAfterProcessAppEntityPetIdentifierPost"  => 'onCreateNewPetIdentifier',
            'onAfterProcessAppEntityPetIdentifierPatch' => 'onCreateNewPetIdentifier',
            'onAfterProcessAppEntityPetIdentifierPut'   => 'onCreateNewPetIdentifier',
        );
    }

    /**
     * @param EventRequest $event
     */
    public function onCreateNewPetIdentifier(EventRequest $event)
    {
        /** @var Identifier $identifier */
        $identifier = $event->getData();

        $history = new IdentifierHistory();
        $history->setDate(new \DateTime());
        $history->setIdentifierType($identifier->getType());
        $history->setPet($identifier->getPet());
        $history->setValue($identifier->getValue());

        $this->entityManager->persist($history);
        $this->entityManager->flush();
    }
}

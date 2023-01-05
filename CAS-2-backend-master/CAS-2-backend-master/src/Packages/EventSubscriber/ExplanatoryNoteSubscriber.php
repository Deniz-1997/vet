<?php

declare(strict_types=1);

namespace App\Packages\EventSubscriber;

use App\Entity\User\User;
use App\Entity\ExplanatoryNote;
use App\Service\UploadFile\UploadFileServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Packages\EventDispatcher\EventRequest;
use App\Service\DeserializeService;
use App\Service\SerializeService;

class ExplanatoryNoteSubscriber implements EventSubscriberInterface
{
    private User $currentUser;
    private EntityManagerInterface $entityManager;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
        if ($tokenStorage && $tokenStorage->getToken()) {
            $this->currentUser = $tokenStorage->getToken()->getUser();
        }
    }

    public static function getSubscribedEvents(): array
    {
        return array(
            'onAfterProcessAppEntityExplanatoryNotePost' => 'onAfterActionAddItem',
            //            'onBeforeProcessAppEntityExplanatoryNotePatch' => 'onBeforeActionPatchItem',
        );
    }

    public function onAfterActionAddItem(EventRequest $event)
    {
        /** @var ExplanatoryNote $explanatoryNote */
        $explanatoryNote = $event->getData();
        $explanatoryNote->setUser($this->currentUser);
        $this->entityManager->persist($explanatoryNote);
        $this->entityManager->flush();
        $event->setData($explanatoryNote);
    }
}

<?php

namespace App\Packages\EventSubscriber;

use App\Entity\Laboratory\ResearchDocument;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Packages\EventDispatcher\EventRequest;
use App\Service\DeserializeService;
use App\Enum\DocumentStateEnum;
use App\Packages\DBAL\Types\ResearchStateEnum;
use App\Service\Laboratory\ResearchHistoryService;

class ResearchDocumentSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var ResearchHistoryService */
    private $historyService;


    /**
     * ResearchDocumentSubscriber constructor.
     * @param EntityManagerInterface $entityManager
     * @param ResearchHistoryService $historyService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ResearchHistoryService $historyService
    ) {
        $this->entityManager = $entityManager;
        $this->historyService = $historyService;
    }

    /**
     * @param EventRequest $event
     * @throws \Exception
     */
    public function onAfterResearchChanged(EventRequest $event)
    {
        /** @var ResearchDocument $researchDocument */
        $researchDocument = $event->getData();

        if ($researchDocument->getState()->code === DocumentStateEnum::REGISTERED) {
            $researchDocument->setStatus(ResearchStateEnum::getItem(ResearchStateEnum::DONE));
            $researchDocument->setDateEnd(new \DateTime());
            $this->entityManager->persist($researchDocument);
            $this->entityManager->flush();
        }
        $this->historyService->AddResearchChanges($researchDocument);
    }
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onAfterProcessAppEntityLaboratoryResearchDocumentPut' => 'onAfterResearchChanged',
            'onAfterProcessAppEntityLaboratoryResearchDocumentPatch' => 'onAfterResearchChanged',
            'onAfterProcessAppEntityLaboratoryResearchDocumentPost' => 'onAfterResearchChanged',
        ];
    }
}

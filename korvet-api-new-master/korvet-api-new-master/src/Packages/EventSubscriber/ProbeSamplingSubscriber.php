<?php

namespace App\Packages\EventSubscriber;

use App\Entity\Laboratory\ProbeSampling;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Packages\EventDispatcher\EventRequest;
use App\Service\Laboratory\ResearchHistoryService;

class ProbeSamplingSubscriber implements EventSubscriberInterface
{
    /** @var ResearchHistoryService */
    private $historyService;

    /**
     * ProbeSamplingSubscriber constructor.
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
    public function onAfterProbeSamplingChanged(EventRequest $event)
    {
        /** @var ProbeSampling $probeSampling */
        $probeSampling = $event->getData();

        foreach ($probeSampling->getProbeItems() as $probe) {
            foreach ($probe->getResearchDocuments() as $research) {
                $this->historyService->AddResearchChanges($research);
            }
        }
    }
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onAfterProcessAppEntityLaboratoryProbeSamplingPut' => 'onAfterProbeSamplingChanged',
            'onAfterProcessAppEntityLaboratoryProbeSamplingPatch' => 'onAfterProbeSamplingChanged',
            'onAfterProcessAppEntityLaboratoryProbeSamplingPost' => 'onAfterProbeSamplingChanged',
        ];
    }
}

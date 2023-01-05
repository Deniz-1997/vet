<?php

namespace App\Service\Laboratory;

use App\Entity\Laboratory\ResearchDocument;
use App\Entity\Laboratory\ResearchHistory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ResearchHistoryService
{
     /** @var EntityManagerInterface */
     private $entityManager;
     /** @var User|null */
     private $currentUser = null;


    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $token = $tokenStorage->getToken();
        $this->currentUser = $token ? $token->getUser() : null;
    }

    /**
     * @param ResearchDocument $researchDocument
     */
    public function AddResearchChanges(ResearchDocument $researchDocument): void
    {
         /** @var ResearchHistory $history */
         $history = new ResearchHistory();
         $history->setUser($this->currentUser);
         $history->setResearchDocumentId($researchDocument->getId());
         $history->setStatus($researchDocument->getStatus());
         $history->setUpdatedAt(new \DateTime());
         $this->entityManager->persist($history);
         $this->entityManager->flush();
    }
}

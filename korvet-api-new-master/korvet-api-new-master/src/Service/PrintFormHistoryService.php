<?php

namespace App\Service;

use App\Entity\PrintFormHistory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PrintFormHistoryService
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
     * @param string $printForm
     */
    public function AddChanges(string $printForm): void
    {
         /** @var PrintFormHistory $history */
         $history = new PrintFormHistory();
         $history->setUser($this->currentUser);
         $history->setPrintForm($printForm);
         $history->setUpdatedAt(new \DateTime());
         $this->entityManager->persist($history);
         $this->entityManager->flush();
    }
}

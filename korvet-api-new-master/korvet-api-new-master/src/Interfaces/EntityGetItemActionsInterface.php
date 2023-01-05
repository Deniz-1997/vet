<?php


namespace App\Interfaces;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Interface EntityGetItemActionsInterface
 */
interface EntityGetItemActionsInterface
{
    public function getItemActions(?UserInterface $user, array $actions): array;
}

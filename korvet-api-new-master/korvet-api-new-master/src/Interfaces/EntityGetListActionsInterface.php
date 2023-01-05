<?php


namespace App\Interfaces;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Interface EntityActionsTrait
 */
interface EntityGetListActionsInterface
{
    public function getListActions(?UserInterface $user, array $actions): array;
}

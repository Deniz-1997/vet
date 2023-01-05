<?php

namespace App\Packages\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use App\Packages\Security\SecurityOperations;

/**
 * Class DefaultSecurityVoter
 */
class DefaultSecurityVoter extends Voter implements VoterInterface
{

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed  $subject   The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject): bool
    {
        return in_array($attribute, [
            SecurityOperations::ADD_OPERATION,
            SecurityOperations::DELETE_OPERATION,
            SecurityOperations::LIST_OPERATION,
            SecurityOperations::READ_OPERATION,
            SecurityOperations::UPDATE_OPERATION
        ]);
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param mixed  $subject
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** Allow anonymous call for legacy services without authorization including */
        return true;
    }
}

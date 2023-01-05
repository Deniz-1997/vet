<?php

namespace App\Packages\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authorization\Voter\RoleVoter as BaseRoleVoter;
use Symfony\Component\Security\Core\Role\Role;
use App\Packages\Security\SecurityOperations;


class RoleVoter extends BaseRoleVoter implements VoterInterface
{

    /** @var string */
    private string $prefix;

    /**
     * RoleVoter constructor.
     *
     * @param string $prefix
     */
    public function __construct(string $prefix = 'ROLE_')
    {
        parent::__construct($prefix);

        $this->prefix = $prefix;
    }

    /**
     * {@inheritdoc}
     */
    public function vote(TokenInterface $token, $subject, array $attributes): int
    {
        $roles = $this->extractRoles($token);
        foreach ($attributes as $attribute) {
            if (!is_string($attribute) || 0 !== strpos($attribute, $this->prefix)) {
                continue;
            }

            foreach ($roles as $role) {
                if ($role == 'ROLE_ROOT') {
                    return VoterInterface::ACCESS_GRANTED;
                }

                if ($attribute === $role) {
                    return VoterInterface::ACCESS_GRANTED;
                }
            }
        }
        return parent::vote($token, $subject, $attributes);
    }

    protected function extractRoles(TokenInterface $token): array
    {
        return $token->getRoleNames();
    }
}

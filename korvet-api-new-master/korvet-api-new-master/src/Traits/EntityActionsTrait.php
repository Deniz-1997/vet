<?php

namespace App\Traits;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

trait EntityActionsTrait
{
    /**
     * @Groups({"default"})
     * @var Action[]
     */
    private $actions = [];

    /**
     * @param Action[] $actions
     * @return $this
     */
    public function setActions(array $actions): self
    {
        $this->actions = $actions;

        return $this;
    }

    /**
     * @return Action[]
     */
    public function getActions(): array
    {
        return $this->actions;
    }
}

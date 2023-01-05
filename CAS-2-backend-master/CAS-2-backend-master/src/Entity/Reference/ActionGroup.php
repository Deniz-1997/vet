<?php

namespace App\Entity\Reference;

use App\Traits\ORMTraits\OrmCodeTrait;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Packages\Annotation\IgnoreDeleted;
use App\Packages\Annotation\SerializeNestedIgnore;

/**
 * Class ActionGroup
 * @ORM\Table(schema="action")
 * @ORM\Entity(repositoryClass="App\Repository\Reference\ActionGroupRepository")
 */
class ActionGroup
{
    use OrmIdTrait, OrmNameTrait, OrmCodeTrait, OrmDeletedTrait, OrmSortTrait;

    /**
     * @IgnoreDeleted()
     * @var ActionGroup|null
     * @Groups({"default"})
     * @ORM\JoinColumn(nullable=true)
     * @SerializeNestedIgnore()
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\ActionGroup")
     */
    private $parent;

    /**
     * @IgnoreDeleted()
     * @var Action[]
     * @Groups({"api.actionGroup"})
     * @ORM\ManyToMany(targetEntity="App\Entity\Reference\Action", mappedBy="groups")
     */
    private $actions;

    /**
     * ActionGroup constructor.
     */
    public function __construct()
    {
        $this->actions = new ArrayCollection();
    }

    /**
     * @return Action[]
     */
    public function getActions()
    {
        $actions = !$this->actions instanceof Collection ? new ArrayCollection($this->actions ?? []) : $this->actions;
        return $actions->filter(function(Action $action) {
            return !$action->isDeleted();
        });
    }

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
     * @param Action $action
     * @return $this
     */
    public function addAction(Action $action)
    {
        if (!$this->actions->contains($action)) {
            $action->addGroup($this);
            $this->actions->add($action);
        }

        return $this;
    }

    /**
     * @return ActionGroup|null
     */
    public function getParent(): ?ActionGroup
    {
        return $this->parent;
    }

    /**
     * @param ActionGroup|null $parent
     * @return $this
     */
    public function setParent(?ActionGroup $parent): self
    {
        $this->parent = $parent;

        return $this;
    }
}

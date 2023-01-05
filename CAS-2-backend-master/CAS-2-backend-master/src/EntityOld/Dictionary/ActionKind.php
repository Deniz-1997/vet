<?php

namespace App\EntityOld\Dictionary;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Dictionary\ActionKindRepository")
 * @ORM\Table(
 *     name="action_kind",
 *     schema="dictionary"
 * )
 */
class ActionKind
{
    /**
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @var string
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(name="sort_order", type="smallint", nullable=false)
     * @Assert\NotBlank()
     * @var integer
     */
    private $sortOrder;

    /**
     * @ORM\OneToMany(targetEntity="App\EntityOld\Dictionary\Action", mappedBy="actionKind")
     * @var ArrayCollection
     */
    private $actions;

    /**
     * ActionKind constructor.
     */
    public function __construct()
    {
        $this->actions = new ArrayCollection();
    }


    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return ActionKind
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ActionKind
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * @param int $sortOrder
     * @return ActionKind
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param Action $action
     * @return ActionKind
     */
    public function addAction(Action $action)
    {
        $this->actions->add($action);
        return $this;
    }

    /**
     * @param Action $action
     * @return ActionKind
     */
    public function removeAction(Action $action)
    {
        $this->actions->removeElement($action);
        return $this;
    }

}

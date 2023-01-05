<?php


namespace App\Entity\Reference;

use App\Packages\DBAL\Types\ActionTypeEnum;
use App\Entity\Embeddable\ActionConfirmation;
use App\Entity\Embeddable\ButtonSettings;
use App\Entity\Embeddable\Entity;
use App\Traits\ORMTraits\OrmCodeTrait;
use App\Packages\Annotation\IgnoreDeleted;
use App\Packages\Annotation\SerializeNestedIgnore;
use App\Traits\ORMTraits\OrmDeletedTrait;
use App\Traits\ORMTraits\OrmIdTrait;
use App\Traits\ORMTraits\OrmNameTrait;
use App\Traits\ORMTraits\OrmSortTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Annotations as SWG;


/**
 * Class Action
 * @ORM\Table(schema="action")
 * @ORM\Entity(repositoryClass="App\Repository\Reference\ActionRepository")
 */
class Action
{
    use OrmIdTrait, OrmNameTrait, OrmCodeTrait, OrmDeletedTrait, OrmSortTrait;

    /**
     * @IgnoreDeleted()
     * @var Action|null
     * @Groups({"default"})
     * @ORM\JoinColumn(nullable=true)
     * @SerializeNestedIgnore()
     * @ORM\ManyToOne(targetEntity="App\Entity\Reference\Action", inversedBy="items", cascade={"persist"})
     */
    private $parent;

    /**
     * @IgnoreDeleted()
     * @var Action[]
     * @Groups({"default"})
     * @SerializeNestedIgnore()
     * @ORM\OneToMany(targetEntity="App\Entity\Reference\Action", mappedBy="parent", cascade={"persist"})
     */
    private $items;

    /**
     * @var Action[]
     * @Groups({"default"})
     * @SerializeNestedIgnore()
     * @ORM\ManyToMany(targetEntity="App\Entity\Reference\Action")
     */
    private $additionalActions;

    /**
     * @var ActionGroup[]
     * @Groups({"default"})
     * @SerializeNestedIgnore()
     * @ORM\ManyToMany(targetEntity="App\Entity\Reference\ActionGroup", inversedBy="actions")
     */
    private $groups;

    /**
     * @var object[]
     * @Groups({"detail", "post", "patch", "put"})
     */
    private $roles;

    /**
     * @var ButtonSettings
     * @Assert\Valid()
     * @SWG\Property(ref=@Model(type=ButtonSettings::class))
     * @Groups({"default"})
     * @ORM\Embedded(class=ButtonSettings::class)
     */
    private $buttonSettings;

    /**
     * @var ActionConfirmation
     * @SWG\Property(ref=@Model(type=ActionConfirmation::class))
     * @Groups({"default"})
     * @ORM\Embedded(class=ActionConfirmation::class)
     */
    private $confirmation;

    /**
     * @var ActionTypeEnum
     * @Assert\Expression(expression="this.getType() and this.getType().code", message="action.type.not_empty")
     * @Groups({"default"})
     * @ORM\Column(type="App\Packages\DBAL\Types\ActionTypeEnum", nullable=false, options={"default": ActionTypeEnum::NONE})
     */
    private $type;

    /**
     * @var string|null
     * @Groups({"default"})
     * @ORM\Column(type="string", nullable=true)
     */
    private $url;

    /**
     * @var Entity
     * @Groups({"default"})
     * @SWG\Property(ref=@Model(type="App\Entity\Embeddable\Entity"))
     * @ORM\Embedded(class="App\Entity\Embeddable\Entity", columnPrefix="entity_")
     */
    private $entityClass;

    /**
     * @var bool
     * @Groups({"default"})
     * @ORM\Column(type="boolean")
     */
    private $itemsCountEnabled;

    /**
     * @var int|null
     * @Groups({"default"})
     * @ORM\Column(type="integer", nullable=true)
     */
    private $itemsCount;

    /**
     * @var boolean
     * @Groups({"default"})
     * @ORM\Column(type="boolean")
     */
    private $getListEnabled;

    /**
     * @var boolean
     * @Groups({"default"})
     * @ORM\Column(type="boolean")
     */
    private $viewItemEnabled;

    /**
     * @var boolean
     * @Groups({"default"})
     * @ORM\Column(type="boolean")
     */
    private $getItemEnabled;

    /**
     * @Groups({
     *     "default"
     * })
     *
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Action constructor.
     */
    public function __construct()
    {
        $this->groups = new ArrayCollection();
        $this->roles = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->additionalActions = new ArrayCollection();
        $this->entityClass = new Entity();
    }

    /**
     * @return Action|null
     */
    public function getParent(): ?Action
    {
        return $this->parent;
    }

    /**
     * @param Action|null $parent
     * @return $this
     */
    public function setParent(?Action $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Action[]
     */
    public function getItems()
    {
        $items = !$this->items instanceof Collection ? new ArrayCollection($this->items ?? []) : $this->items;

        return $items->filter(function(Action $action) {
            return !$action->isDeleted();
        });
    }

    /**
     * @param Action[] $items
     * @return $this
     */
    public function setItems(array $items): self
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @return ActionGroup[]
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param ActionGroup[] $groups
     * @return $this
     */
    public function setGroups(array $groups): self
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * @param ActionGroup $group
     * @return $this
     */
    public function addGroup(ActionGroup $group)
    {
        if (!$this->groups->contains($group)) {
            $group->addAction($this);
            $this->groups->add($group);
        }

        return $this;
    }

    /**
     * @return object[]
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param object[] $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return ButtonSettings
     */
    public function getButtonSettings(): ?ButtonSettings
    {
        return $this->buttonSettings;
    }

    /**
     * @param ButtonSettings $buttonSettings
     * @return $this
     */
    public function setButtonSettings(?ButtonSettings $buttonSettings): self
    {
        $this->buttonSettings = $buttonSettings;

        return $this;
    }

    /**
     * @return ActionConfirmation
     */
    public function getConfirmation(): ?ActionConfirmation
    {
        return $this->confirmation;
    }

    /**
     * @param ActionConfirmation $confirmation
     * @return $this
     */
    public function setConfirmation(?ActionConfirmation $confirmation): self
    {
        $this->confirmation = $confirmation;

        return $this;
    }

    /**
     * @return ActionTypeEnum
     */
    public function getType(): ?ActionTypeEnum
    {
        return $this->type;
    }

    /**
     * @param ActionTypeEnum $type
     * @return $this
     */
    public function setType(ActionTypeEnum $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     * @return $this
     */
    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Entity
     */
    public function getEntityClass(): Entity
    {
        return $this->entityClass;
    }

    /**
     * @param Entity $entityClass
     * @return $this
     */
    public function setEntityClass(Entity $entityClass): self
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    /**
     * @return bool
     */
    public function isItemsCountEnabled(): bool
    {
        return $this->itemsCountEnabled;
    }

    /**
     * @param bool $itemsCountEnabled
     * @return $this
     */
    public function setItemsCountEnabled(bool $itemsCountEnabled): self
    {
        $this->itemsCountEnabled = $itemsCountEnabled;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getItemsCount(): ?int
    {
        return $this->itemsCount;
    }

    /**
     * @param int|null $itemsCount
     * @return $this
     */
    public function setItemsCount(?int $itemsCount): self
    {
        $this->itemsCount = $itemsCount;

        return $this;
    }

    /**
     * @return bool
     */
    public function isGetListEnabled(): bool
    {
        return $this->getListEnabled;
    }

    /**
     * @param bool $getListEnabled
     * @return $this
     */
    public function setGetListEnabled(bool $getListEnabled): self
    {
        $this->getListEnabled = $getListEnabled;

        return $this;
    }

    /**
     * @return bool
     */
    public function isViewItemEnabled(): bool
    {
        return $this->viewItemEnabled;
    }

    /**
     * @param bool $viewItemEnabled
     * @return $this
     */
    public function setViewItemEnabled(bool $viewItemEnabled): self
    {
        $this->viewItemEnabled = $viewItemEnabled;

        return $this;
    }

    /**
     * @return bool
     */
    public function isGetItemEnabled(): bool
    {
        return $this->getItemEnabled;
    }

    /**
     * @param bool $getItemEnabled
     * @return $this
     */
    public function setGetItemEnabled(bool $getItemEnabled): self
    {
        $this->getItemEnabled = $getItemEnabled;

        return $this;
    }

    /**
     * @return Action[]
     */
    public function getAdditionalActions()
    {
        return $this->additionalActions;
    }

    /**
     * @param Action[] $additionalActions
     * @return $this
     */
    public function setAdditionalActions(array $additionalActions): self
    {
        $this->additionalActions = $additionalActions;

        return $this;
    }
}

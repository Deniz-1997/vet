<?php

namespace App\Entity\Owner;


use App\Entity\Owner;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Traits\ORMTraits\OrmIdTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Owner\FarmMemberRepository")
 * @ORM\Table("owner_farm_members")
 */
class FarmMember
{
    use OrmIdTrait;

    /**
     * @var Owner Владелец
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Owner", inversedBy="farmMembers")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     * @SWG\Property(description="Владелец")
     */
    private $owner;

    /**
     * @var Owner\Embeddable\FarmMember|null Участник КФХ
     * @Groups({"default"})
     * @ORM\Embedded(class="App\Entity\Owner\Embeddable\FarmMember", columnPrefix=false)
     * @SWG\Property(ref=@Model(type=App\Entity\Owner\Embeddable\FarmMember::class), description="Участник КФХ")
     */
    private $member;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Owner|null
     */
    public function getOwner(): ?Owner
    {
        return $this->owner;
    }

    /**
     * @param Owner $owner
     */
    public function setOwner(Owner $owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return Embeddable\FarmMember|null
     */
    public function getMember(): ?Embeddable\FarmMember
    {
        return $this->member;
    }

    /**
     * @param Embeddable\FarmMember|null $member
     */
    public function setMember(Embeddable\FarmMember $member)
    {
        $this->member = $member;
    }
}

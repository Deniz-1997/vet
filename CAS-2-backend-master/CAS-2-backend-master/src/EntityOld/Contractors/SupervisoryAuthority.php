<?php

namespace App\EntityOld\Contractors;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as SWG;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class SupervisoryAuthority
 * @ORM\Entity(repositoryClass="App\Repository\Contractors\SupervisoryAuthorityRepository")
 * @ORM\Table(name="supervisory_authorities", schema="contractors", indexes={
 *          @ORM\Index(name="supervisory_authorities_parent_id_idx", columns={"parent_id"}),
 *          @ORM\Index(name="supervisory_authorities_contractor_id_idx", columns={"contractor_id"})
 *      }
 * )
 */
class SupervisoryAuthority
{
    use TimestampableEntity;

    /**
     * @var string $id
     *
     * @Groups({"default"})
     * @SWG\Property(type="guid", example="d3fec963-539e-4dfd-8abc-517673aaa7a8")
     *
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private string $id;

    /**
     * @var string $name
     *
     * @ORM\Column(type="string", name="name", length=512, nullable=true)
     * @Assert\Length(max="512")
     */
    private string $name;

    /**
     * @var string $okpo
     *
     * @ORM\Column(type="string", name="okpo", length=36, nullable=true)
     * @Assert\Length(max="36")
     */
    private $okpo;

    /**
     * @var string $okved
     *
     * @ORM\Column(type="string", name="okved", length=36, nullable=true)
     * @Assert\Length(max="36")
     */
    private $okved;

    /**
     * @var string $okato
     *
     * @ORM\Column(type="string", name="okato", length=36, nullable=true)
     * @Assert\Length(max="36")
     */
    private $okato;

    /**
     * @var string $okogu
     *
     * @ORM\Column(type="string", name="okogu", length=36, nullable=true)
     * @Assert\Length(max="36")
     */
    private $okogu;

    /**
     * @var string $okopf
     *
     * @ORM\Column(type="string", name="okopf", length=36, nullable=true)
     * @Assert\Length(max="36")
     */
    private $okopf;

    /**
     * @var string $okfs
     *
     * @ORM\Column(type="string", name="okfs", length=36, nullable=true)
     * @Assert\Length(max="36")
     */
    private $okfs;

    /**
     * @var bool $deactivated
     * @ORM\Column(type="boolean", name="deactivated", nullable=false, options={"default" = FALSE})
     */
    private bool $deactivated;

    /**
     * @var int|null $notificationId
     * @ORM\Column(name="notification_id", type="integer", nullable=true)
     */
    private $notificationId;


    public function __construct()
    {
        $this->deactivated = false;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return is_null($this->name) ? "" : $this->name;
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set name
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get name
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set okpo
     * @param string $okpo
     */
    public function setOkpo(string $okpo): void
    {
        $this->okpo = $okpo;
    }

    /**
     * Get okpo
     * @return string
     */
    public function getOkpo(): ?string
    {
        return $this->okpo;
    }

    /**
     * Set okved
     * @param string $okved
     */
    public function setOkved(string $okved): void
    {
        $this->okved = $okved;
    }

    /**
     * Get okved
     * @return string
     */
    public function getOkved(): ?string
    {
        return $this->okved;
    }

    /**
     * Set okato
     * @param string $okato
     */
    public function setOkato(string $okato): void
    {
        $this->okato = $okato;
    }

    /**
     * Get okato
     * @return string
     */
    public function getOkato(): ?string
    {
        return $this->okato;
    }

    /**
     * Set okogu
     * @param string $okogu
     */
    public function setOkogu(string $okogu): void
    {
        $this->okogu = $okogu;
    }

    /**
     * Get okogu
     * @return string
     */
    public function getOkogu(): ?string
    {
        return $this->okogu;
    }

    /**
     * Set okopf
     * @param string $okopf
     */
    public function setOkopf(string $okopf): void
    {
        $this->okopf = $okopf;
    }

    /**
     * Get okopf
     * @return string
     */
    public function getOkopf(): ?string
    {
        return $this->okopf;
    }

    /**
     * Set okfs
     * @param string $okfs
     */
    public function setOkfs(string $okfs)
    {
        $this->okfs = $okfs;
    }

    /**
     * Get okfs
     * @return string
     */
    public function getOkfs(): ?string
    {
        return $this->okfs;
    }

    /**
     * @return int
     */
    public function getNotificationId(): ?int
    {
        return $this->notificationId;
    }

    /**
     * @param int $notificationId
     */
    public function setNotificationId(?int $notificationId): void
    {
        $this->notificationId = $notificationId;
    }
}

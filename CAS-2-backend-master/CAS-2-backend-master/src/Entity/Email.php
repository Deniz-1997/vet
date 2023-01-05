<?php

namespace App\Entity;

use App\Enum\EmailStatus;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\ORMTraits\OrmIdTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmailRepository")
 * @ORM\Table(name="webslon_email")
 */
class Email
{
    use OrmIdTrait;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted = false;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank
     * @Assert\Email
     */
    private $emailFrom;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank
     * @Assert\Email
     */
    private $emailTo;

    /**
     * @ORM\Column(type="string", length=500, nullable=false)
     * @Assert\NotBlank
     * @Assert\Type("string")
     */
    private $subject;

    /**
     * @ORM\Column(type="json", nullable=true)
     * @Assert\All({
     *     @Assert\Email
     * })
     */
    private $cc;

    /**
     * @ORM\Column(type="json", nullable=true)
     * @Assert\All({
     *     @Assert\Email
     * })
     */
    private $bcc;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Email
     */
    private $replyTo;

    /**
     * @ORM\Column(type="json", nullable=true)
     * * @Assert\All({
     *     @Assert\Type("string")
     * })
     */
    private $parameters;

    /**
     * @ORM\ManyToOne(targetEntity="Template")
     * @ORM\JoinColumn(nullable=true)
     * @Assert\NotNull(groups={"addEmailByTemplate"})
     */
    private $template;

    /**
     * @ORM\ManyToOne(targetEntity="Theme")
     * @ORM\JoinColumn(nullable=true)
     * @Assert\NotNull(groups={"addEmail"})
     */
    private $theme;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank(groups={"addEmail"})
     * @Assert\Type("string")
     */
    private $body;

    /**
     * @ORM\Column(type="string")
     */
    private $application;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status = EmailStatus::NEW;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $sentAt;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $response;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->application = 'none';
    }

    /**
     * @return boolean
     */
    public function isDeleted(): bool
    {
        return $this->deleted === true;
    }

    /**
     * @param bool $deleted
     *
     * @return Email
     */
    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmailFrom(): string
    {
        return $this->emailFrom;
    }

    /**
     * @param string $emailFrom
     *
     * @return Email
     */
    public function setEmailFrom(string $emailFrom): self
    {
        $this->emailFrom = $emailFrom;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmailTo(): string
    {
        return $this->emailTo;
    }

    /**
     * @param string $emailTo
     *
     * @return Email
     */
    public function setEmailTo(string $emailTo): self
    {
        $this->emailTo = $emailTo;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     *
     * @return Email
     */
    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return null|array
     */
    public function getCc(): ?array
    {
        return $this->cc;
    }

    /**
     * @param array|null $cc
     * @return Email
     */
    public function setCc(?array $cc): self
    {
        $this->cc = $cc;

        return $this;
    }

    /**
     * @return null|array
     */
    public function getBcc(): ?array
    {
        return $this->bcc;
    }

    /**
     * @param array|null $bcc
     * @return Email
     */
    public function setBcc(?array $bcc): self
    {
        $this->bcc = $bcc;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getReplyTo(): ?string
    {
        return $this->replyTo;
    }

    /**
     * @param null|string $replyTo
     * @return Email
     */
    public function setReplyTo(?string $replyTo): self
    {
        $this->replyTo = $replyTo;

        return $this;
    }

    /**
     * @return null|array
     */
    public function getParameters(): ?array
    {
        return $this->parameters;
    }

    /**
     * @param array|null $parameters
     * @return Email
     */
    public function setParameters(?array $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @return null|Template
     */
    public function getTemplate(): ?Template
    {
        return $this->template;
    }

    /**
     * @param null|Template $template
     * @return Email
     */
    public function setTemplate(?Template $template): self
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTemplate(): bool
    {
        return $this->template !== null;
    }

    /**
     * @return null|Theme
     */
    public function getTheme(): ?Theme
    {
        return $this->theme;
    }

    /**
     * @param null|Theme $theme
     * @return Email
     */
    public function setTheme(?Theme $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasTheme(): bool
    {
        return $this->theme !== null;
    }

    /**
     * @return null|string
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * @param null|string $body
     * @return Email
     */
    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasBody(): bool
    {
        return $this->body !== null;
    }

    /**
     * @return string
     */
    public function getApplication(): string
    {
        return $this->application;
    }

    /**
     * @param string $application
     *
     * @return Email
     */
    public function setApplication(string $application): self
    {
        $this->application = $application;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return Email
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return Email
     */
    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getSentAt(): ?\DateTime
    {
        return $this->sentAt;
    }

    /**
     * @param \DateTime|null $sentAt
     * @return Email
     */
    public function setSentAt(?\DateTime $sentAt): self
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getResponse(): ?string
    {
        return $this->response;
    }

    /**
     * @param null|string $response
     * @return Email
     */
    public function setResponse(?string $response): self
    {
        $this->response = $response;

        return $this;
    }
}

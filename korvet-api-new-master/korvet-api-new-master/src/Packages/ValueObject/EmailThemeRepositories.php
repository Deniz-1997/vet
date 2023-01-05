<?php

namespace App\Packages\ValueObject;

use App\Repository\EmailRepository;
use App\Repository\EmailThemeRepository;

class EmailThemeRepositories
{
    /**
     * @var EmailRepository
     */
    private EmailRepository $emailRepository;

    /**
     * @var EmailThemeRepository
     */
    private EmailThemeRepository $emailThemeRepository;

    public function __construct(EmailRepository $emailRepository, EmailThemeRepository $emailThemeRepository) {
        $this->emailRepository = $emailRepository;
        $this->emailThemeRepository = $emailThemeRepository;
    }

    /**
     * @return EmailRepository
     */
    public function getEmailRepository(): EmailRepository
    {
        return $this->emailRepository;
    }

    /**
     * @return EmailThemeRepository
     */
    public function getThemeRepository(): EmailThemeRepository
    {
        return $this->emailThemeRepository;
    }
}

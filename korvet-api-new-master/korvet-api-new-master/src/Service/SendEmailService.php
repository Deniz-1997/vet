<?php

namespace App\Service;

use App\Entity\Email;
use App\Entity\Theme;
use App\Enum\EmailStatus;
use App\Packages\ValueObject\EmailThemeRepositories;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use RuntimeException;
use Swift_Mailer;
use Swift_Message;
use Twig\Environment;

class SendEmailService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $om;

    /**
     * @var Environment
     */
    private Environment $twig;

    /**
     * @var Swift_Mailer
     */
    private Swift_Mailer $mailer;

    /**
     * @var ResourcePathService
     */
    private ResourcePathService $resourcePath;

    /**
     * @var EmailThemeRepositories
     */
    private EmailThemeRepositories $repositories;

    public function __construct(EntityManagerInterface $om, EmailThemeRepositories $repositories, Environment $twig, Swift_Mailer $mailer, ResourcePathService $resourcePath) {
        $this->om = $om;
        $this->twig = $twig;
        $this->mailer = $mailer;
        $this->resourcePath = $resourcePath;
        $this->repositories = $repositories;
    }

    public function sendEmail(Email $email, &$successCounter = 0, &$errorCounter = 0): Email
    {
        try {
            /* @var $email Email */
            $message = (new Swift_Message())
                ->setFrom($email->getEmailFrom())
                ->setTo($email->getEmailTo())
                ->setSubject($email->getSubject())
                ->setReplyTo($email->getReplyTo())
                ->setCc($email->getCc())
                ->setBcc($email->getBcc());

            if ($email->hasBody()) {
                $message->setBody($email->getBody());
            } else {
                if (!$email->isTemplate()) {
                    throw new RuntimeException("Email don't have template or body.");
                }

                $templateName = $this->resourcePath->getTemplatePath($email->getTemplate()->getFile());

                $parameters = $email->getParameters();
                $parameters['theme'] = $this->getThemePath($email);

                $message->setBody(
                    $this->twig->render($templateName, $parameters),
                    'text/html'
                );
            }

            if ($this->mailer->send($message)) {
                $email->setStatus(EmailStatus::SENT);
                $email->setSentAt(new DateTime());
                $successCounter++;
            }
        } catch (Exception $e) {
            $email->setResponse($e->getMessage());
            $email->setStatus(EmailStatus::ERROR);
            $errorCounter++;
        }
        $this->om->persist($email);
        return $email;
    }

    public function send(): string
    {
        $emails = $this->repositories->getEmailRepository()->getNewAndNotDeletedEmails();
        if (empty($emails)) {
            return 'Нет писем для отправки.';
        }

        $correct = 0;
        $error = 0;

        foreach ($emails as $email) {
            $this->sendEmail($email, $correct, $error);
        }

        $this->om->flush();

        return "Успешно отправлено: {$correct} писем.\nПисем с ошибками: {$error}.";
    }

    /**
     * @param Email $email
     *
     * @return string
     * @throws RuntimeException
     */
    private function getThemePath(Email $email): string
    {
        if ($email->hasTheme()) {
            $theme = $email->getTheme()->getFile();
        } elseif ($email->isTemplate() && $email->getTemplate()->isTheme()) {
            $theme = $email->getTemplate()->getTheme()->getFile();
        } else {
            $theme = $this->repositories->getThemeRepository()->getDefaultTheme();

            if (!$theme instanceof Theme) {
                throw new RuntimeException('Cannot find default theme.');
            }

            $theme = $theme->getFile();
        }

        return $this->resourcePath->getThemePath($theme);
    }
}

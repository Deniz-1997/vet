<?php

namespace App\Tests\Service;

use App\Entity\Email;
use App\Entity\Template;
use App\Entity\Theme;
use App\Enum\EmailStatus;
use App\Repository\EmailRepository;
use App\Repository\EmailThemeRepository;
use App\Service\ResourcePathService;
use App\Service\ResourceService;
use App\Service\SendEmailService;
use App\Packages\ValueObject\EmailThemeRepositories;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Swift_Mailer;
use Twig_Environment;
use Doctrine\Persistence\ObjectManager;

class SendEmailServiceTest extends TestCase
{

    /**
     * @var EmailRepository|MockObject
     */
    private $emailRepository;

    /**
     * @var EmailThemeRepository|MockObject
     */
    private $emailThemeRepository;

    /**
     * @var Swift_Mailer|MockObject
     */
    private $swiftMailerStub;

    /**
     * @var SendEmailService
     */
    private SendEmailService $sendEmailService;

    /**
     * @var string
     */
    private string $dateFormat;
    /**
     * @var Email
     */
    private Email $email;

    /**
     * @var Theme
     */
    private Theme $theme;

    /**
     * @var Template
     */
    private $template;

    protected function setUp(): void
    {
        $themePath = '/this/is/some/path/to/theme/';
        $templatePath = '/this/is/some/path/to/template/';

        $resourceService = new ResourcePathService(
            $themePath,
            $templatePath
        );

        $this->emailRepository = $this->createMock(EmailRepository::class);
        $this->emailThemeRepository = $this->createMock(EmailThemeRepository::class);

        $repositories = new EmailThemeRepositories(
            $this->emailRepository,
            $this->emailThemeRepository
        );

        $this->swiftMailerStub = $this->createMock(Swift_Mailer::class);

        $this->sendEmailService = new SendEmailService(
            $this->createMock(ObjectManager::class),
            $repositories,
            $this->createMock(Twig_Environment::class),
            $this->swiftMailerStub,
            $resourceService
        );

        $this->dateFormat = 'Y-m-d H:i:s';

        $this->email = (new Email())
            ->setEmailFrom('from@example.com')
            ->setEmailTo('to@example.com')
            ->setSubject('Есть много вариантов Lorem Ipsum.');

        $this->theme = (new Theme())->setFile('theme.html.twig');
        $this->template = (new Template())->setFile('template.html.twig');
    }

    public function testSendWithoutEmailInDB(): void
    {
        $this->assertSame('Нет писем для отправки.',
            $this->sendEmailService->send());
    }

    public function testSendWithBody(): void
    {
        $this->email->setBody('Some body.');

        $this->getNewAndNotDeletedEmailsMock([$this->email]);
        $this->mockSendTrue();

        $this->correctSendAssert();
    }

    public function testSendWithTemplateAndTheme(): void
    {
        $this->email
            ->setTemplate($this->template)
            ->setTheme($this->theme);
        $this->getNewAndNotDeletedEmailsMock([$this->email]);
        $this->mockSendTrue();
        $this->correctSendAssert();
    }

    public function testSendCorrectWithThemeInTemplate(): void
    {
        $this->template->setTheme($this->theme);
        $this->email->setTemplate($this->template);

        $this->getNewAndNotDeletedEmailsMock([$this->email]);
        $this->mockSendTrue();
        $this->correctSendAssert();
    }

    public function testSendWithTemplateAndDefaultTheme(): void
    {
        $this->emailThemeRepository->expects($this->once())
            ->method('getDefaultTheme')
            ->willReturn($this->theme);

        $this->email->setTemplate($this->template);

        $this->getNewAndNotDeletedEmailsMock([$this->email]);
        $this->mockSendTrue();
        $this->correctSendAssert();
    }

    public function testSendWithoutBodyAndTemplate(): void
    {
        $this->getNewAndNotDeletedEmailsMock([$this->email]);

        $this->errorSendAssert("Email don't have template or body.");
    }

    public function testSendWithTemplateAndWithoutDefaultTheme(): void
    {
        $this->email->setTemplate($this->template);
        $this->getNewAndNotDeletedEmailsMock([$this->email]);

        $this->errorSendAssert('Cannot find default theme.');
    }

    private function getNewAndNotDeletedEmailsMock($value): void
    {
        $this->emailRepository->expects($this->once())
            ->method('getNewAndNotDeletedEmails')
            ->willReturn($value);
    }

    private function mockSendTrue(): void
    {
        $this->swiftMailerStub->expects($this->once())
            ->method('send')
            ->willReturn(true);
    }

    private function correctSendAssert(): void
    {
        $this->assertSame("Успешно отправлено: 1 писем.\nПисем с ошибками: 0.",
            $this->sendEmailService->send());
        $this->assertSame(EmailStatus::SENT, $this->email->getStatus());
        $this->assertSame((new \DateTime())->format($this->dateFormat),
            $this->email->getSentAt()->format($this->dateFormat));
    }

    private function errorSendAssert(string $response): void
    {
        $this->assertSame("Успешно отправлено: 0 писем.\nПисем с ошибками: 1.",
            $this->sendEmailService->send());
        $this->assertSame(EmailStatus::ERROR,
            $this->email->getStatus());
        $this->assertSame($response,
            $this->email->getResponse());
    }
}

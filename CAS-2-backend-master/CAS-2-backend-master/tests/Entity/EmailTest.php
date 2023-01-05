<?php

namespace App\Tests\Entity;

use App\Entity\Email;
use App\Entity\Template;
use App\Entity\Theme;
use App\Enum\EmailStatus;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    /**
     * @var Email
     */
    private $email;

    /**
     * @var \DateTime
     */
    private \DateTime $date;

    protected function setUp(): void
    {
        $this->email = new Email();
        $this->date = new \DateTime();
    }

    public function testId(): void
    {
        $id = 42;

        /* @var $mock Email|MockObject */
        $mock = $this->createMock(Email::class);
        $mock->expects($this->once())
            ->method('getId')
            ->willReturn($id);

        $this->assertSame($id, $mock->getId());
    }

    public function testSettersAndGetters(): void
    {
        $emailFrom = 'from@example.com';
        $emailTo = 'to@example.com';
        $subject = 'Есть много вариантов Lorem Ipsum.';

        $cc = [
            'cc@example.com',
            'cc1@example.com',
            'cc2@example.com'
        ];
        $bcc = [
            'bcc@example.com',
            'bcc1@example.com',
            'bcc2@example.com'
        ];
        $replyTo = 'reply@example.com';

        $parameters = [
            'one' => 'some',
            'two' => 'value',
        ];

        $body = 'Многие думают, что Lorem Ipsum - взятый с потолка.';
        $application = 'app';

        $date = new \DateTime();
        $dateFormat = 'Y-m-d H:i:s';

        $response = 'This is some response.';

        $this->assertFalse($this->email->isDeleted());
        $this->email->setDeleted(true);
        $this->assertTrue($this->email->isDeleted());

        $this->email->setEmailFrom($emailFrom);
        $this->assertSame($emailFrom, $this->email->getEmailFrom());

        $this->email->setEmailTo($emailTo);
        $this->assertSame($emailTo, $this->email->getEmailTo());

        $this->email->setSubject($subject);
        $this->assertSame($subject, $this->email->getSubject());

        $this->email->setCc($cc);
        $this->assertSame($cc, $this->email->getCc());

        $this->email->setBcc($bcc);
        $this->assertSame($bcc, $this->email->getBcc());

        $this->email->setReplyTo($replyTo);
        $this->assertSame($replyTo, $this->email->getReplyTo());

        $this->email->setParameters($parameters);
        $this->assertSame($parameters, $this->email->getParameters());

        $this->email->setBody($body);
        $this->assertSame($body, $this->email->getBody());

        $this->assertSame('none', $this->email->getApplication());
        $this->email->setApplication($application);
        $this->assertSame($application, $this->email->getApplication());

        $this->assertSame(EmailStatus::NEW, $this->email->getStatus());
        $this->email->setStatus(EmailStatus::SENT);
        $this->assertSame(EmailStatus::SENT, $this->email->getStatus());

        $this->assertSame(
            $date->format($dateFormat),
            $this->email->getCreatedAt()->format($dateFormat)
        );

        $this->email->setSentAt($date);
        $this->assertInstanceOf(\DateTime::class, $this->email->getSentAt());

        $this->email->setResponse($response);
        $this->assertSame($response, $this->email->getResponse());
    }

    public function testThemeAndTemplate(): void
    {
        /* @var $theme Theme */
        $theme = $this->createMock(Theme::class);
        /* @var $template Template */
        $template = $this->createMock(Template::class);

        $this->email->setTheme($theme);
        $this->assertInstanceOf(Theme::class, $this->email->getTheme());

        $this->email->setTemplate($template);
        $this->assertInstanceOf(Template::class, $this->email->getTemplate());
    }
}

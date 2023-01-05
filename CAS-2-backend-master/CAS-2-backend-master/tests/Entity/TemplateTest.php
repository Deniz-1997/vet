<?php

namespace App\Tests\Entity;

use App\Entity\Template;
use App\Entity\Theme;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class TemplateTest extends TestCase
{
    /**
     * @var Template
     */

    private $template;
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $file;

    protected function setUp(): void
    {
        $this->name = 'template.html.twig';
        $this->file = 'template.html.twig';

        $this->template = new Template();
    }

    public function testId(): void
    {
        $id = 92;

        /* @var $mock Template|MockObject */
        $mock = $this->createMock(Template::class);
        $mock->expects($this->once())
            ->method('getId')
            ->willReturn($id);

        $this->assertSame($id, $mock->getId());
    }

    public function testNameAndFile(): void
    {
        $this->template->setName($this->name);
        $this->assertSame($this->name, $this->template->getName());

        $this->template->setFile($this->file);
        $this->assertSame($this->file, $this->template->getFile());
    }

    public function testNullTheme(): void
    {
        $this->assertNull($this->template->getTheme());
        $this->assertFalse($this->template->isTheme());
    }

    public function testTheme(): void
    {
        /* @var $mock Theme|MockObject */
        $mock = $this->createMock(Theme::class);

        $this->template->setTheme($mock);
        $this->assertSame($mock, $this->template->getTheme());
    }

    public function testIsNotDeleted(): void
    {
        $this->assertFalse($this->template->isDeleted());
    }

    public function testIsDeleted(): void
    {
        $this->template->setDeleted(true);
        $this->assertTrue($this->template->isDeleted());
    }
}

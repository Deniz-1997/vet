<?php

namespace App\Tests\Entity;

use App\Entity\Theme;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class ThemeTest extends TestCase
{
    /**
     * @var Theme
     */
    private $theme;

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
        $this->name = 'theme.html.twig';
        $this->file = 'theme.html.twig';

        $this->theme = new Theme();
    }

    public function testId(): void
    {
        $id = 42;

        /* @var $mock Theme|MockObject */
        $mock = $this->createMock(Theme::class);
        $mock->expects($this->once())
            ->method('getId')
            ->willReturn($id);

        $this->assertSame($id, $mock->getId());
    }

    public function testNameAndFile(): void
    {
        $this->theme->setName($this->name)
            ->setFile($this->file);

        $this->assertSame($this->name, $this->theme->getName());
        $this->assertSame($this->file, $this->theme->getFile());
    }

    public function testIsNotDeleted(): void
    {
        $this->assertFalse($this->theme->isDeleted());
    }

    public function testIsDeleted(): void
    {
        $this->theme->setDeleted(true);
        $this->assertTrue($this->theme->isDeleted());
    }

    public function testIsNotDefault(): void
    {
        $this->assertFalse($this->theme->isDefault());
    }

    public function testIsDefault(): void
    {
        $this->theme->setIsDefault(true);
        $this->assertTrue($this->theme->isDefault());
    }
}

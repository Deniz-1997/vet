<?php

namespace App\Tests\Entity;

use App\Entity\Resource;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ResourceTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $name = 'theme.html.twig';
        $file = 'theme.html.twig';

        /* @var $mock Resource|MockObject */
        $mock = $this->createMock(Resource::class);
        $mock->expects($this->once())
            ->method('setName')
            ->with($name)
            ->willReturn($mock);

        $mock->expects($this->once())
            ->method('setFile')
            ->with($file)
            ->willReturn($mock);

        $mock->expects($this->once())
            ->method('getName')
            ->willReturn($name);

        $mock->expects($this->once())
            ->method('getFile')
            ->willReturn($file);

        $mock->setName($name);
        $this->assertSame($name, $mock->getName());

        $mock->setFile($file);
        $this->assertSame($file, $mock->getFile());
    }
}

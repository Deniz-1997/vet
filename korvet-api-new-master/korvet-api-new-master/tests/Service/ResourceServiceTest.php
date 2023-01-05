<?php

namespace App\Tests\Service;

use App\Service\ResourceService;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class ResourceServiceTest extends TestCase
{

    /**
     * @var ResourceService|MockObject
     */
    private $service;

    protected function setUp(): void
    {
        $this->service = $this->createMock(ResourceService::class);
    }

    public function testCheckWithoutDirectory(): void
    {
        $message = 'Cannot find directory.';
        $this->mockCheckWithException($message);
        $this->expectedCheckException($message,
            'Cannot find directory exception expected.');
    }

    public function testCheckWithoutReadableDirectory(): void
    {
        $message = 'Cannot read directory.';
        $this->mockCheckWithException($message);
        $this->expectedCheckException($message,
            'Cannot read directory exception expected.');
    }

    public function testCheckWithEmptyDirectory(): void
    {
        $message = 'Directory is empty.';
        $this->mockCheckWithException($message);
        $this->expectedCheckException($message,
            'Directory is empty exception expected.');
    }

    /**
     * @param string $message
     */
    private function mockCheckWithException(string $message): void
    {
        $this->service->expects($this->once())
            ->method('check')
            ->willThrowException(
                new \RuntimeException($message)
            );
    }

    /**
     * @param string $expectMessage
     * @param string $failMessage
     */
    private function expectedCheckException(
        string $expectMessage,
        string $failMessage
    ): void {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage($expectMessage);
        $this->service->check();
        $this->fail($failMessage);
    }
}

<?php

namespace App\Service\Test\CRUD;

use PHPUnit\Framework\TestCase;

class WrongIdTest extends TestCase
{
    /**
     * @param array $result
     * @param int $code
     * @param string $message
     */
    public static function expected(
        array $result,
        int $code,
        string $message
    ): void {
        static::assertFalse($result['status']);
        static::assertSame($code, $result['httpResponseCode']);
        static::assertSame($message, $result['errors'][0]['message']);
    }
}

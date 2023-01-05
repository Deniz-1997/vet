<?php

namespace App\Tests\Command;

use App\Service\Test\Command\CommandTest;

class LoadServiceCommandTest extends CommandTest
{
    public function testExecute(): void
    {
        $output = $this->getResultMessage('app:load-stock-file', ['--use-stub' => true]);

        //todo
        static::assertContains('Success orders update:', $output);
        static::assertContains('Orders without update:', $output);
        static::assertContains('Done.', $output);
    }
}

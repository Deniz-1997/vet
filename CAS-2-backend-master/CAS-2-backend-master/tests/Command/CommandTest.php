<?php

namespace App\Service\Test\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CommandTest extends KernelTestCase
{
    /**
     * @param string $commandName
     * @param array $options
     * @return string
     */
    public function getResultMessage(
        string $commandName,
        array $options = []
    ): string {
        $application = new Application(static::createKernel());
        $command = $application->find($commandName);
        $commandTester = new CommandTester($command);
        $commandTester->execute(array_merge(
            [
                'command' => $command->getName(),
            ],
            $options
        ));

        return $commandTester->getDisplay();
    }
}

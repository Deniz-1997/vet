<?php

namespace App\Command\Email;

use App\Command\ContainerAwareCommand\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class EmailSendCommand extends ContainerAwareCommand
{
    protected function configure(): void
    {
        $this->setName('webslon:email:send')
            ->setDescription('Отправка новых писем.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $service = $this->getContainer()->get('webslon.send_email');
        $output->writeln($service->send());
        return Command::SUCCESS;
    }
}

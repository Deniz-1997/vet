<?php

namespace App\Command;

use App\Command\ContainerAwareCommand\ContainerAwareCommand;
use App\Packages\Security\Encryptor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EncryptDataCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('webslon:authentication:encrypt')
            ->addArgument('dencrypted', InputArgument::REQUIRED)
            ->setDescription('Try encrypt string');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $decoded = $this->getContainer()->get(Encryptor::class)->encrypt($input->getArgument('dencrypted'));
        dump($decoded);
        return Command::SUCCESS;
    }
}

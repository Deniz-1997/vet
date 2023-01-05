<?php

namespace App\Command;

use App\Command\ContainerAwareCommand\ContainerAwareCommand;
use App\Packages\Security\SecretStorageProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PasetoKeyUpdateCommand extends ContainerAwareCommand
{

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('webslon:pasetoKey:update')
            ->setDescription('Update key for paseto key.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->getContainer()->get(SecretStorageProvider::class)->updateEncodingUserKey();
        return Command::SUCCESS;
    }
}

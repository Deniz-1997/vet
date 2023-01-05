<?php

namespace App\Command;

use App\Command\ContainerAwareCommand\ContainerAwareCommand;
use App\Packages\Security\Encryptor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DecryptUserCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('webslon:authentication:decrypt')
            ->addArgument('encrypted', InputArgument::REQUIRED)
            ->setDescription('Try decrypt user token to normally UserDTO');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $encodedUser = $this->getContainer()->get(Encryptor::class)->decryptAuthenticationInformation(
            $input->getArgument('encrypted')
        );

        dump($encodedUser);
        return Command::SUCCESS;
    }
}

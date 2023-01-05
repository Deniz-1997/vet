<?php

namespace App\Command;

use App\Command\ContainerAwareCommand\ContainerAwareCommand;
use App\Packages\Client\OAuthClient;
use App\Packages\Security\Encryptor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AuthorizeCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('webslon:auth:authorize')
            ->addArgument('username', InputArgument::OPTIONAL)
            ->addArgument('password', InputArgument::OPTIONAL)
            ->setDescription('Try authorization');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $oauthClient = $this->getContainer()->get(OAuthClient::class);

        if ($input->getArgument('username') && $input->getArgument('password')) {
            $token = $oauthClient->getAccessTokenForUser(
                $input->getArgument('username'),
                $input->getArgument('password')
            );
        } else {
            $token = $oauthClient->getAccessTokenForClient();
        }

        $user = $this->getContainer()->get('App\Endpoint\Client\AccountClient')->getCurrentAuthenticationInfo(
            $token->getAccessToken()
        );

        $encodedUser = $this->getContainer()->get(Encryptor::class)->encryptAuthenticationInformation($user);

        dump($token, $encodedUser, $user, $this->getContainer()->get(OAuthClient::class)->getAccessTokenForClient());
        return Command::SUCCESS;
    }
}

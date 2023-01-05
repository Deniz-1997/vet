<?php

namespace App\Command;

use App\Entity\OAuth\Client;
use App\Repository\OAuth\ClientRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SecurityOauthClientsAddRedirectUrlCommand extends Command
{
    protected static $defaultName = 'app:security:oauth-clients:add-redirect-url';

    /** @var ClientRepository */
    private ClientRepository $clientRepository;

    /**
     * SecurityOauthClientsAddRedirectUrlCommand constructor.
     * @param ClientRepository $clientRepository
     */
    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;

        parent::__construct(self::$defaultName);
    }

    protected function configure()
    {
        $this
            ->setDescription('Command for add additional redirect-uris for client (authorization by auth_code)')
            ->addArgument('clientId', InputArgument::REQUIRED, 'Client id')
            ->addArgument('newRedirectUri', InputArgument::REQUIRED, 'New redirect uri');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $clientId = $input->getArgument('clientId');
        $newRedirectUri = $input->getArgument('newRedirectUri');

        /** @var Client $client */
        if (!$client = $this->clientRepository->find($clientId)) {
            throw new \LogicException(sprintf('Client %d not found', $clientId));
        }

        $redirectUris = $client->getRedirectUris() ?: [];
        $redirectUris[] = $newRedirectUri;
        $client->setRedirectUris($redirectUris);

        $this->clientRepository->save($client);

        $io->success('Redirect uri has been added');
        return Command::SUCCESS;
    }
}

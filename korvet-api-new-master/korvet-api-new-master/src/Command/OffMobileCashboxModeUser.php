<?php

namespace App\Command;

use App\Entity\User\User;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;

class OffMobileCashboxModeUser extends Command
{
    protected static $defaultName = 'app:off-mobile-mode-cashbox-user';

    /** @var EntityManagerInterface */
    private EntityManagerInterface $_entityManager;

    /**
     * OffMobileCashboxModeUser constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->_entityManager = $entityManager;
        parent::__construct(self::$defaultName);
    }

    protected function configure()
    {
        $this->setDescription('Off flag mode cashbox');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = $this->_entityManager->getRepository(User::class)->findAll();

        foreach ($users as $user) {
            $user->setModeCashboxMobile(false);
            $this->_entityManager->persist($user);
        }
        return Command::SUCCESS;
    }
}

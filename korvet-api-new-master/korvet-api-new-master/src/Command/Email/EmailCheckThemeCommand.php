<?php

namespace App\Command\Email;

use App\Entity\Theme;
use App\Repository\EmailThemeRepository;
use App\Repository\ResourceRepository;
use App\Service\ResourceService;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

class EmailCheckThemeCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('webslon:email:check:theme')
            ->setDescription('Проверка тем в директории на актуальность в БД.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var Theme $theme */
        $theme = $this->getContainer()->get('webslon.resource.theme');

        /** @var ResourceRepository $repository */
        $repository = $this->getContainer()->get(EmailThemeRepository::class);

        /** @var EntityManager $objectManager */
        $objectManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $twigPath = $this->getContainer()->getParameter('twig.default_path');

        $path = $this->getContainer()->getParameter('webslon_email.resources.theme');

        $service = new ResourceService($objectManager, $repository, $theme, $twigPath . $path);
        $service->check();

        $output->writeln('Проверка и обновление выполнены успешно.');
        return Command::SUCCESS;
    }
}

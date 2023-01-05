<?php

namespace App\Command\Email;

use App\Command\ContainerAwareCommand\ContainerAwareCommand;
use App\Repository\EmailTemplateRepository;
use App\Service\ResourceService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EmailCheckTemplateCommand extends ContainerAwareCommand
{
    protected function configure(): void
    {
        $this
            ->setName('webslon:email:check:template')
            ->setDescription('Проверка шаблонов в директории на актуальность в БД.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $template = $this->getContainer()->get('webslon.resource.template');
        $repository = $this->getContainer()->get(EmailTemplateRepository::class);
        $objectManager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $twigPath = $this->getContainer()->getParameter('twig.default_path');
        $path = $this->getContainer()->getParameter('webslon_email.resources.template');

        $service = new ResourceService($objectManager, $repository, $template, $twigPath . $path);
        $service->check();

        $output->writeln('Проверка и обновление выполнены успешно.');
        return Command::SUCCESS;
    }
}

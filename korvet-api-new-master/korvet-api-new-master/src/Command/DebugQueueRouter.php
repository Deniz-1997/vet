<?php

namespace App\Command;

use App\Packages\AMQP\Router\RouterCollection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DebugQueueRouter extends Command
{
    protected static $defaultName = 'webslon:debug:queue:router';

    /**
     * @var RouterCollection
     */
    private RouterCollection $routeCollection;


    /**
     * ConsumerCommand constructor.
     *
     * @param RouterCollection $routeCollection
     */
    public function __construct (RouterCollection $routeCollection)
    {
        parent::__construct();

        $this->routeCollection = $routeCollection;
    }

    protected function execute (InputInterface $input, OutputInterface $output): int
    {
        $table = new Table($output);
        $table->setHeaders(['queue name', 'consumer', 'action', 'exchange', 'exchange bind key']);

        foreach ($this->routeCollection->all() as $route) {
            $table->addRow([
                $route->getQueue(),
                $route->getConsumer(),
                $route->getAction(),
                $route->getExchangeName(),
                $route->getExchangeBindKey(),
            ]);
        }

        $table->render();
        return Command::SUCCESS;
    }
}

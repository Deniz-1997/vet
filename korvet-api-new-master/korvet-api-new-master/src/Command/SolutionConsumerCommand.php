<?php

namespace App\Command;

use App\Model\Env;
use App\Packages\AMQP\RPC\RedisResponseStorage;
use App\Packages\Security\SecurityManager;
use DateTime;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPSSLConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Packages\AMQP\AMQPConnection;
use App\Packages\AMQP\Packet;
use App\Packages\AMQP\RabbitRestClient;
use App\Packages\AMQP\Router\Route;
use App\Packages\AMQP\Router\RouterCollection;
use App\Packages\AMQP\RPC\ResponseStorageInterface;
use App\Packages\Annotation\Enqueue\Consume;
use App\Packages\Consumer\Consumer;
use App\Packages\Monolog\RabbitMqContext;
use App\Service\Solution\SolutionConsumerCollection;

class SolutionConsumerCommand extends ConsumerCommand
{
    use LockableTrait;

    protected static $defaultName = 'webslon:api:solution:consumer';

    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var SolutionConsumerCollection
     */
    private $consumerCollection;

    /**
     * SolutionConsumerCommand constructor.
     * @param AMQPConnection $connection
     * @param RabbitRestClient $rabibtmqClient
     * @param ResponseStorageInterface $responseStorage
     * @param RouterCollection $routeCollection
     * @param ContainerInterface $container
     * @param LoggerInterface $logger
     * @param SecurityManager $securityManager
     * @param EntityManagerInterface $entityManager
     * @param EventDispatcherInterface $eventDispatcher
     * @param Reader $reader
     * @param SolutionConsumerCollection $consumerCollection
     */
    public function __construct(
        AMQPConnection $connection,
        RabbitRestClient $rabibtmqClient,
        ResponseStorageInterface $responseStorage,
        RouterCollection $routeCollection,
        ContainerInterface $container,
        LoggerInterface $logger,
        SecurityManager $securityManager,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        Reader $reader,
        SolutionConsumerCollection $consumerCollection
    ) {
        $this->reader = $reader;

        parent::__construct(
            $connection,
            $rabibtmqClient,
            $responseStorage,
            $routeCollection,
            $container,
            $logger,
            $securityManager,
            $entityManager,
            $eventDispatcher
        );
        $this->consumerCollection = $consumerCollection;
    }

    protected function configure()
    {
        $this
            ->setDescription('Start consumers for service Solution')
        ;

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws ReflectionException
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->lock(__FILE__)) {
            return 0;
        }

        $this->overrideConnection();
        $this->overrideRouteCollection();

        try {
            parent::execute($input, $output);
        } finally {
            $this->release();
        }
        return Command::SUCCESS;
    }

    /**
     * @throws Exception
     */
    private function overrideConnection()
    {
        if ($this->connection instanceof AMQPConnection ) {
            $host = Env::getenv('SOLUTION_RABBITMQ_HOST');
            $vHost = Env::getenv('SOLUTION_RABBITMQ_VHOST');
            $apiPort = Env::getenv('SOLUTION_RABBITMQ_PORT');
            $username = Env::getenv('SOLUTION_RABBITMQ_USERNAME');
            $password = Env::getenv('SOLUTION_RABBITMQ_PASSWORD');

            $ssl_options = array(
                'peer_name' => Env::getenv('SOLUTION_RABBITMQ_SSL_PEER_NAME'),
                'verify_peer' => false,
            );

            if ($this->connection->isConnected()) {
                $this->connection->close();
            }

            $this->connection = new AMQPSSLConnection($host, $apiPort, $username, $password, $vHost, $ssl_options);
        }
    }

    /**
     * @throws ReflectionException
     */
    private function overrideRouteCollection()
    {
        $routes = [];

//        foreach (self::getConsumerClasses() as $class) {
        foreach ($this->consumerCollection->getConsumers() as $class) {
            $reflect = new ReflectionClass($class);
            foreach ($reflect->getMethods() as $refMethod) {
                /** @var Consume $consume */
                $consume = $this->reader->getMethodAnnotation($refMethod, Consume::class);
                if (! $consume) {
                    continue;
                }

                $routes[] = Route::createFromConsume($consume, $reflect->getName(), $refMethod->getName());
            }
        }

        $this->routeCollection = new RouterCollection($routes);
    }

    /**
     * @param AMQPMessage $msg
     * @param string $queueName
     * @throws Exception
     */
    public function process(AMQPMessage $msg, string $queueName)
    {
        /** @var AMQPChannel $msgChannel */
        $msgChannel = $msg->delivery_info['channel'];

        $body = $msg->getBody();
        $dataMsg = json_decode($body, true);

        if (!is_array($dataMsg)) {
            $msgChannel->basic_reject($msg->delivery_info['delivery_tag'], false);
            $this->logger->error('AMQP Message, expected json string', RabbitMqContext::getLoggingContextAmqpMessage($msg));
            return;
        }

        $replyContext = isset($dataMsg['replyContext']) ? $dataMsg['replyContext'] : [];

        $packet = new Packet(
            null,
            new DateTime(),
            $dataMsg,
            isset($dataMsg['rpc']) ? $dataMsg['rpc'] : Packet::RPC_NONE,
            isset($dataMsg['errors']) ? $dataMsg['errors'] : null,
            $replyContext
        );
        $packet->setAMQPMessage($msg);

        if (isset($dataMsg['rpcCallStack'])) {
            $packet->setRpcCallStack($dataMsg['rpcCallStack'] ?? []);
        }

        $router = $this->routeCollection->getRouteByQueueName($queueName);
        $processorId = $router->getConsumer();
        $method = $router->getAction();

        try {
            $processor = $this->container->get($processorId);
            if ($processor instanceof Consumer) {
                $processor->setRequestPacket($packet);
                $processor->loadReplyContext($replyContext);
            }
            call_user_func([$processor, $method], $packet);
        } catch (Exception $exception) {
            $msgChannel->basic_reject($msg->delivery_info['delivery_tag'], false);
            $this->logger->error(sprintf('AMQP rejected, error: %s', $exception->getMessage()), RabbitMqContext::getLoggingContext($packet, $exception));

            throw $exception;
        }

        $this->processedMessages += 1;


        gc_collect_cycles();

        if ($this->maxMessages && $this->processedMessages >= $this->maxMessages) {
            $this->logger->notice(sprintf('Processed maximum messages %d', $this->processedMessages));
            $msgChannel->close();
            exit(0); //Successfully exit this process
        }
    }
}

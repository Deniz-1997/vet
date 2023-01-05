<?php


namespace App\Packages\DependencyInjection\Compiler;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Finder\Finder;
use App\Packages\AMQP\PlaceholderResolver;
use App\Packages\AMQP\Router\Route;
use App\Packages\AMQP\Router\RouterCollection;
use App\Packages\Annotation\Enqueue\Consume;
use App\Packages\Annotation\Enqueue\CrudConsume;
use App\Packages\Annotation\Enqueue\CrudProduce;
use App\Packages\Annotation\Enqueue\Produce;
use App\Packages\Consumer\Batch\CreateConsumer;
use App\Packages\Consumer\Batch\DeleteConsumer;
use App\Packages\Consumer\Batch\ReplaceConsumer;
use App\Packages\Consumer\Batch\UpdateConsumer;

/**
 * Class EnqueueSubscriberPass
 */
final class EnqueueSubscriberPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $annotationReader = $container->get(Reader::class);
        $entityDirectories = $container->getParameter('queue_entities_directory');
        $dtoDirectories = $container->getParameter('webslon_api.queue_dto_directory');
        $sourceDirectory = $container->getParameter('kernel.project_dir').'/';

        $directories = array_map(function($directory) use ($sourceDirectory) {
            return $sourceDirectory.$directory;
        }, array_merge($dtoDirectories, $entityDirectories));


        $phpFiles = Finder::create()->files()->in($directories)->name('*.php');

        $classes = [];
        foreach ($phpFiles as $phpFile) {
            $className = str_replace('.php', '', $phpFile->getFileName());
            $classes[] =  $this->getFullNamespace($phpFile->getRealpath()).'\\'.$className;
        }

        $routeCollectionServiceId = RouterCollection::class;
        $routeCollectionDefinition = $container->getDefinition($routeCollectionServiceId);

        $routes = [];
        $prefixToHandler = [
            Route::TOPIC_CREATE_PREFIX => CreateConsumer::class,
            Route::TOPIC_DELETE_PREFIX => DeleteConsumer::class,
            Route::TOPIC_UPDATE_PREFIX => UpdateConsumer::class,
            Route::TOPIC_REPLACE_PREFIX => ReplaceConsumer::class,
        ];
        foreach ($classes as $class) {
            $reflectionClass = new \ReflectionClass($class);

            /** @var CrudConsume $queueConsume */
            $queueConsume = $annotationReader->getClassAnnotation($reflectionClass, CrudConsume::class);

            if (!$queueConsume) {
                continue;
            }

            foreach ($prefixToHandler as $prefix => $handler) {
                if (!isset($queueConsume->topicsMap[$prefix])) {
                    continue;
                }

                $consume = $queueConsume->topicsMap[$prefix];
                if (!$consume instanceof Consume) {
                    throw new \RuntimeException(sprintf('Topic map value must be string or %s got %s', Consume::class, gettype($consume)));
                }
                /** @var Produce $onErrors */
                list($queue, $name, $exchange, $exchangeBindKey, $onErrors) = [
                    $consume->queue,
                    $consume->queue,
                    $consume->exchangeName,
                    $consume->exchangeBindKey,
                    $consume->onErrors
                ];

                $placeholderResolver = new PlaceholderResolver();
                list($queue, $name, $exchange, $exchangeBindKey) = $placeholderResolver->handlePlaceholdersParametersFromContainer($container, $queue, $name, $exchange, $exchangeBindKey);

                $serviceId = sprintf('webslon.consumer.%s', $name);

                $container->register($serviceId, $handler)
                    ->setPublic(true)
                    ->addArgument($reflectionClass->getName())
                    ->setAutoconfigured(true)
                    ->setAutowired(true)
//                    ->addTag('webslon.queue_consumer')
                ;

                $routes[] = array_merge(Route::createFromConsume($consume, $serviceId, 'process'), [
                    'name' => $name,
                    'queue' => $queue,
                    'onErrors' => $onErrors ? [
                        'routingKey' => $onErrors->routingKey,
                        'exchange' => $onErrors->exchange,
                        'queue' => $onErrors->queue,
                    ] : null,
                    'itemType' => $reflectionClass->getName(),
                    'consumer' => $serviceId,
                    'action' => 'process',
                    'exchange_bind_key' => $exchangeBindKey,
                    'exchange_name' => $exchange,
                ]);
            }
        }

        if(!empty($routes))
            $routeCollectionDefinition->setArgument(0, $routes);
    }

    private function getFullNamespace($phpFile)
    {
        $lines = preg_grep('/^namespace /', file($phpFile));
        $namespaceLine = array_shift($lines);

        return trim(str_replace(['namespace',';'], '', $namespaceLine));
    }
}


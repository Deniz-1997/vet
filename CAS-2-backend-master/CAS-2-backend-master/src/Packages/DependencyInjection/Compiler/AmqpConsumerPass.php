<?php


namespace App\Packages\DependencyInjection\Compiler;

use App\Packages\AMQP\Router\Route;
use App\Packages\AMQP\Router\RouterCollection;
use App\Packages\Annotation\Enqueue\Consume;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class AmqpConsumerPass
 */
final class AmqpConsumerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $annotationReader = $container->get('annotation_reader');

        $routeCollectionServiceId = RouterCollection::class;
        $routeCollectionDefinition = $container->getDefinition($routeCollectionServiceId);

        $routes = [];
        foreach ($container->findTaggedServiceIds('webslon.queue_consumer') as $serviceId => $serviceData) {
            $reflectionClass = new \ReflectionClass(
                $container->getDefinition($serviceId)->getClass()
            );

            foreach ($reflectionClass->getMethods() as $reflectionMethod) {
                /** @var Consume $consume */
                $consume = $annotationReader->getMethodAnnotation($reflectionMethod, Consume::class);
                if (!$consume) {
                    continue;
                }

                $routes[] = Route::createFromConsume($consume, $serviceId, $reflectionMethod->getName());
            }
        }

        $routeCollectionDefinition->replaceArgument(0, array_merge(
            $routeCollectionDefinition->getArgument(0),
            $routes
        ));
    }
}

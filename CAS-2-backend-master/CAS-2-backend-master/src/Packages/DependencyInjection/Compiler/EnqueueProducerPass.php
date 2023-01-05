<?php

namespace App\Packages\DependencyInjection\Compiler;

use App\Packages\Annotation\Enqueue\CrudProduce;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class EnqueueProducerPass
 */
final class EnqueueProducerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $annotationReader = $container->get('annotation_reader');

        foreach ($container->findTaggedServiceIds('webslon.queue_producer') as $queueClientServiceId => $serviceData) {
            $producerDefinition = $container->getDefinition($queueClientServiceId);
            $reflectionClass = new \ReflectionClass($producerDefinition->getClass());

            /** @var CrudProduce $queueProduce */
            $queueProduce = $annotationReader->getClassAnnotation($reflectionClass, CrudProduce::class);

            if (!$queueProduce) {
                continue;
            }

            $producerDefinition->setBindings(['$topicsMap' => $queueProduce->topicsMap]);
        }
    }
}


<?php


namespace App\Packages\DependencyInjection\Compiler;


use App\Packages\AMQP\RPC\FileSystemResponseStorage;
use App\Packages\AMQP\RPC\RedisResponseStorage;
use App\Packages\AMQP\RPC\ResponseStorageInterface;
use App\Packages\Describer\JMSModelDescriber;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;


final class ConfigurationPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container->register(FileSystemResponseStorage::class, FileSystemResponseStorage::class);
        $defaultResponseStorage = FileSystemResponseStorage::class;
        if ($container->hasParameter('webslon_api.rabbitmq.redis.host')) {
            $container->register(RedisResponseStorage::class, RedisResponseStorage::class)
                ->setAutowired(true)
                ->setAutoconfigured(true)
                ->setArguments([
                    '%webslon_api.rabbitmq.redis.host%',
                    '%webslon_api.rabbitmq.redis.port%',
                    '%webslon_api.rabbitmq.redis.scheme%'
                ])
            ;

            $defaultResponseStorage = RedisResponseStorage::class;
        }

        $container->setAlias(ResponseStorageInterface::class, $defaultResponseStorage);

        if ($container->hasDefinition('jms_serializer.metadata_factory')) {
            $container->register('webslon_api.model_describers.jms', JMSModelDescriber::class)
                ->setPublic(false)
                ->setArguments([
                    new Reference('jms_serializer.metadata_factory'),
                    new Reference('jms_serializer.naming_strategy'),
                    new Reference('annotation_reader'),
                ])
                ->addTag('nelmio_api_doc.model_describer', ['priority' => 1]);
        }

        if (class_exists('Webslon\Library\Leroymerlin\OldPuz\Client')) {
            $definition = $container->register('Webslon\Library\Leroymerlin\OldPuz\Client', 'Webslon\Library\Leroymerlin\OldPuz\Client')
                ->setPublic(true)
                ->setAutowired(true)
                ->addTag('monolog.logger', ['channel' => 'php.request_logging'])
            ;

            if (!$container->hasDefinition('monolog.logger.php.request_logging')) {
                return;
            }

            foreach ($definition->getArguments() as $index => $argument) {
                if ($argument instanceof Reference && 'logger' === (string) $argument) {
                    $definition->replaceArgument($index, $container->getDefinition('monolog.logger.php.request_logging'));
                }
            }
        }
    }
}


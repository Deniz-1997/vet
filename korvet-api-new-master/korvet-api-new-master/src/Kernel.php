<?php

namespace App;

use App\Packages\AMQP\PlaceholderResolver;
use App\Packages\AMQP\Router\Route;
use App\Packages\AMQP\Router\RouterCollection;
use App\Packages\AMQP\RPC\FileSystemResponseStorage;
use App\Packages\AMQP\RPC\RedisResponseStorage;
use App\Packages\AMQP\RPC\ResponseStorageInterface;
use App\Packages\Annotation\Enqueue\Consume;
use App\Packages\Annotation\Enqueue\CrudBatchConsume;
use App\Packages\Annotation\Enqueue\CrudConsume;
use App\Packages\Annotation\Enqueue\CrudProduce;
use App\Packages\Annotation\Enqueue\Produce;
use App\Packages\Consumer\Batch\CreateConsumer;
use App\Packages\Consumer\Batch\DeleteConsumer;
use App\Packages\Consumer\Batch\ReplaceConsumer;
use App\Packages\Consumer\Batch\UpdateConsumer;
use App\Packages\DependencyInjection\Compiler\AmqpConsumerPass;
use App\Packages\DependencyInjection\Compiler\ConfigurationPass;
use App\Packages\DependencyInjection\Compiler\EnqueueBatchSubscriberPass;
use App\Packages\DependencyInjection\Compiler\EnqueueProducerPass;
use App\Packages\DependencyInjection\Compiler\EnqueueSubscriberPass;
use App\Packages\DependencyInjection\Compiler\FixSentryExceptionListenerPass;
use App\Packages\Describer\JMSModelDescriber;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    public function getCacheDir(): string
    {
        return $this->getProjectDir() . '/var/cache/' . $this->environment;
    }

    public function getLogDir(): string
    {
        return $this->getProjectDir() . '/var/log';
    }

    public function registerBundles(): \Generator
    {
        $contents = require $this->getProjectDir() . '/config/bundles.php';
        foreach ($contents as $class => $envs) {
            if ($envs[$this->environment] ?? $envs['all'] ?? false) {
                yield new $class();
            }
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param LoaderInterface $loader
     * @throws \ReflectionException
     */
    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $container->addResource(new FileResource($this->getProjectDir() . '/config/bundles.php'));
        // Feel free to remove the "container.autowiring.strict_mode" parameter
        // if you are using symfony/dependency-injection 4.0+ as it's the default behavior
        $container->setParameter('container.autowiring.strict_mode', true);
        $container->setParameter('container.dumper.inline_class_loader', true);
        $confDir = $this->getProjectDir() . '/config';

        $loader->load($confDir . '/{packages}/*' . self::CONFIG_EXTS, 'glob');
        $loader->load($confDir . '/{packages}/' . $this->environment . '/**/*' . self::CONFIG_EXTS, 'glob');
        $loader->load($confDir . '/{services}' . self::CONFIG_EXTS, 'glob');
        $loader->load($confDir . '/{services}_' . $this->environment . self::CONFIG_EXTS, 'glob');

        $container->addCompilerPass(new EnqueueBatchSubscriberPass());
        $container->addCompilerPass(new AmqpConsumerPass());
        $container->addCompilerPass(new ConfigurationPass());
        $container->addCompilerPass(new EnqueueProducerPass());
        $container->addCompilerPass(new EnqueueSubscriberPass());
        $container->addCompilerPass(new FixSentryExceptionListenerPass());
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $confDir = $this->getProjectDir() . '/config';

        $routes->import($confDir . '/{routes}/*' . self::CONFIG_EXTS, 'glob');
        $routes->import($confDir . '/{routes}/' . $this->environment . '/**/*' . self::CONFIG_EXTS, 'glob');
        $routes->import($confDir . '/{routes}' . self::CONFIG_EXTS, 'glob');
//        if (is_file($confDir . '/{routes}/*' . self::CONFIG_EXTS)) {
//            $routes->import($confDir . '/{routes}/'. self::CONFIG_EXTS);
//        } elseif (is_file($path = $confDir . '/{routes}/'. self::CONFIG_EXTS)) {
//            (require $path)($routes->withPath($path), $this);
//        }
    }

}

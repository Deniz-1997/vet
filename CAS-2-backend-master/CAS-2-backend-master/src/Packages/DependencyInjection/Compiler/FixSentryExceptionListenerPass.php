<?php


namespace App\Packages\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class FixSentryExceptionListenerPass
 */
class FixSentryExceptionListenerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process (ContainerBuilder $container)
    {
        if ($container->hasDefinition('sentry.exception_listener')) {
            $exceptionListener = $container->getDefinition('sentry.exception_listener');
            $exceptionListener->setTags([]);
        }
    }
}

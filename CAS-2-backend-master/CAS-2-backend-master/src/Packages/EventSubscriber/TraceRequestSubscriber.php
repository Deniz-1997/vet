<?php

namespace App\Packages\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use App\Service\Trace\Tracer;

/**
 * Class TraceRequestSubscriber
 */
class TraceRequestSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST   => 'onRequest',
            KernelEvents::TERMINATE => 'onTerminate',
            KernelEvents::EXCEPTION => 'onException',
        ];
    }

    public function onRequest(GetResponseEvent $getResponseEvent)
    {
        Tracer::getInstance();
    }

    public function onTerminate(PostResponseEvent $postResponseEvent)
    {
        Tracer::getInstance()->flush();
    }

    public function onException(GetResponseForExceptionEvent $event)
    {
        Tracer::getInstance()->flush();
    }
}

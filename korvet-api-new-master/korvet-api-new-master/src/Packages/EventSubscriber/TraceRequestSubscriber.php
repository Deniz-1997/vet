<?php

namespace App\Packages\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
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

    public function onRequest(RequestEvent $getResponseEvent)
    {
        Tracer::getInstance();
    }

    public function onTerminate(TerminateEvent $postResponseEvent)
    {
        Tracer::getInstance()->flush();
    }

    public function onException(ExceptionEvent $event)
    {
        Tracer::getInstance()->flush();
    }
}

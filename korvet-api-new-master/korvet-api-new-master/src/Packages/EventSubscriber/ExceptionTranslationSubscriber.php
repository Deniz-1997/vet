<?php

namespace App\Packages\EventSubscriber;

use App\Model\Env;
use App\Packages\Event\AfterBaseResponseSendEvent;
use App\Packages\Event\BeforeBaseResponseSendEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Exception\ApiException;

class ExceptionTranslationSubscriber implements EventSubscriberInterface
{
    /**
     * @var bool
     */
    private $isResponseMidified;

    /**
     * @var array
     */
    private $originalErrors;

    /**
     * @var object|array|null
     */
    private $originalResponse;

    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var array
     */
    private $supportedEnvironments;

    /**
     * ExceptionTranslationSubscriber constructor.
     * @param TranslatorInterface $translator
     * @param array $supportedEnvironments
     */
    public function __construct(TranslatorInterface $translator, $supportedEnvironments = [])
    {
        $this->translator = $translator;
        $this->originalErrors = [];
        $this->isResponseMidified = false;
        $this->supportedEnvironments = $supportedEnvironments;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * ['eventName' => 'methodName']
     *  * ['eventName' => ['methodName', $priority]]
     *  * ['eventName' => [['methodName1', $priority], ['methodName2']]]
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            BeforeBaseResponseSendEvent::class => [
                'beforeBaseResponseSendHandler',
            ],
            AfterBaseResponseSendEvent::class => [
                'afterBaseResponseSendHandler',
            ],
        ];
    }

    /**
     * @param BeforeBaseResponseSendEvent $event
     */
    public function beforeBaseResponseSendHandler(BeforeBaseResponseSendEvent $event)
    {
        if (! in_array(Env::getenv('APP_ENV'), $this->supportedEnvironments)) {
            return;
        }

        $response = $event->getResponse();
        if ($response->isStatusOk() || empty($response->getErrors())) {
            return;
        }

        $this->originalResponse = $response->getResponse();
        $this->originalErrors = $response->getErrors();

/*        $firstError = $this->originalErrors[0];

        if ($firstError->getCode() === Response::HTTP_NOT_FOUND) {
            $newException = new ApiException($this->translator->trans('Not Found'), 'ENTITY_NOT_FOUND', null, Response::HTTP_NOT_FOUND);
        } else {
            $newException = new ApiException($this->translator->trans('Internal server error'));
        }

        $response->setErrors([$newException])->setResponse(null);*/
        $this->isResponseMidified = true;
    }

    public function afterBaseResponseSendHandler(AfterBaseResponseSendEvent $event)
    {
        if ($this->isResponseMidified) {
            $response = $event->getResponse();
            $response->setErrors($this->originalErrors)->setResponse($this->originalResponse);
            $this->isResponseMidified = false;
        }
    }
}

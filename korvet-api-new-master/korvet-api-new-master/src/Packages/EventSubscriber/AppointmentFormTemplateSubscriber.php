<?php


namespace App\Packages\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Packages\EventDispatcher\EventRequest;

class AppointmentFormTemplateSubscriber  implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'onBeforeProcessAppEntityAppointmentAppointmentFormTemplatePatch' => 'onBeforeSaveAppointmentFormTemplate',
            'onBeforeProcessAppEntityAppointmentAppointmentFormTemplatePut' => 'onBeforeSaveAppointmentFormTemplate',
            'onBeforeProcessAppEntityAppointmentAppointmentFormTemplatePost' => 'onBeforeSaveAppointmentFormTemplate'
        );
    }

    /**
     * @param EventRequest $event
     */
    public function onBeforeSaveAppointmentFormTemplate(EventRequest $event)
    {
        $data = $event->getData();
        $appointmentFormTemplate = json_decode($data['content'], true);

        foreach ($appointmentFormTemplate['formFieldsValues'] as $formFieldKey => $formFieldValue) {
            $value = strval($formFieldValue['value']);
            $appointmentFormTemplate['formFieldsValues'][$formFieldKey]['value'] = $value;
        }
        $event->setData(json_encode($appointmentFormTemplate));
    }
}

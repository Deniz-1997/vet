<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendNotifications extends Mailable
{
    use Queueable, SerializesModels;

    private $_text;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($text)
    {
        $this->_text = $text;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Оповещение от КОРВЕТ')
            ->from(env('MAIL_USERNAME'))
            ->markdown('vendor.notifications.email', [
                'greeting'   =>  'Здравствуйте',
                'level'   =>  'success',
                'line'   =>  $this->_text]);
    }
}

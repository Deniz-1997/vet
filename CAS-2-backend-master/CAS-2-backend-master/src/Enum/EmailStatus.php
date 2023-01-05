<?php

namespace App\Enum;

final class EmailStatus
{
    public const NEW = 1;
    public const SENT = 2;
    public const ERROR = 3;

    /**
     * @var array
     */
    private static $choices = [
        'sms.status.new' => self::NEW,
        'sms.status.sent' => self::SENT,
        'sms.status.error' => self::ERROR,
    ];

    /**
     * @return array
     */
    public static function getChoices(): array
    {
        return self::$choices;
    }
}

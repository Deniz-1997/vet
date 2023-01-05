<?php

namespace App;


use App\Jobs\JobSendNotificationsTelegram;
use Log;

/**
 * Class Logger
 * @package App
 */
class Logger
{

    /**
     * @param string $text
     */
    public static function alert(string $text): void
    {
        if (env('NOTIFY_ALERT')) {
            self::send("Notify ALERT: $text");
        }

        Log::alert($text);
    }

    /**
     * @param string $text
     */
    public static function warning(string $text): void
    {
        if (env('NOTIFY_ERROR')) {
            self::send("Notify WARNING: $text");
        }

        Log::warning($text);
    }

    /**
     * @param string $text
     */
    private static function send(string $text): void
    {
        JobSendNotificationsTelegram::dispatch($text)->onQueue(env('TUBE_TELEGRAM'))->delay(mt_rand(1, 5));
    }

    /**
     * @param string $text
     */
    public static function critical(string $text): void
    {
        if (env('NOTIFY_ERROR')) {
            self::send("Notify CRITICAL: $text");
        }

        Log::critical($text);
    }

    /**
     * @param string $text
     */
    public static function emergency(string $text): void
    {
        if (env('NOTIFY_ERROR')) {
            self::send("Notify EMERGENCY: $text");
        }

        Log::emergency($text);
    }

    /**
     * @param string $text
     */
    public static function error(string $text): void
    {
        if (env('NOTIFY_ERROR')) {
            self::send("Notify ERROR: $text");
        }

        Log::error($text);
    }

    /**
     * @param string $text
     */
    public static function notice(string $text): void
    {
        Log::notice($text);
    }

    /**
     * @param string $text
     */
    public static function info(string $text): void
    {
        Log::info($text);
    }

    /**
     * @param string $text
     */
    public static function debug(string $text): void
    {
        Log::debug($text);
    }

}

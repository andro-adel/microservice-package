<?php

namespace DD\MicroserviceCore\Classes;

use Illuminate\Support\Facades\Log;

class Logging
{
    /**
     * Summary of emergency
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function emergency(string $message, array $context = [])
    {
        Log::emergency($message, $context);
    }

    /**
     * Summary of alert
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function alert(string $message, array $context = [])
    {
        Log::alert($message, $context);
    }

    /**
     * Summary of critical
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function critical(string $message, array $context = [])
    {
        Log::critical($message, $context);
    }

    /**
     * Summary of error
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function error(string $message, array $context = [])
    {
        Log::error($message, $context);
    }

    /**
     * Summary of warning
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function warning(string $message, array $context = [])
    {
        Log::warning($message, $context);
    }

    /**
     * Summary of notice
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function notice(string $message, array $context = [])
    {
        Log::notice($message, $context);
    }

    /**
     * Summary of info
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function info(string $message, array $context = [])
    {
        Log::info($message, $context);
    }

    /**
     * Summary of debug
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function debug(string $message, array $context = [])
    {
        Log::debug($message, $context);
    }
}

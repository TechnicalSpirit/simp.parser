<?php

namespace SimpParser\Services\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Log
{
    private static ?Logger $logger = null;
    public static function init($path)
    {
        if(self::$logger === null)
        {
            self::$logger = new Logger('name');
            self::$logger->pushHandler(new StreamHandler("$path/log/info.log", Logger::INFO));
            self::$logger->pushHandler(new StreamHandler("$path/log/error.log", Logger::ERROR));
        }
        return self::$logger;
    }

    public static function info(string $text)
    {
        self::$logger->info($text);
    }

    public static function error(string $text)
    {
        self::$logger->error($text);
    }
}
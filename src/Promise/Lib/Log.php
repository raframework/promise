<?php
/**
 * User: coderd
 * Date: 2017/6/27
 * Time: 19:56
 */

namespace Promise\Lib;


use Rapkg\Log\Logger;

/**
 * Class Log
 * @package Promise\Lib
 * @method static emergency($message, array $context = [])
 * @method static alert($message, array $context = [])
 * @method static critical($message, array $context = [])
 * @method static error($message, array $context = [])
 * @method static warning($message, array $context = [])
 * @method static notice($message, array $context = [])
 * @method static info($message, array $context = [])
 * @method static debug($message, array $context = [])
 * @method static log($level, $message, array $context = [])
 */
class Log
{
    /**
     * @var Logger
     */
    private static $defaultLogger;

    public static function initDefaultLogger($filePath)
    {
        self::$defaultLogger = new Logger($filePath);
    }

    public static function setDefaultLevel($level)
    {
        self::$defaultLogger->withLevel($level);
    }

    public static function __callStatic($name, $arguments)
    {
        call_user_func_array([self::$defaultLogger, $name], $arguments);
    }
}
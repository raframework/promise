<?php
/**
 * User: coderd
 * Date: 2017/6/29
 * Time: 19:05
 */

namespace Promise\Lib\Support;


class Str
{
    public static function exceptionToString(\Exception $e)
    {
        return 'Unhandled exception \'' . get_class($e)  .'\' with message \'' . $e->getMessage() . '\''
            . ' in ' . $e->getFile() . ':' . $e->getLine() . "\nStack trace:\n" . $e->getTraceAsString();
    }

    public static function exceptionToStringWithoutLF(\Exception $e)
    {
        return 'Unhandled exception \'' . get_class($e)  .'\' with message \'' . $e->getMessage() . '\''
            . ' in ' . $e->getFile() . ':' . $e->getLine();
    }
}
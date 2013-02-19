<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 18/02/13
 * Time: 22:37
 * To change this template use File | Settings | File Templates.
 */

class Log {

    // Disable this when not debugging with logs frequently,
    // as it's a major performance and security hit.
    public static $loggingOn = true;

    // Make this location writable by apache, either chown www-data or chmod 777
    // THINK  SECURITY !!!!
    // log files could easily end up with plain text passwords, or worse, readable by anyone.

    public static $logFile = "/var/www/logging/geoportal_debug.log";

    public static function toFile($content) {
        if(Log::$loggingOn) {

            $date = new DateTime();
            $timestamp = $date->format('U = Y-m-d H:i:s');
            $complete = $timestamp . " --> " . $content . PHP_EOL;

            file_put_contents(Log::$logFile, $complete, FILE_APPEND | LOCK_EX);

        }
    }

}
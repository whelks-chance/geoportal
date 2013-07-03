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
//    public static function isLoggingOn() { return !variables::debug(); }

    // Make this location writable by apache, either chown www-data or chmod 777
    // THINK  SECURITY !!!!
    // log files could easily end up with plain text passwords, or worse, readable by anyone.

    public static $logFile = "/var/www/logging/geoportal_debug.log";
//    public static $logFile = "geoportal_debug.log";


    public static function toFile($content, $file = "", $timestamp = true, $append = true) {
        if($file == "") {
            $file = Log::$logFile;
        }

        if(variables::debug()) {

            if ($timestamp) {

                $date = new DateTime();
                $timestamp = $date->format('U = Y-m-d H:i:s');
                $content = $timestamp . " --> " . $content . PHP_EOL;

            }

            try {
                if ($append) {
                    file_put_contents($file, $content, FILE_APPEND | LOCK_EX);
                } else {
                    file_put_contents($file, $content, LOCK_EX);
                }

            } catch(Exception $e) {

            }
        }
    }

}
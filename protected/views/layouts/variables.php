<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 23/02/13
 * Time: 02:07
 * To change this template use File | Settings | File Templates.
 */

class variables {

    public static $geoserverRoot = 'http://192.168.56.102:8080/geoserver/WISERD/wms/';
//    public static $geoserverRoot = 'http://131.251.172.95:7000/geoserver/WISERD/wms/';

    public static $geoportalAddr = 'http://localhost/test-yii/test-yii/index.php?';

    public static $databaseAddr = "192.168.56.102";

    public static $databasePort = "7007";

    public static function debug()
    {
        return true;
    }
}
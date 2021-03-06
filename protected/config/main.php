<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
ob_start('My_OB');
function My_OB($str, $flags)
{
    //remove UTF-8 BOM
    $str = preg_replace("/\xef\xbb\xbf/","",$str);

    return $str;
}
return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'WISERD',
//    'defaultController' => 'myController/myAction',

    // preloading 'log' component
    'preload'=>array('log'),

    // autoloading model and component classes
    'import'=>array(
        'application.models.*',
        'application.components.*',
        'application.models.BusinessLogic.*',
        'application.models.DataAccess.*',
        'application.models.util.*',
        'application.models.DataFeedReaders.*',
        'application.views.layouts.*',

//        'application.models.RDFPHP.api.model.*',
//        'application.models.RDFPHP.api.util.*',
//        'application.models.RDFPHP.api.syntax.*',
//        'application.models.RDFPHP.api.*',

    ),

    'modules'=>array(
        // uncomment the following to enable the Gii tool

        'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'g11w15erd',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters'=>array('127.0.0.1','::1'),
        ),

    ),


    // application components
    'components'=>array(
        // do not use built-in jquery.js library
        'clientScript'=>array(
            'class' => 'CClientScript',
            'scriptMap' => array(
                'jquery.js'=>false,
            ),
            'coreScriptPosition' => CClientScript::POS_BEGIN,
        ),

        //TODO replace
//        'user'=>array(
//            // enable cookie-based authentication
//            'allowAutoLogin'=>true,
//        ),

        'session' => array(
            'timeout' => 86400,
        ),
        'user'=>array(
            'allowAutoLogin' => true,
            'autoRenewCookie' => true,
            'authTimeout' => 31557600,
        ),

        // uncomment the following to enable URLs in path-format
//        /*

        'urlManager'=>array(
            'urlFormat'=>'path',
            'rules'=>array(
                /*
                                'api/<id:\d+>/<title:.*?>'=>'post/view',

                                array('api/list', 'pattern'=>'api/<model:\w+>', 'verb'=>'GET'),
                                array('api/view', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'GET'),
                */
                'api/getMetadata/<val1:\w+>/<val2:\w+>'=>'API/doFunction',

                '/'=>'site/index',
                '<view:(about|textgui)>'=>'site/page',

                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',

//                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
//                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
//                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
            ),
        ),

//        */
//        'db'=>array(
//            'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
//        ),
        // uncomment the following to use a MySQL database
        /*
        'db'=>array(
            'connectionString' => 'mysql:host=localhost;dbname=testdrive',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ),
        */
        'db'=>array(
            'tablePrefix'=>'',
            'connectionString' => 'pgsql:host=192.168.56.103;port=5432;dbname=Geoportal',
            'username'=>'dataportal',
            'password'=>'d4t4p0rtalacce55',
            'charset'=>'UTF8',
        ),

        'authManager'=>array(
//            'class'=>'CDbAuthManager',
            'class'=>'CPhpAuthManager'
//            'connectionID'=>'db',
        ),

        'errorHandler'=>array(
            // use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning',
                ),
                // uncomment the following to show log messages on web pages
                /*
                array(
                    'class'=>'CWebLogRoute',
                ),
                */
            ),
        ),
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params'=>array(
        // this is used in contact page
        'adminEmail'=>'webmaster@example.com',
    ),
);
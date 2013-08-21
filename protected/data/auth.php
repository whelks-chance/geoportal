<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 20/08/13
 * Time: 18:56
 * To change this template use File | Settings | File Templates.
 */

return array(
    'user' => array (
        'type'=>CAuthItem::TYPE_ROLE,
        'description'=>'Can search public Surveys',
        'bizRule'=>'',
        'data'=>''
    ),

    'projectUser' => array (
        'type'=>CAuthItem::TYPE_ROLE,
        'description'=>'Can search users own private Surveys',
        'bizRule'=>'user',
        'data'=>''
    ),

    'hubAdmin' => array (
        'type'=>CAuthItem::TYPE_ROLE,
        'description'=>'Can edit visibility of all public and private Surveys',
        'bizRule'=>'',
        'children'=>array(
            'projectUser'
        ),
        'data'=>''
    ),

    'superAdmin' => array (
        'type'=>CAuthItem::TYPE_ROLE,
        'description'=>'Can create users and change users roles',
        'children'=>array(
            'hubAdmin'
        ),
        'bizRule'=>'',
        'data'=>''
    )
);
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 20/08/13
 * Time: 20:35
 * To change this template use File | Settings | File Templates.
 */


// We're logging in somewhere else, so this is just an CUserIdentity Object which does
// basically nothing, but allows Yii to know who we are.

class LoggedInUserIdentity  extends CUserIdentity
{
    public function authenticate()
    {

        $this->errorCode=self::ERROR_NONE;
        return !$this->errorCode;
    }
}

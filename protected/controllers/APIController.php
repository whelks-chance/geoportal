<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 09/07/13
 * Time: 17:22
 * To change this template use File | Settings | File Templates.
 */
class APIController extends Controller
{

    public function actiondoFunction() {
        $val1 = $_GET['val1'];
        $val2 = $_GET['val2'];

        Log::toFile('api caught ' . $val1 . " + " . $val2);
        echo 'good, that worked ' . $val1 . " " . $val2;
    }

}

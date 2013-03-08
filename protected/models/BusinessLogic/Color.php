<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 24/02/13
 * Time: 22:31
 * To change this template use File | Settings | File Templates.
 *
 * @property mixed R
 * @property mixed G
 * @property mixed B
 */

class Color {

    public $R = 0;
    public $G = 0;
    public $B = 0;

    public $Key = "";

    public static function FromArgb($rAverage, $gAverage, $bAverage)
    {
        return new Color();
    }
}
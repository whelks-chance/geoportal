<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 24/02/13
 * Time: 22:31
 * To change this template use File | Settings | File Templates.
 *
 * @property integer R
 * @property integer G
 * @property integer B
 */

class Color {

    public $R = "";
    public $G = "";
    public $B = "";

//    public $Key = "";

    public function getHex() {

        $thisR = dechex($this->R);
        if (strlen($thisR) == 1) {
            $thisR .= "0" . $thisR;
        }

        $thisG = dechex($this->G);
        if (strlen($thisG) == 1) {
            $thisG .= "0" . $thisG;
        }

        $thisB = dechex($this->B);
        if (strlen($thisB) == 1) {
            $thisB .= "0" . $thisB;
        }

        return "#" . $thisR . $thisG . $thisB;

    }
}
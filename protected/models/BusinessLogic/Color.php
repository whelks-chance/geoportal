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

    public function toString(){
        return $this->R . '-' . $this->G . '-' . $this->B;
    }


}
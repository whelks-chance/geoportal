<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 24/02/13
 * Time: 18:45
 * To change this template use File | Settings | File Templates.
 */

class ColorTranslator {


    public static function FromHtml($color)
    {
        if ($color[0] == '#')
            $color = substr($color, 1);

        $newColour = new Color();
        $newColour->R = $color[0].$color[1];
        $newColour->G = $color[2].$color[3];
        $newColour->B = $color[4].$color[5];

        return $newColour;
    }

    public static function ToHtml($param1)
    {
        return "";
    }
}
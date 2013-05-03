<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 24/02/13
 * Time: 18:45
 * To change this template use File | Settings | File Templates.
 */

class ColorTranslator {


    public static function FromHtml($color) {

//        Log::toFile("Color from r,g,b : " . $color);

        if ($color[0] == '#')
            $color = substr($color, 1);

        $newColour = new Color();
        $newColour->R = $color[0].$color[1];
        $newColour->G = $color[2].$color[3];
        $newColour->B = $color[4].$color[5];

//        Log::toFile("Color : " . print_r($newColour, true));

        return $newColour;
    }

    public static function FromArgb($rAverage, $gAverage, $bAverage)
    {

//        Log::toFile("Color from A r,g,b : " . $rAverage . "," . $gAverage . "," . $bAverage);



        $color = new Color();
        $color->R = dechex($rAverage);
        $color->G = dechex($gAverage);
        $color->B = dechex($bAverage);

//        $color = $this->tidyColour($color);

//        Log::toFile("Color : " . print_r($color, true));

        return $color;

    }

    /** @var $colour Color */
    public static function getHex($colour) {

        $thisR = $colour->R;
        if (strlen($thisR) == 1) {
            $thisR = "0" . $thisR;
        }

        $thisG = $colour->G;
        if (strlen($thisG) == 1) {
            $thisG = "0" . $thisG;
        }

        $thisB = $colour->B;
        if (strlen($thisB) == 1) {
            $thisB = "0" . $thisB;
        }

        $toReturn = "#" . $thisR . $thisG . $thisB;

//        Log::toFile("getHex : " . $toReturn);

        return $toReturn;
    }

    public static function ToHtml($param1)
    {
        return "";
    }
}
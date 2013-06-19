<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 19/06/13
 * Time: 16:35
 * To change this template use File | Settings | File Templates.
 */

class LegendBuilder{

    public function BuildLegend($type, $fromColour, $toColour, $fieldName, $min, $max, $classes, $layer, $labelName)
    {

        Log::toFile($type .' ' . $fieldName . ' '. $layer . ' ' . $labelName);

        $colorList = SLD::generateColourRange($fromColour, $toColour, SLD::generateEqualInterval(($max - $min), $classes), $classes - 1, $min);

        $font = 25;

        $im = @imagecreatetruecolor(150, (20 * sizeof($colorList)) + 30);
//        $im = @imagecreatetruecolor(strlen($string) * $font / 1.5, $font);


        imagesavealpha($im, true);

        imagealphablending($im, false);

        $white = imagecolorallocatealpha($im, 255, 255, 255, 127);

        imagefill($im, 0, 0, $white);

//        $lime = imagecolorallocate($im, 204, 255, 51);
        $black = imagecolorallocate($im, 0, 0, 0);


//            imagettftext($im, $font, 0, 0, $font - 3, $lime, "droid_mono.ttf", $string);
//        imagestring($im, 1, 5, 5,  $string, $black);

        $y1 = 10;
        $y2 = 25;

        $prevVal = 0;

        ForEach ($colorList as $key => $Colour) {
//            Log::toFile('legend colour ' . $key . ' ' . $Colour->toString());

            $colour = imagecolorallocate($im, hexdec($Colour->R), hexdec($Colour->G), hexdec($Colour->B));

            imagefilledrectangle ( $im , 19 , $y1-1 , 31 , $y2+1 , $black );
            imagefilledrectangle ( $im , 20 , $y1 , 30 , $y2 , $colour );

            $values = $prevVal . " - " . $key;


            $prevVal = intval($key) + 1;

            imagestring($im, 3, 45, $y1 + 2,  $values, $black);


            $y1 += 20;
            $y2 += 20;
        }


        header("Content-type: image/png");

        imagepng($im, null, 9);
//        imagepng($im, '/var/www/logging/imagefile.png', 9);

        imagedestroy($im);

    }

    public function CreateLayerLogo($toColour)
    {
        $im = @imagecreatetruecolor(20, 30);

        $white = imagecolorallocatealpha($im, 255, 255, 255, 127);

        imagefill($im, 0, 0, $white);

        $Colour = ColorTranslator::FromHtml($toColour);

        $colourInt = imagecolorallocate($im, hexdec($Colour->R), hexdec($Colour->G), hexdec($Colour->B));

        imagefilledrectangle ( $im , 4 , 6 , 16 , 24 , $colourInt );

        header("Content-type: image/png");

        imagepng($im, null, 9);
//        imagepng($im, '/var/www/logging/imagefile.png', 9);

        imagedestroy($im);
    }

}
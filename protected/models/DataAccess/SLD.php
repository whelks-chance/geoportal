<?

class SLD {

    Public Function DynamicSLD2($type, $fromColour, $toColour, $fieldName, $min, $max, $classes, $layer, $labelName) {
//            $xmlSettings = XmlWriterSettings();
//            $xmlSettings->CloseOutput = False;
//            $xmlSettings->Indent = True;


        $ns = "http://www.opengis.net/sld";
        $ogc = "http://www.opengis.net/ogc";
        $colorList = $this::generateColourRange($fromColour, $toColour, $this::generateEqualInterval(($max - $min), $classes), $classes - 1, $min);



//            $stream = New MemoryStream();

        //Using writer = Xml$writer->Create(stream, xmlSettings);
        @date_default_timezone_set("GMT");
        $writer = new XMLWriter();

        $writer->openMemory();

        $writer->startDocument('1.0', 'UTF-8');

        $writer->setIndent(true);


        // '$writer->WriteStartDocument();

        $writer->StartElement("StyledLayerDescriptor");
        $writer->WriteAttribute("version", "1.0.0");
        $writer->WriteAttribute("xmlns", $ns);

//        $writer->WriteAttribute( "xsi", "http://www.opengis.net/sld");
        $writer->WriteAttributeNS("xmlns", "sld", null, $ns);
        $writer->WriteAttributeNS("xmlns", "ogc", null, $ogc);
        $writer->WriteAttributeNS("xmlns", "gml", null, "http://www.opengis.net/gml");

        $writer->StartElement("sld:NamedLayer");

        // 'write sld name
        $writer->StartElement("sld:Name");
        $writer->text($layer);
        $writer->EndElement();

        $writer->StartElement("sld:UserStyle");
        // 'write featuretype
        $writer->StartElement("sld:FeatureTypeStyle");

        // 'write 0 rule

        $writer->StartElement("sld:Rule");

        // 'write sld Rule Title - adds label to Legend
        $writer->StartElement("sld:Title");
        $writer->text(0);
        $writer->EndElement();

        $writer->StartElement("ogc:Filter");

        $writer->StartElement("ogc:PropertyIsEqualTo");
        // 'write property name
        $writer->StartElement("ogc:PropertyName");
        $writer->text($fieldName);
        $writer->EndElement();
        // 'write literal
        $writer->StartElement("ogc:Literal");
        $writer->text(0);
        $writer->EndElement();


        // '} to
        $writer->EndElement();


        // '}
        $writer->EndElement();

        $writer->StartElement("sld:Polygonsymbolizer");

        // 'write fill tag
        $writer->StartElement("sld:Fill");

        // 'write fill param
        $writer->StartElement("CssParameter");
        $writer->WriteAttribute("name", "fill");

        // 'write fill colour
        $writer->text("#FFFFFF");
        $writer->EndElement();


        // 'write css fill opacity
        $writer->StartElement("CssParameter"); // '
        $writer->WriteAttribute("name", "fill-opacity");
        $writer->text(0.0);
        $writer->EndElement();
        // '}
        $writer->EndElement();
        // '}
        $writer->EndElement();


        // '}
        $writer->EndElement();


        $lowerBoundary = $min;

        /** @var $Colour Color */
        ForEach ($colorList as $key => $Colour) { // Colour(Of Integer, Color) In colorList {

            // 'write sld rule tag  for polygo$ns
            $writer->StartElement("sld:Rule");

            // 'write sld Rule Title - adds label to Legend
            $writer->StartElement("sld:Title");
            $writer->text((intval($lowerBoundary)) . " - " . $key);
            $writer->EndElement();

            $writer->StartElement("ogc:Filter");

            $writer->StartElement("ogc:PropertyIsBetween"); // ' "Function name=""categorize""");
            // 'write property name
            $writer->StartElement("ogc:PropertyName");
            $writer->text($fieldName);
            $writer->EndElement();
            // 'Lower Boundary
            $writer->StartElement("ogc:LowerBoundary");
            $writer->StartElement("ogc:Literal");
            $writer->text(intval($lowerBoundary));
            $writer->EndElement();
            // '} boundary
            $writer->EndElement();

            // 'upper boundary
            $writer->StartElement("ogc:UpperBoundary");
            // 'write value


            $writer->StartElement("ogc:Literal");
            $writer->text($key);
            $writer->EndElement();

            // '} Boundary
            $writer->EndElement();

            // '}
            $writer->EndElement();
            // 'write } filter
            $writer->EndElement();

            // 'write sld polygon tag
            $writer->StartElement("sld:Polygonsymbolizer");

            // 'write fill tag
            $writer->StartElement("sld:Fill");

            // 'write fill param
            $writer->StartElement("CssParameter");
            $writer->WriteAttribute("name", "fill");

            // 'write fill colour
            $writer->text(ColorTranslator::getHex($Colour)); //  "#" . dechex($Colour->R) . dechex($Colour->G) . dechex($Colour->B));
            $writer->EndElement();


            // 'write css fill opacity
            $writer->StartElement("CssParameter");  // '
            $writer->WriteAttribute( "name", "fill-opacity");
            $writer->text(0.85);
            $writer->EndElement();
            // '}
            $writer->EndElement();
            // 'start stroke
            $writer->StartElement("sld:Stroke");
            // 'write cssparam for outline
            $writer->StartElement("CssParameter");
            $writer->WriteAttribute("name", "stroke");
            $writer->text("#C0C0C0");
            $writer->EndElement();
            // 'width
            $writer->StartElement("CssParameter");
            $writer->WriteAttribute("name", "stroke-width");
            $writer->text(0.5);
            $writer->EndElement();


            // '}
            $writer->EndElement();

            // 'write } Symbolizer
            $writer->EndElement();

            // 'write } tag
            $writer->EndElement();

            $lowerBoundary = $key + 1;

        }


        // 'write label rules
        $writer->StartElement("sld:Rule");

        // 'write rule name
        $writer->StartElement("sld:Name");
        $writer->text("Default");
        $writer->EndElement();

        // 'write scale denominator
        $writer->StartElement("sld:MaxScaleDenominator");
        $writer->text(175000);
        $writer->EndElement();
        // 'text symoblizer
        $writer->StartElement("sld:TextSymbolizer");

        // 'write geom
        $writer->StartElement("sld:Geometry");
        // 'write property name
        $writer->StartElement("ogc:PropertyName");
        $writer->text("the_geom");
        $writer->EndElement();
        // '}
        $writer->EndElement();

        // 'write label field
        $writer->StartElement("sld:Label");

        $writer->StartElement("ogc:PropertyName");
        $writer->text($labelName);
        $writer->EndElement();
        // '}
        $writer->EndElement();

        // 'write font properties
        $writer->StartElement("sld:Font");

        // 'Font Name
        $writer->StartElement("sld:CssParameter");
        $writer->WriteAttribute("name", "font-family");
        $writer->text("Times New Roman");
        $writer->EndElement();

        // 'Font Size
        $writer->StartElement("sld:CssParameter");
        $writer->WriteAttribute("name", "font-size");
        $writer->text(10.5);
        $writer->EndElement();


        // 'font style
        $writer->StartElement("sld:CssParameter");
        $writer->WriteAttribute("name", "font-style");
        $writer->text("italic");
        $writer->EndElement();

        // 'font weight
        $writer->StartElement("sld:CssParameter");
        $writer->WriteAttribute("name", "font-weight");
        $writer->text("bold");
        $writer->EndElement();

        // 'write }
        $writer->EndElement();

        // 'label PLacement
        $writer->StartElement("sld:LabelPlacement");
        // 'point placement
        $writer->StartElement("sld:PointPlacement");
        // 'anchor Point
        $writer->StartElement("sld:AnchorPoint");
        // 'X
        $writer->StartElement("sld:AnchorPointX");
        $writer->text(0.2);
        $writer->EndElement();
        // 'Y

        $writer->StartElement("sld:AnchorPointY");
        $writer->text(0.2);
        $writer->EndElement();

        // '} point
        $writer->EndElement();
        // '} placement
        $writer->EndElement();
        // '} placement
        $writer->EndElement();

        // 'write Halo
        $writer->StartElement("sld:Halo");
        // 'radius
        $writer->StartElement("sld:Radius");
        $writer->text(1.0);
        $writer->EndElement();
        // 'fill colour
        $writer->StartElement("sld:Fill");

        $writer->StartElement("CssParameter");
        $writer->WriteAttribute("name", "fill");

        // 'write fill colour
        $writer->text("#FFFFFF");
        $writer->EndElement();


        // 'write css fill opacity
        $writer->StartElement("sld:CssParameter"); // '
        $writer->WriteAttribute("name", "fill-opacity");
        $writer->text(0.5);
        $writer->EndElement();



        // '}
        $writer->EndElement();

        // '}
        $writer->EndElement();

        // 'Font Colour
        $writer->StartElement("sld:CssParameter");
        $writer->WriteAttribute("name", "fill");

        // 'write fill colour
        $writer->text("#696969");
        $writer->EndElement();

        // 'write placement optio$ns

        $writer->StartElement("VendorOption");
        $writer->WriteAttribute("name", "polygonAlign");
        $writer->text("mbr");
        $writer->EndElement();

        $writer->StartElement("VendorOption");
        $writer->WriteAttribute("name", "maxDisplacement");
        $writer->text(20);
        $writer->EndElement();


        $writer->StartElement("VendorOption");
        $writer->WriteAttribute("name", "goodnessOfFit");
        $writer->text(1.0);
        $writer->EndElement();


        // '}
        $writer->EndElement();
        // '}
        $writer->EndElement();


        // ' }
        $writer->EndElement();
        // ' } user style
        $writer->EndElement();
        $writer->EndElement();

        $writer->EndDocument();

//        $writer->Flush();

        $xD = $writer->outputMemory(true);

//            $output = "";
//            $stream->Position = 0;
//            $sr = StreamReader($stream);
//
//            $xD = new XmlDocument();
//            $xD->LoadXml($sr->ReadToEnd());

//        $xD = $this->rmBOM($xD);

        Log::toFile($xD, "/var/www/logging/sld.xml", false, false);

        Return $xD;
    }


//    function rmBOM($string) {
//        if(substr($string, 0,3) == pack("CCC",0xef,0xbb,0xbf)) {
//            $string=substr($string, 3);
//        }
//    return $string;
//    }


    public static Function generateColourRange($fromColour, $ToColour, $intervalRange, $intervalCount, $min) {


        $classInterval = $min;

        $startColor = ColorTranslator::FromHtml($fromColour);
        $endColor = ColorTranslator::FromHtml($ToColour);

        $colourList = array();
        $i = 0;


        $rMax = hexdec($endColor->R);
        $gMax = hexdec($endColor->G);
        $bMax = hexdec($endColor->B);

        $rMin = hexdec($startColor->R);
        $gMin = hexdec($startColor->G);
        $bMin = hexdec($startColor->B);

        $intervalCount = $intervalCount +1;

        While ($i <= $intervalCount) {

            $rAverage = $rMin + (($rMax - $rMin) * $i / $intervalCount);
            $gAverage = $gMin + (($gMax - $gMin) * $i / $intervalCount);
            $bAverage = $bMin + (($bMax - $bMin) * $i / $intervalCount);

            $colourList[$classInterval] = ColorTranslator::FromArgb($rAverage, $gAverage, $bAverage);
            $classInterval = ($classInterval + $intervalRange);
            $i += 1;
        }

//        $colourList[$classInterval] = ColorTranslator::FromArgb(0, 0, 0);


        Return $colourList;


    }


    public static Function generateEqualInterval($Total, $intervals) {

        // ' Dim

//        $intervalRange = Math.Round($Total / $intervals, System.MidpointRounding.ToEven);
        $intervalRange = round($Total / $intervals, 0, PHP_ROUND_HALF_EVEN);


        Return $intervalRange;
    }

}
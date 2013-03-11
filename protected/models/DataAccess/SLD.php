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

        $writer->startDocument('1.0');

        $writer->setIndent(true);


        // '$writer->WriteStartDocument();

        $writer->StartElement("StyledLayerDescriptor");
        $writer->WriteAttribute("version", "1.0.0");
        $writer->WriteAttribute("xmlns", $ns);

        $writer->WriteAttributeNS("xmlns", "sld", null, $ns);
        $writer->WriteAttributeNS("xmlns", "ogc", null, "http://www.opengis.net/ogc");
        $writer->WriteAttributeNS("xmlns", "gml", null, "http://www.opengis.net/gml");

        $writer->StartElementNS("sld", "NamedLayer", $ns);

        // 'write sld name
        $writer->StartElementNS("sld", "Name", $ns);
        $writer->text($layer);
        $writer->EndElement();

        $writer->StartElementNS("sld", "UserStyle", $ns);
        // 'write featuretype
        $writer->StartElementNS("sld", "FeatureTypeStyle", $ns);

        // 'write 0 rule

        $writer->StartElementNS("sld", "Rule", $ns);

        // 'write sld Rule Title - adds label to Legend
        $writer->StartElementNS("sld", "Title", $ns);
        $writer->text(0);
        $writer->EndElement();

        $writer->StartElementNS("ogc", "Filter", $ogc);

        $writer->StartElementNS("ogc", "PropertyIsEqualTo", $ogc);
        // 'write property name
        $writer->StartElementNS("ogc", "PropertyName", $ogc);
        $writer->text($fieldName);
        $writer->EndElement();
        // 'write literal
        $writer->StartElementNS("ogc", "Literal", $ogc);
        $writer->text(0);
        $writer->EndElement();


        // '} to
        $writer->EndElement();


        // '}
        $writer->EndElement();

        $writer->StartElementNS("sld", "Polygonsymbolizer", $ns);

        // 'write fill tag
        $writer->StartElementNS("sld", "Fill", $ns);

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


        $previous = $min;

        ForEach ($colorList as $Colour) { // Colour(Of Integer, Color) In colorList {

            // 'write sld rule tag  for polygo$ns
            $writer->StartElementNS("sld", "Rule", $ns);

            // 'write sld Rule Title - adds label to Legend
            $writer->StartElementNS("sld", "Title", $ns);
            $writer->text((intval($previous) + 1) & " - " & $Colour->Key);
            $writer->EndElement();

            $writer->StartElementNS("ogc", "Filter", $ogc);

            $writer->StartElementNS("ogc", "PropertyIsBetween", $ogc); // ' "Function name=""categorize""");
            // 'write property name
            $writer->StartElementNS("ogc", "PropertyName", $ogc);
            $writer->text($fieldName);
            $writer->EndElement();
            // 'Lower Boundary
            $writer->StartElementNS("ogc", "LowerBoundary", $ogc);
            $writer->StartElementNS("ogc", "Literal", $ogc);
            $writer->text(intval($previous) + 1);
            $writer->EndElement();
            // '} boundary
            $writer->EndElement();

            // 'upper boundary
            $writer->StartElementNS("ogc", "UpperBoundary", $ogc);
            // 'write value


            $writer->StartElementNS("ogc", "Literal", $ogc);
            $writer->text($Colour->Key);
            $writer->EndElement();

            // '} Boundary
            $writer->EndElement();

            // '}
            $writer->EndElement();
            // 'write } filter
            $writer->EndElement();

            // 'write sld polygon tag
            $writer->StartElementNS("sld", "Polygonsymbolizer", $ns);

            // 'write fill tag
            $writer->StartElementNS("sld", "Fill", $ns);

            // 'write fill param
            $writer->StartElement("CssParameter");
            $writer->WriteAttribute("name", "fill");

            // 'write fill colour
            $writer->text("#" & dechex($Colour->R) & dechex($Colour->G) & dechex($Colour->B));
            $writer->EndElement();


            // 'write css fill opacity
            $writer->StartElement("CssParameter");  // '
            $writer->WriteAttribute( "name", "fill-opacity");
            $writer->text(0.85);
            $writer->EndElement();
            // '}
            $writer->EndElement();
            // 'start stroke
            $writer->StartElementNS("sld", "Stroke", $ns);
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

            $$previous = $Colour->Key;

        }


        // 'write label rules
        $writer->StartElementNS("sld", "Rule", $ns);

        // 'write rule name
        $writer->StartElementNS("sld", "Name", $ns);
        $writer->text("Default");
        $writer->EndElement();

        // 'write scale denominator
        $writer->StartElementNS("sld", "MaxScaleDenominator", $ns);
        $writer->text(175000);
        $writer->EndElement();
        // 'text symoblizer
        $writer->StartElementNS("sld", "TextSymbolizer", $ns);

        // 'write geom
        $writer->StartElementNS("sld", "Geometry", $ns);
        // 'write property name
        $writer->StartElementNS("ogc", "PropertyName", $ogc);
        $writer->text("the_geom");
        $writer->EndElement();
        // '}
        $writer->EndElement();

        // 'write label field
        $writer->StartElementNS("sld", "Label", $ns);

        $writer->StartElementNS("ogc", "PropertyName", $ogc);
        $writer->text($labelName);
        $writer->EndElement();
        // '}
        $writer->EndElement();

        // 'write font properties
        $writer->StartElementNS("sld", "Font", $ns);

        // 'Font Name
        $writer->StartElementNS("sld", "CssParameter", $ns);
        $writer->WriteAttribute("name", "font-family");
        $writer->text("Times New Roman");
        $writer->EndElement();

        // 'Font Size
        $writer->StartElementNS("sld", "CssParameter", $ns);
        $writer->WriteAttribute("name", "font-size");
        $writer->text(10.5);
        $writer->EndElement();


        // 'font style
        $writer->StartElementNS("sld", "CssParameter", $ns);
        $writer->WriteAttribute("name", "font-style");
        $writer->text("italic");
        $writer->EndElement();

        // 'font weight
        $writer->StartElementNS("sld", "CssParameter", $ns);
        $writer->WriteAttribute("name", "font-weight");
        $writer->text("bold");
        $writer->EndElement();

        // 'write }
        $writer->EndElement();

        // 'label PLacement
        $writer->StartElementNS("sld", "LabelPlacement", $ns);
        // 'point placement
        $writer->StartElementNS("sld", "PointPlacement", $ns);
        // 'anchor Point
        $writer->StartElementNS("sld", "AnchorPoint", $ns);
        // 'X
        $writer->StartElementNS("sld", "AnchorPointX", $ns);
        $writer->text(0.2);
        $writer->EndElement();
        // 'Y

        $writer->StartElementNS("sld", "AnchorPointY", $ns);
        $writer->text(0.2);
        $writer->EndElement();

        // '} point
        $writer->EndElement();
        // '} placement
        $writer->EndElement();
        // '} placement
        $writer->EndElement();

        // 'write Halo
        $writer->StartElementNS("sld", "Halo", $ns);
        // 'radius
        $writer->StartElementNS("sld", "Radius", $ns);
        $writer->text(1.0);
        $writer->EndElement();
        // 'fill colour
        $writer->StartElementNS("sld", "Fill", $ns);

        $writer->StartElement("CssParameter");
        $writer->WriteAttribute("name", "fill");

        // 'write fill colour
        $writer->text("#FFFFFF");
        $writer->EndElement();


        // 'write css fill opacity
        $writer->StartElementNS("sld", "CssParameter", $ns); // '
        $writer->WriteAttribute("name", "fill-opacity");
        $writer->text(0.5);
        $writer->EndElement();



        // '}
        $writer->EndElement();

        // '}
        $writer->EndElement();

        // 'Font Colour
        $writer->StartElementNS("sld", "CssParameter", $ns);
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

        Return $xD;
    }


    Private Function generateColourRange($fromColour, $ToColour, $intervalRange, $intervalCount, $min) {


        $classInterval = $min;

            $startColor = ColorTranslator::FromHtml($fromColour);
            $endColor = ColorTranslator::FromHtml($ToColour);

            $colourList = array();
            $i = 0;


            $rMax = $endColor->R;
            $rMin = $startColor->R;
            $gMax = $endColor->G;
            $gMin = $startColor->G;
            $bMax = $endColor->B;
            $bMin = $startColor->B;



            While ($i <= $intervalCount) {

                $rAverage = $rMin + (($rMax - $rMin) * $i / $intervalCount);
                $gAverage = $gMin + (($gMax - $gMin) * $i / $intervalCount);
                $bAverage = $bMin + (($bMax - $bMin) * $i / $intervalCount);

                $colourList[$classInterval] = Color::FromArgb($rAverage, $gAverage, $bAverage);
                $classInterval = ($classInterval + $intervalRange);
                $i += 1;
            }



            Return $colourList;


        }


    Private Function generateEqualInterval($Total, $intervals) {

        // ' Dim

//        $intervalRange = Math.Round($Total / $intervals, System.MidpointRounding.ToEven);
        $intervalRange = round($Total / $intervals, 0, PHP_ROUND_HALF_EVEN);


        Return $intervalRange;
    }

}
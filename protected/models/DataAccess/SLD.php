<?

class SLD {

    Public Function DynamicSLD2($type, $fromColour, $toColour, $fieldName, $min, $max, $classes, $layer, $labelName) {
//            $xmlSettings = XmlWriterSettings();
//            $xmlSettings->CloseOutput = False;
//            $xmlSettings->Indent = True;


        $ns = "http://www.opengis.net/sld";
        $ogc = "http://www.opengis.net/ogc";
        $colorList = generateColourRange($fromColour, $toColour, generateEqualInterval(($max - $min), $classes), $classes - 1, $min);



//            $stream = New MemoryStream();

        //Using writer = Xml$writer->Create(stream, xmlSettings);

        $writer = new XMLWriter();

        $writer->setIndent(true);

        $writer->openMemory();

        // '$writer->WriteStartDocument();

        $writer->StartElement("StyledLayerDescriptor", $ns);
        $writer->WriteAttribute("version", "1.0.0");
        $writer->WriteAttribute("xml$ns", $ns);
        $writer->WriteAttribute("xml$ns", "sld", null, $ns);
        $writer->WriteAttribute("xml$ns", "ogc", null, "http://www.opengis.net/ogc");
        $writer->WriteAttribute("xml$ns", "gml", null, "http://www.opengis.net/gml");

        $writer->StartElement("sld", "NamedLayer", $ns);

        // 'write sld name
        $writer->StartElement("sld", "Name", $ns);
        $writer->text($layer);
        $writer->EndElement();

        $writer->StartElement("sld", "UserStyle", $ns);
        // 'write featuretype
        $writer->StartElement("sld", "FeatureTypeStyle", $ns);

        // 'write 0 rule

        $writer->StartElement("sld", "Rule", $ns);

        // 'write sld Rule Title - adds label to Legend
        $writer->StartElement("sld", "Title", $ns);
        $writer->text(0);
        $writer->EndElement();

        $writer->StartElement("ogc", "Filter", $ogc);

        $writer->StartElement("ogc", "PropertyIsEqualTo", $ogc);
        // 'write property name
        $writer->StartElement("ogc", "PropertyName", $ogc);
        $writer->text($fieldName);
        $writer->EndElement();
        // 'write literal
        $writer->StartElement("ogc", "Literal", $ogc);
        $writer->text(0);
        $writer->EndElement();


        // '} to
        $writer->EndElement();


        // '}
        $writer->EndElement();

        $writer->StartElement("sld", "Polygonsymbolizer", $ns);

        // 'write fill tag
        $writer->StartElement("sld", "Fill", $ns);

        // 'write fill param
        $writer->StartElement("CssParameter");
        $writer->WriteAttribute("name", "fill");

        // 'write fill colour
        $writer->text("#FFFFFF");
        $writer->EndElement();


        // 'write css fill opacity
        $writer->StartElement("CssParameter"); // '
        $writer->WriteAttribute(null, "name", null, "fill-opacity");
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
            $writer->StartElement("sld", "Rule", $ns);

            // 'write sld Rule Title - adds label to Legend
            $writer->StartElement("sld", "Title", $ns);
            $writer->text(($previous + 1) & " - " & $Colour->Key);
            $writer->EndElement();

            $writer->StartElement("$ogc", "Filter", $ogc);

            $writer->StartElement("$ogc", "PropertyIsBetween", $ogc); // ' "Function name=""categorize""");
            // 'write property name
            $writer->StartElement("$ogc", "PropertyName", $ogc);
            $writer->text($fieldName);
            $writer->EndElement();
            // 'Lower Boundary
            $writer->StartElement("$ogc", "LowerBoundary", $ogc);
            $writer->StartElement("$ogc", "Literal", $ogc);
            $writer->text($previous + 1);
            $writer->EndElement();
            // '} boundary
            $writer->EndElement();

            // 'upper boundary
            $writer->StartElement("$ogc", "UpperBoundary", $ogc);
            // 'write value


            $writer->StartElement("$ogc", "Literal", $ogc);
            $writer->text($Colour->Key);
            $writer->EndElement();

            // '} Boundary
            $writer->EndElement();

            // '}
            $writer->EndElement();
            // 'write } filter
            $writer->EndElement();

            // 'write sld polygon tag
            $writer->StartElement("sld", "Polygonsymbolizer", $ns);

            // 'write fill tag
            $writer->StartElement("sld", "Fill", $ns);

            // 'write fill param
            $writer->StartElement("CssParameter");
            $writer->WriteAttribute("name", "fill");

            // 'write fill colour
            $writer->text("#" & Hex($Colour->Value.R) & Hex($Colour->Value.G) & Hex($Colour->Value.B));
            $writer->EndElement();


            // 'write css fill opacity
            $writer->StartElement("CssParameter");  // '
            $writer->WriteAttribute(null, "name", null, "fill-opacity");
            $writer->text(0.85);
            $writer->EndElement();
            // '}
            $writer->EndElement();
            // 'start stroke
            $writer->StartElement("sld", "Stroke", $ns);
            // 'write cssparam for outline
            $writer->StartElement("CssParameter");
            $writer->WriteAttribute(null, "name", null, "stroke");
            $writer->text("#C0C0C0");
            $writer->EndElement();
            // 'width
            $writer->StartElement("CssParameter");
            $writer->WriteAttribute(null, "name", null, "stroke-width");
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
        $writer->StartElement("sld", "Rule", $ns);

        // 'write rule name
        $writer->StartElement("sld", "Name", $ns);
        $writer->text("Default");
        $writer->EndElement();

        // 'write scale denominator
        $writer->StartElement("sld", "MaxScaleDenominator", $ns);
        $writer->text(175000);
        $writer->EndElement();
        // 'text symoblizer
        $writer->StartElement("sld", "TextSymbolizer", $ns);

        // 'write geom
        $writer->StartElement("sld", "Geometry", $ns);
        // 'write property name
        $writer->StartElement("$ogc", "PropertyName", $ogc);
        $writer->text("the_geom");
        $writer->EndElement();
        // '}
        $writer->EndElement();

        // 'write label field
        $writer->StartElement("sld", "Label", $ns);

        $writer->StartElement("$ogc", "PropertyName", $ogc);
        $writer->text($labelName);
        $writer->EndElement();
        // '}
        $writer->EndElement();

        // 'write font properties
        $writer->StartElement("sld", "Font", $ns);

        // 'Font Name
        $writer->StartElement("sld", "CssParameter", $ns);
        $writer->WriteAttribute(null, "name", null, "font-family");
        $writer->text("Times New Roman");
        $writer->EndElement();

        // 'Font Size
        $writer->StartElement("sld", "CssParameter", $ns);
        $writer->WriteAttribute(null, "name", null, "font-size");
        $writer->text(10.5);
        $writer->EndElement();


        // 'font style
        $writer->StartElement("sld", "CssParameter", $ns);
        $writer->WriteAttribute(null, "name", null, "font-style");
        $writer->text("italic");
        $writer->EndElement();

        // 'font weight
        $writer->StartElement("sld", "CssParameter", $ns);
        $writer->WriteAttribute(null, "name", null, "font-weight");
        $writer->text("bold");
        $writer->EndElement();

        // 'write }
        $writer->EndElement();

        // 'label PLacement
        $writer->StartElement("sld", "LabelPlacement", $ns);
        // 'point placement
        $writer->StartElement("sld", "PointPlacement", $ns);
        // 'anchor Point
        $writer->StartElement("sld", "AnchorPoint", $ns);
        // 'X
        $writer->StartElement("sld", "AnchorPointX", $ns);
        $writer->text(0.2);
        $writer->EndElement();
        // 'Y

        $writer->StartElement("sld", "AnchorPointY", $ns);
        $writer->text(0.2);
        $writer->EndElement();

        // '} point
        $writer->EndElement();
        // '} placement
        $writer->EndElement();
        // '} placement
        $writer->EndElement();

        // 'write Halo
        $writer->StartElement("sld", "Halo", $ns);
        // 'radius
        $writer->StartElement("sld", "Radius", $ns);
        $writer->text(1.0);
        $writer->EndElement();
        // 'fill colour
        $writer->StartElement("sld", "Fill", $ns);

        $writer->StartElement("CssParameter");
        $writer->WriteAttribute("name", "fill");

        // 'write fill colour
        $writer->text("#FFFFFF");
        $writer->EndElement();


        // 'write css fill opacity
        $writer->StartElement("sld", "CssParameter", $ns); // '
        $writer->WriteAttribute(null, "name", null, "fill-opacity");
        $writer->text(0.5);
        $writer->EndElement();



        // '}
        $writer->EndElement();

        // '}
        $writer->EndElement();

        // 'Font Colour
        $writer->StartElement("sld", "CssParameter", $ns);
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

        $writer->Flush();

        $xD = $writer->outputMemory();

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

            $startColor = ColorTranslator->FromHtml($fromColour);
            $endColor = ColorTranslator->FromHtml($ToColour);

            $colourList = array();
            $i = 0;


            $rMax = $endColor->R;
            $rMin = $startColor->R;
            $gMax = $endColor->G;
            $gMin = $startColor->G;
            $bMax = $endColor->B;
            $bMin = $startColor->B;



            While ($i <= $intervalCount) {

                $rAverage = $rMin + CInt(($rMax - $rMin) * $i / $intervalCount);
                $gAverage = $gMin + CInt(($gMax - $gMin) * $i / $intervalCount);
                $bAverage = $bMin + CInt(($bMax - $bMin) * $i / $intervalCount);

                $colourList[$classInterval] = Color->FromArgb($rAverage, $gAverage, $bAverage);
                $classInterval = ($classInterval + $intervalRange);
                $i += 1;
            }



            Return $colourList;


        }


    Private Function generateEqualInterval($Total, $intervals) {

        // ' Dim

        $intervalRange = Math.Round($Total / $intervals, System.MidpointRounding.ToEven);



        Return $intervalRange;
    }

}
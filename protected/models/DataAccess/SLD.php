<?

class SLD {

        Public Function DynamicSLD2($type, $fromColour, $toColour, $fieldName, $min, $max, $classes, $layer, $labelName) {
            $xmlSettings = XmlWriterSettings();
            $xmlSettings->CloseOutput = False;
            $xmlSettings->Indent = True;


            $ns = "http://www.opengis.net/sld";
            $ogc = "http://www.opengis.net/ogc";
            $colorList = generateColourRange($fromColour, $toColour, generateEqualInterval(($max - $min), $classes), $classes - 1, $min);



            $stream = New MemoryStream();

            //Using writer = Xml$writer->Create(stream, xmlSettings);
    
            $writer = new XMLWriter();

                // '$writer->WriteStartDocument();

                $writer->WriteStartElement("StyledLayerDescriptor", $ns);
                $writer->WriteAttributeString("version", "1.0.0");
                $writer->WriteAttributeString("xml$ns", $ns);
                $writer->WriteAttributeString("xml$ns", "sld", null, $ns);
                $writer->WriteAttributeString("xml$ns", "ogc", null, "http://www.opengis.net/ogc");
                $writer->WriteAttributeString("xml$ns", "gml", null, "http://www.opengis.net/gml");

                $writer->WriteStartElement("sld", "NamedLayer", $ns);

                // 'write sld name
                $writer->WriteStartElement("sld", "Name", $ns);
                $writer->WriteString($layer);
                $writer->WriteEndElement();

                $writer->WriteStartElement("sld", "UserStyle", $ns);
                // 'write featuretype
                $writer->WriteStartElement("sld", "FeatureTypeStyle", $ns);

                // 'write 0 rule

                $writer->WriteStartElement("sld", "Rule", $ns);

                // 'write sld Rule Title - adds label to Legend
                $writer->WriteStartElement("sld", "Title", $ns);
                $writer->WriteString(0);
                $writer->WriteEndElement();

                $writer->WriteStartElement("ogc", "Filter", $ogc);

                $writer->WriteStartElement("ogc", "PropertyIsEqualTo", $ogc);
                // 'write property name
                $writer->WriteStartElement("ogc", "PropertyName", $ogc);
                $writer->WriteString($fieldName);
                $writer->WriteEndElement();
                // 'write literal
                $writer->WriteStartElement("ogc", "Literal", $ogc);
                $writer->WriteValue(0);
                $writer->WriteEndElement();


                // '} to
                $writer->WriteEndElement();


                // '}
                $writer->WriteEndElement();

                $writer->WriteStartElement("sld", "Polygonsymbolizer", $ns);

                // 'write fill tag
                $writer->WriteStartElement("sld", "Fill", $ns);

                // 'write fill param
                $writer->WriteStartElement("CssParameter");
                $writer->WriteAttributeString("name", "fill");

                // 'write fill colour
                $writer->WriteString("#FFFFFF");
                $writer->WriteEndElement();


                // 'write css fill opacity
                $writer->WriteStartElement("CssParameter"); // '
                $writer->WriteAttributeString(null, "name", null, "fill-opacity");
                $writer->WriteValue(0.0);
                $writer->WriteEndElement();
                // '}
                $writer->WriteEndElement();
                // '}
                $writer->WriteEndElement();


                // '}
                $writer->WriteEndElement();


                $previous = $min;

                ForEach ($colorList as $Colour) { // Colour(Of Integer, Color) In colorList {

                    // 'write sld rule tag  for polygo$ns
                    $writer->WriteStartElement("sld", "Rule", $ns);

                    // 'write sld Rule Title - adds label to Legend
                    $writer->WriteStartElement("sld", "Title", $ns);
                    $writer->WriteString(($previous + 1) & " - " & $Colour->Key);
                    $writer->WriteEndElement();

                    $writer->WriteStartElement("$ogc", "Filter", $ogc);

                    $writer->WriteStartElement("$ogc", "PropertyIsBetween", $ogc); // ' "Function name=""categorize""");
                    // 'write property name
                    $writer->WriteStartElement("$ogc", "PropertyName", $ogc);
                    $writer->WriteString($fieldName);
                    $writer->WriteEndElement();
                    // 'Lower Boundary
                    $writer->WriteStartElement("$ogc", "LowerBoundary", $ogc);
                    $writer->WriteStartElement("$ogc", "Literal", $ogc);
                    $writer->WriteValue($previous + 1);
                    $writer->WriteEndElement();
                    // '} boundary
                    $writer->WriteEndElement();

                    // 'upper boundary
                    $writer->WriteStartElement("$ogc", "UpperBoundary", $ogc);
                    // 'write value


                    $writer->WriteStartElement("$ogc", "Literal", $ogc);
                    $writer->WriteValue($Colour->Key);
                    $writer->WriteEndElement();

                    // '} Boundary
                    $writer->WriteEndElement();

                    // '}
                    $writer->WriteEndElement();
                    // 'write } filter
                    $writer->WriteEndElement();

                    // 'write sld polygon tag 
                    $writer->WriteStartElement("sld", "Polygonsymbolizer", $ns);

                    // 'write fill tag
                    $writer->WriteStartElement("sld", "Fill", $ns);

                    // 'write fill param
                    $writer->WriteStartElement("CssParameter");
                    $writer->WriteAttributeString("name", "fill");

                    // 'write fill colour
                    $writer->WriteString("#" & Hex($Colour->Value.R) & Hex($Colour->Value.G) & Hex($Colour->Value.B));
                    $writer->WriteEndElement();


                    // 'write css fill opacity
                    $writer->WriteStartElement("CssParameter");  // '
                    $writer->WriteAttributeString(null, "name", null, "fill-opacity");
                    $writer->WriteValue(0.85);
                    $writer->WriteEndElement();
                    // '}
                    $writer->WriteEndElement();
                    // 'start stroke
                    $writer->WriteStartElement("sld", "Stroke", $ns);
                    // 'write cssparam for outline
                    $writer->WriteStartElement("CssParameter");
                    $writer->WriteAttributeString(null, "name", null, "stroke");
                    $writer->WriteValue("#C0C0C0");
                    $writer->WriteEndElement();
                    // 'width
                    $writer->WriteStartElement("CssParameter");
                    $writer->WriteAttributeString(null, "name", null, "stroke-width");
                    $writer->WriteValue(0.5);
                    $writer->WriteEndElement();


                    // '}
                    $writer->WriteEndElement();

                    // 'write } Symbolizer
                    $writer->WriteEndElement();

                    // 'write } tag
                    $writer->WriteEndElement();

                    $$previous = $Colour->Key;

                }


                // 'write label rules
                $writer->WriteStartElement("sld", "Rule", $ns);

                // 'write rule name
                $writer->WriteStartElement("sld", "Name", $ns);
                $writer->WriteString("Default");
                $writer->WriteEndElement();

                // 'write scale denominator
                $writer->WriteStartElement("sld", "MaxScaleDenominator", $ns);
                $writer->WriteString(175000);
                $writer->WriteEndElement();
                // 'text symoblizer
                $writer->WriteStartElement("sld", "TextSymbolizer", $ns);

                // 'write geom
                $writer->WriteStartElement("sld", "Geometry", $ns);
                // 'write property name
                $writer->WriteStartElement("$ogc", "PropertyName", $ogc);
                $writer->WriteString("the_geom");
                $writer->WriteEndElement();
                // '}
                $writer->WriteEndElement();

                // 'write label field
                $writer->WriteStartElement("sld", "Label", $ns);

                $writer->WriteStartElement("$ogc", "PropertyName", $ogc);
                $writer->WriteString($labelName);
                $writer->WriteEndElement();
                // '}
                $writer->WriteEndElement();

                // 'write font properties   
                $writer->WriteStartElement("sld", "Font", $ns);

                // 'Font Name
                $writer->WriteStartElement("sld", "CssParameter", $ns);
                $writer->WriteAttributeString(null, "name", null, "font-family");
                $writer->WriteString("Times New Roman");
                $writer->WriteEndElement();

                // 'Font Size
                $writer->WriteStartElement("sld", "CssParameter", $ns);
                $writer->WriteAttributeString(null, "name", null, "font-size");
                $writer->WriteString(10.5);
                $writer->WriteEndElement();


                // 'font style
                $writer->WriteStartElement("sld", "CssParameter", $ns);
                $writer->WriteAttributeString(null, "name", null, "font-style");
                $writer->WriteString("italic");
                $writer->WriteEndElement();

                // 'font weight    
                $writer->WriteStartElement("sld", "CssParameter", $ns);
                $writer->WriteAttributeString(null, "name", null, "font-weight");
                $writer->WriteString("bold");
                $writer->WriteEndElement();

                // 'write }
                $writer->WriteEndElement();

                // 'label PLacement
                $writer->WriteStartElement("sld", "LabelPlacement", $ns);
                // 'point placement
                $writer->WriteStartElement("sld", "PointPlacement", $ns);
                // 'anchor Point
                $writer->WriteStartElement("sld", "AnchorPoint", $ns);
                // 'X
                $writer->WriteStartElement("sld", "AnchorPointX", $ns);
                $writer->WriteString(0.2);
                $writer->WriteEndElement();
                // 'Y

                $writer->WriteStartElement("sld", "AnchorPointY", $ns);
                $writer->WriteString(0.2);
                $writer->WriteEndElement();

                // '} point
                $writer->WriteEndElement();
                // '} placement
                $writer->WriteEndElement();
                // '} placement
                $writer->WriteEndElement();

                // 'write Halo             
                $writer->WriteStartElement("sld", "Halo", $ns);
                // 'radius
                $writer->WriteStartElement("sld", "Radius", $ns);
                $writer->WriteString(1.0);
                $writer->WriteEndElement();
                // 'fill colour
                $writer->WriteStartElement("sld", "Fill", $ns);

                $writer->WriteStartElement("CssParameter");
                $writer->WriteAttributeString("name", "fill");

                // 'write fill colour
                $writer->WriteString("#FFFFFF");
                $writer->WriteEndElement();


                // 'write css fill opacity
                $writer->WriteStartElement("sld", "CssParameter", $ns); // '
                $writer->WriteAttributeString(null, "name", null, "fill-opacity");
                $writer->WriteValue(0.5);
                $writer->WriteEndElement();



                // '}
                $writer->WriteEndElement();

                // '}               
                $writer->WriteEndElement();

                // 'Font Colour
                $writer->WriteStartElement("sld", "CssParameter", $ns);
                $writer->WriteAttributeString("name", "fill");

                // 'write fill colour
                $writer->WriteString("#696969");
                $writer->WriteEndElement();

                // 'write placement optio$ns

                $writer->WriteStartElement("VendorOption");
                $writer->WriteAttributeString("name", "polygonAlign");
                $writer->WriteString("mbr");
                $writer->WriteEndElement();

                $writer->WriteStartElement("VendorOption");
                $writer->WriteAttributeString("name", "maxDisplacement");
                $writer->WriteString(20);
                $writer->WriteEndElement();


                $writer->WriteStartElement("VendorOption");
                $writer->WriteAttributeString("name", "goodnessOfFit");
                $writer->WriteString(1.0);
                $writer->WriteEndElement();


                // '}
                $writer->WriteEndElement();
                // '}
                $writer->WriteEndElement();


                // ' }
                $writer->WriteEndElement();
                // ' } user style
                $writer->WriteEndElement();
                $writer->WriteEndElement();
                // '$writer->WriteEndDocument();

                $writer->Flush();
                $writer->Close();
            }


            $output = "";
            $stream->Position = 0;
            $sr = StreamReader($stream);

            $xD = new XmlDocument();
            $xD->LoadXml($sr->ReadToEnd());

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
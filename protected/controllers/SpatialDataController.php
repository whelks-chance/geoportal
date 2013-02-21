﻿<?
    class SpatialDataController extends Controller {

        // '
        // ' GET: /SpatialData
        // // <CompressFilter()>
        Function actiongetSpatialUnits($surveyID) {

            $SD = New SpatialData();

            $SU = $SD->getAvailableUnits($surveyID);

            echo '{"rows":' . json_encode($SU) . "}";


        }
        // // <CompressFilter()>
        Function actiongetSpatialLabel($TableName) {

            $SD = New SpatialData();

            $SL = $SD->getSpatialLabels($TableName);


            echo '{"rows":' . json_encode($SL) . "}";
        }
        // // <CompressFilter()>
        Function actiongetChoroFields($TableName) {

            $SD = New SpatialData();

            $SL = $SD->getChoroFields($TableName);


            echo '{"rows":' . json_encode($SL) . "}";
        }

        // // <CompressFilter()>
        Function actiongetSpatialSubUnit($TableName) {


            $SD = New SpatialData();

            $SSU = $SD->getSpatialSubUnits($TableName);

            echo '{"rows":' . json_encode($SSU) . "}";


        }


        // // <CompressFilter()>
        Function actiongetSpatialRefSubUnit($SID, $MajorUnit) {


            $SD = New SpatialData();

            $RSSU = $SD->getRefSpatialSubUnits($this::getTableName($SID, $MajorUnit));

            echo '{"rows":' . json_encode($RSSU) . "}";


        }


        // // <CompressFilter()>
        Function actiongetRefSpatialIndivudalUnits($UnitName, $MajorUnit, $SubUnit, $the_geom, $SID) {


            $SD = New SpatialData();

            $ISSU = $SD->getRefSpatialIndivudalUnits($UnitName, $MajorUnit, $SubUnit, $the_geom, $SID);

            echo '{"rows":' . json_encode($ISSU) . "}";


        }

        Private Function getTableName($SID, $unit) {

           If ( $unit == "Police Region" ) {
                Return "x_" . $SID . "_police_";
            } ElseIf ( $unit == "Assembly Economic Fora Area" ) {
                Return "x_" . $SID . "_aefa_";
            } ElseIf ( $unit == "Fire Brigade Region" ) {
                Return "x_" . $SID . "_fire_";
            } ElseIf ( $unit == "Lower Super Output Area" ) {
                Return "x_" . $SID . "_lsoa_";
            } ElseIf ( $unit == "Parliamentary Constituencies" ) {
                Return "x_" . $SID . "_parl_";
            } ElseIf ( $unit == "Postcode Sector" ) {
                Return "x_" . $SID . "_pcode_";
            } ElseIf ( $unit == "Unitary Authority" ) {
                Return "x_" . $SID . "_ua_";
            }

        }


        // // <CompressFilter()>
        Public Function actionGenerateSpatialData($SurveyID, $Unit, $SubUnit, $Outline, $Label, $fromColour, $toColour, $Choropleth, $ChoroplethField, $addLabels, $ClassMethod, $intervals) {

            $SD = New SpatialData();

            $shapes = $SD->GenerateSpatialData($SurveyID, $Unit, $SubUnit, GetBool($Unit), $Label, $fromColour, $toColour, GetBool($Choropleth), $ChoroplethField, GetBool($addLabels), $ClassMethod, $intervals);

           If ( sizeof($shapes) > 0 ) {
                Return '({"success": true, "shapes": ' . json_encode($shapes) . "})";
            } Else {
                $msg = New jsonMsg();
                $msg->success = False;
                $msg->message = "No Spatial Data currently available for this record.";
                Return json_encode($msg);
            }

        }


        // // <CompressFilter()>
        Public Function actiongetSpatialDataSets($data) {

            $dataSet = New Dictionary(Of String, spatialSearchLayers);

            $jsons = Newtonsoft.Json.Linq.JArray = json_decode($data);

            ForEach ( $json As JToken In jsons ) {

                $layer json_decode(Of spatialSearchLayers)(json);
                $dataSet->Add(layer.name, layer);
            }

            $spatialSearch = New SpatialData();

            $full_data As Dictionary(Of String, spatialSearchLayers) = spatialSearch->getChosenLayers(dataSet);

        }



        // <CompressFilter()>
        Public Function actionSpatialSearch($geography, $start, $limit, $Type) {

            $count;

            $SD = New SpatialData();


            $results = array();

            // 'Return "{""success"": true, ""data"":" . json_encode($results) . "}";

            $res = new SpatialSearch2();

           If ( $geography == "" ) {
                $results = Session("spatialResults");
                Select Case Type
                    Case "Quant"
                        $count = Session("quantCount");
                    Case "Qual"
                        $count = Session("qualCount");
                    Case "Grey"

                    Case "Admin"

                End Select

                $res = $results(0);
            } Else {
                $results = null;
               If ( $results == null ) {
                    $results = $SD->SpatialSearch(geography)

                    res = $results(0)
                    Select Case Type
                        Case "Quant"
                            $count = res.quantCount
                            Session.Add("quantCount", $count)
                        Case "Qual"
                            $count = res.qualCount
                            Session.Add("qualCount", $count)
                        Case "Grey"

                        Case "Admin"

                    End Select

                    // 'Return "{""quantData"":" . json_encode(res.quantData) . "}";

                    Session.Add("spatialResults", $results)

                }
            }


            // '$resultsset = New GeoPortal.Models.BusinessLogic.spatialResults

            // 'resultsset.totalCount = $count
            // 'resultsset.questions = json_encode(results(0).quantData)


            $pageResults = array();

            $cnt = $start;
            $cnt_end = cnt + limit;

            $Str = ""

            Select Case Type
                Case "Quant"

                    Do Until cnt = cnt_end Or cnt = res.quantCount;

                        pageResults.Add(res.quantData(res.quantData.Keys(cnt)));
                        cnt += 1;
                    Loop

                    $Str = "{""totalCount"":" . $count . ",""quantData"":" . json_encode(pageResults) . "}";


                Case "Qual"
                    Do Until cnt = cnt_end Or cnt = res.qualCount

                        pageResults.Add(res.qualData(res.qualData.Keys(cnt)));
                        cnt += 1;
                    Loop

                    Str = "{""totalCount"":" . $count . ",""qualData"":" . json_encode(pageResults) . "}";
                Case "Grey"

                Case "Admin"

            End Select

            Return Str;

        }


        // <CompressFilter()>
        Public Function actionVerifySpatialSearch($coords, $type, $dist) {

            $SD = New SpatialData();
            $msg = New jsonMsg();
           If ( $SD->VerifySpatialSearch($coords, $type, $dist) ) {
                $msg->success = True;
                $msg->message = "Results Found";
            } Else {
                $msg->success = False;
                $msg->message = "No Results found within search distance";

            }



            Return json_encode($msg);


        }

        // <CompressFilter()>
        Public Function actionGenerateQualSpatialData($ID) {

            $SD = New SpatialData();
            $shapes = $SD->generateQualSpatialData("red", $ID);

           If ( sizeof($shapes) > 0 ) {
                Return "({""success"": true, ""shapes"": " . json_encode($shapes) . "})";
            } Else {
                $msg = New jsonMsg();
                $msg->success = False;
                $msg->message = "No Spatial Data currently available for this record.";
                Return json_encode($msg);
            }

        }


        Private Function actionGetBool($Status) {
           If ( $Status == "on" ) {
                Return True;
            } Else {
                Return False;
            }

        }


        Public Function actiongetMinMax($TableName, $ColName) {

            $getMin = "Select MIN(" . $ColName . ") from " . $TableName;
            $getMax = "Select MAX(" . $ColName . ") from " . $TableName;

            $db = New getDBConnections();

            $dBCnn = $db->getDBConnection("Survey_Data");
            $cmd = pg_query($dBCnn, $getMin);

//            dBCnn.Open()

            $min = cmd.ExecuteScalar();

            cmd.CommandText = getMax;

            $max = cmd.ExecuteScalar();

//            dBCnn.Close()


            $minMax = array();
            $minMax[0] = $min;
            $minMax[1] = $max;

            Return json_encode($minMax);

        }

        // <CompressFilter()>
        Public Function actionDynamicSLD2($type, $fromColour, $toColour, $fieldName, $min, $max, $classes, $layer, $labelName) {

            $SLD = New SLD();

            $xmlDoc = $SLD->DynamicSLD2(type, fromColour, toColour, fieldName, min, max, classes, layer, labelName);

            $sw = New StringWriter();
            $txml = New XmlTextWriter($sw);
            $xmlDoc->WriteTo($txml);


            Return $sw;
        }




        // <CompressFilter()>
        Public Function actiongetFeatureInfo($lat, $lon, $tableIDs) {

            // ' create a list of tables from the JSON 
            $tables = json_decode(Of List(Of IdentifyFeatures))(tableIDs);
            $SD = New SpatialData();
            $results = $SD->getFeatureInfoTable($lat, $lon, $tables);


            Return json_encode($results);


        }

    }
﻿<?
class SpatialDataController extends Controller {

    // '
    // ' GET: /SpatialData
    // // <CompressFilter()>
    Function actiongetSpatialUnits() {

        $surveyID = "";
        if(isset($_POST['SurveyID'])) {
            $surveyID = $_POST['SurveyID'];
        }

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

        $dataSet = array();

        $jsons = json_decode($data, true); //Newtonsoft.Json.Linq.JArray

        ForEach ($jsons as $json) {

            $layer = json_decode($json);
            $dataSet[$layer->name] = $layer;
        }

        $spatialSearch = New SpatialData();

        $full_data = $spatialSearch->getChosenLayers($dataSet);

    }



    // <CompressFilter()>
    Public Function actionSpatialSearch() {

//        $count;
        $geography = "";
        if(isset($_POST['geography'])) {
            $geography = $_POST['geography'];
        }
        $start = "";
        if(isset($_POST['start'])) {
            $start = $_POST['start'];
        }
        $limit = "";
        if(isset($_POST['limit'])) {
            $limit = $_POST['limit'];
        }
        $Type = "";
        if(isset($_POST['type'])) {
            $Type = $_POST['type'];
        }

        $SD = New SpatialData();


        $results = array();

        $count = 0;

        // 'Return "{""success"": true, ""data"":" . json_encode($results) . "}";

        $res = new SpatialSearch2();

        If ( $geography == "" ) {
            $results = Yii::app()->session["spatialResults"];
            Switch ($Type) {
                case "Quant":
                    $count = intval(Yii::app()->session["quantCount"]);
                    break;
                case "Qual":
                    $count = intval(Yii::app()->session["qualCount"]);
                    break;
                case "Grey":
                    break;
                case "Admin":
                    break;
            }

            $res = $results[0];
        } Else {
            $results = null;
            If ( $results == null ) {
                $results = $SD->SpatialSearch($geography);

                $res = $results[0];
                Switch ($Type) {
                    case "Quant":
                        $count = intval($res->quantCount);
                        Yii::app()->session["quantCount"] = $count;
                        break;
                    case "Qual":
                        $count = intval($res->qualCount);
                        Yii::app()->session["qualCount"] = $count;
                        break;
                    case "Grey":

                        break;
                    case "Admin":

                }

                // 'Return "{""quantData"":" . json_encode(res.quantData) . "}";

                Yii::app()->session["spatialResults"] = $results;

            }
        }


        // '$resultsset = New GeoPortal.Models.BusinessLogic.spatialResults

        // 'resultsset.totalCount = $count
        // 'resultsset.questions = json_encode(results(0).quantData);


        $pageResults = array();

        $cnt = intval($start);
        $cnt_end = intVal($cnt) + intVal($limit);

        $Str = "";

        Switch ($Type) {

            case "Quant":

//                Log::toFile('cnt_end and quantCount : ' . $cnt_end . ' : ' . $res->quantCount);
//                Log::toFile('quantData : ' . print_r($res->quantData, true));
                $keys = array_keys($res->quantData);

                while ( $cnt < $cnt_end && $cnt < $res->quantCount) {

                    $pageResults[] = ($res->quantData[$keys[$cnt]]);
                    $cnt ++;
                }

//                Log::toFile('quantResults ' . print_r($pageResults, true));

                $Str = '{"totalCount":' . $count . ',"quantData":' . json_encode($pageResults) . "}";


                break;
            case "Qual":

//                Log::toFile('cnt_end and qualCount : ' . $cnt_end . ' : ' . $res->quantCount);
//                Log::toFile('qualData : ' . print_r($res->qualData, true));
                $keys = array_keys($res->qualData);

                while( $cnt < $cnt_end && $cnt < $res->qualCount) {

                    $pageResults[] = ($res->qualData[$keys[$cnt]]);
                    $cnt ++;
                }
//                Log::toFile('quantResults ' . print_r($pageResults, true));

                $Str = '{"totalCount":' . $count . ',"qualData":' . json_encode($pageResults) . '}';
                break;
            case "Grey":

                break;
            case "Admin":
                break;
        }

        echo $Str;

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
    Public Function actionGenerateQualSpatialData() {

        $ID = "";
        if(isset($_POST['ID'])) {
            $ID = $_POST['ID'];
        }

        $SD = New SpatialData();
        $shapes = $SD->generateQualSpatialData("red", $ID);

        If ( sizeof($shapes) > 0 ) {
            echo '({"success": true, "shapes": ' . json_encode($shapes) . "})";
        } Else {
            $msg = New jsonMsg();
            $msg->success = False;
            $msg->message = "No Spatial Data currently available for this record.";
            echo json_encode($msg);
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

//            dBCnn.Open();

        $min = $cmd->ExecuteScalar();

        $cmd->CommandText = $getMax;

        $max = $cmd->ExecuteScalar();

//            dBCnn.Close();


        $minMax = array();
        $minMax[0] = $min;
        $minMax[1] = $max;

        Return json_encode($minMax);

    }

    // <CompressFilter()>
    Public Function actionDynamicSLD() {
        $type = "";
        if(isset($_POST['type'])) {
            $type = $_POST['type'];
        }
        $fromColour = "";
        if(isset($_POST['fromColour'])) {
            $fromColour = $_POST['fromColour'];
        }
        $toColour = "";
        if(isset($_POST['toColour'])) {
            $toColour = $_POST['toColour'];
        }
        $fieldName = "";
        if(isset($_POST['fieldName'])) {
            $fieldName = $_POST['fieldName'];
        }
        $min = "";
        if(isset($_POST['min'])) {
            $min = $_POST['min'];
        }
        $max = "";
        if(isset($_POST['max'])) {
            $max = $_POST['max'];
        }
        $classes = "";
        if(isset($_POST['classes'])) {
            $classes = $_POST['classes'];
        }
        $layer = "";
        if(isset($_POST['layer'])) {
            $layer = $_POST['layer'];
        }
        $labelName = "";
        if(isset($_POST['labelName'])) {
            $labelName = $_POST['labelName'];
        }

        $SLD = New SLD();

        $xmlDoc = $SLD->DynamicSLD2($type, $fromColour, $toColour, $fieldName, $min, $max, $classes, $layer, $labelName);
//
//        $sw = New StringWriter();
//        $txml = New XmlTextWriter($sw);
//        $xmlDoc->WriteTo($txml);
//

        echo $xmlDoc;
    }




    // <CompressFilter()>
    Public Function actiongetFeatureInfo($lat, $lon, $tableIDs) {

        // ' create a list of tables from the JSON
        $tables = json_decode($tableIDs);
        $SD = New SpatialData();
        $results = $SD->getFeatureInfoTable($lat, $lon, $tables);


        Return json_encode($results);


    }

}
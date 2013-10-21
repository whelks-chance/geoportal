﻿<?
class SpatialData {

    Public Function getAvailableUnits($SurveyID) {

//        $dc = New getDBConnections();

//        $cnn = $dc->getDBConnection("Survey_Data");

        $selectStr = "Select * from survey_spatial_link where
        lower(surveyid) = :loweredSurveyID;";

        $loweredSurveyID = strtolower($SurveyID);

        $values = array(":loweredSurveyID" => $loweredSurveyID);

        $results = array();
//        $cmd = pg_query($cnn, $selectStr);

        //$cnn->Open();

//        $DAresultObjects = DataAdapter::DefaultExecuteAndRead($selectStr, "Survey_Data");
        $UnitResults = DataAdapter::DefaultPDOExecuteAndRead($selectStr, $values, "Survey_Data");

//        $resultObjects = $DA->Read($cmd);

        ForEach ($UnitResults->resultObject as $DR ) {
            $units = New AvailableSpatialUnits();
            $units->spatial_id = Trim($DR->spatial_id);
            $units->long_start = Trim($DR->long_start);
            $units->long_finish = Trim($DR->long_finish);
            $units->Name = $this::getFullName($DR->spatial_id);

            $spatialArray = explode("_", $DR->spatial_id);
            $units->short_name = strtolower($spatialArray[3]);

            $results[] = ($units);
        }


        Return $results;

    }


    Public Function getSpatialLabels($TableName) {

//        $dc = New getDBConnections();

//        $cnn = $dc->getDBConnection("Survey_Data");

        $selectStr = "Select column_name as name from information_schema.columns
        where table_name = :TableName;";

        $values = array(":TableName" => $TableName);

        $results = array();

//        $cmd = pg_query($cnn, $selectStr);

//        $DA = new DataAdapter();

//        $resultObjects = $DA->Read($cmd);

//        $resultObjects = DataAdapter::DefaultExecuteAndRead($selectStr, "Survey_Data");
        $spatialResults = DataAdapter::DefaultPDOExecuteAndRead($selectStr, $values, "Survey_Data");


        ForEach ($spatialResults->resultObject as $DR ) {
            $label = New SpatialLabels();
            $label->Name = Trim($DR->name);
            $results[] = ($label);
        }

//        $cnn->Close();

        Return $results;

    }

    Public Function getSpatialSubUnits($TableName) {

//        $dc = New getDBConnections();

//        $cnn = $dc->getDBConnection("Survey_Data");

//        $selectStr = "Select * from " . $TableName . ";";


        $results = array();

        $spatialSUAll = New SpatialSubUnits();
        $spatialSUAll->Name = "All";
        $results[] = ($spatialSUAll);

        if (DataAdapter::isTrustedTableName($TableName, "Survey_Data")) {
            $selectStr = "Select * from " . $TableName . ";";

            $resultObjects = DataAdapter::DefaultPDOExecuteAndRead($selectStr, null, "Survey_Data");

//        $cmd = pg_query($cnn, $selectStr);


//        $DA = new DataAdapter();

//        $resultObjects = $DA->Read($cmd);

            ForEach ($resultObjects->resultObject as $DR ) {
                $spatialSU = New SpatialSubUnits();

                $spatialSU->Name = Trim($DR->Item[1]);
                $results[] = ($spatialSU);
            }
        }
        Return $results;

    }


    Public Function getRefSpatialSubUnits($TableName) {

//        $dc = New getDBConnections();

//        $cnn = $dc->getDBConnection("Survey_Data");

//        $selectStr = "Select * from " . $TableName . ";";

        $results = array();

//        $cmd = pg_query($cnn, $selectStr);

        $spatialSUMap = New SpatialSubUnits();
        $spatialSUMap->Name = "Current Map Extent";
        $results[] = ($spatialSUMap);


//        $DA = new DataAdapter();

//        $resultObjects = $DA->Read($cmd);

        if (DataAdapter::isTrustedTableName($TableName, "Survey_Data")) {
        $selectStr = "Select * from " . $TableName . ";";
        $resultObjects = DataAdapter::DefaultPDOExecuteAndRead($selectStr, null, "Survey_Data");


        ForEach ($resultObjects->resultObject as $DR ) {
            $spatialSU = New SpatialSubUnits();

            $spatialSU->Name = Trim($DR->Item[1]);
            $results[] = ($spatialSU);
        }
        }

        Return $results;

    }

    Public Function getRefSpatialIndivudalUnits($UnitName, $MajorUnit, $SubUnit, $the_geom, $SID) {

//        $dc = New getDBConnections();

//        $cnn = $dc->getDBConnection("Survey_Data");
        $big_geom = "";
        $suffix = "";

        If ( $the_geom == "N/A" ) {

            $suffix = $this::getTableName($SID, $MajorUnit);

            $big_geom = "(SELECT the_geom from spatialdata." . $suffix . " WHERE area_name = '" . $SubUnit . "')";
        } Else {
            $big_geom = $the_geom;

        }


        $selectStr = "SELECT * FROM public." . $UnitName . " WHERE ST_Intersects('" . $big_geom . "', the_geom);";


        $results = array();

//        $cmd = pg_query($cnn, $selectStr);


        $spatialSUAll = New SpatialSubUnits();
        $spatialSUAll->Name = "All";
        $results[] = ($spatialSUAll);

//        $DA = new DataAdapter();

//        $resultObjects = $DA->Read($cmd);

        $resultObjects = DataAdapter::DefaultPDOExecuteAndRead($selectStr, null, "Survey_Data");

        ForEach ($resultObjects->resultObject as $DR ) {
            $spatialSU = New SpatialSubUnits();
            $spatialSU->Name = Trim($DR->Item[1]);
            $results[] = ($spatialSU);
        }

        Return $results;

    }


    Public Function getChoroFields($TableName) {

        $dc = New getDBConnections();

//        $cnn = $dc->getDBConnection("Survey_Data");

        $selectStr = "Select column_name as name from information_schema.columns
        where table_name = :TableName;";

        $values = array(":TableName" => $TableName);
        $results = array();

//        $cmd = pg_query($cnn, $selectStr);

//        //$cnn->Open();
//
//        $DR = $cmd->ExecuteReader;
//
//        // 'Advance reader through the first two records as not applicable for choropleth mapping;
//        $DR->Read();
//        $DR->Read();


//        $DA = new DataAdapter();

//        $resultObjects = $DA->Read($cmd);

//        $resultObjects = DataAdapter::DefaultExecuteAndRead($selectStr, "Survey_Data");
        $chloroResults = DataAdapter::DefaultPDOExecuteAndRead($selectStr, $values, "Survey_Data");


        //      unset($resultObjects[0]);
        //      unset($resultObjects[1]);

        ForEach ($chloroResults->resultObject as $DR ) {
            $label = New SpatialLabels;
            $label->Name = Trim($DR->name);
            $results[] = ($label);
        }

        Return $results;

    }


    Public Function getFullName($spatial_id) {
        $Name = "";
        $spatialArray = explode("_", $spatial_id);
        $postFix = strtolower($spatialArray[3]);

        If ( $postFix == "aefa" ) {
            $Name = "Assembly Economic Fora Area";
        } ElseIf ( $postFix == "fire" ) {
            $Name = "Fire Brigade Region";
        } ElseIf ( $postFix == "lsoa" ) {
            $Name = "Lower Super Output Area";
        } ElseIf ( $postFix == "parl" ) {
            $Name = "Parliamentary Constituencies";
        } ElseIf ( $postFix == "pcode" ) {
            $Name = "Postcode Sector";
        } ElseIf ( $postFix == "police" ) {
            $Name = "Police Region";
        } ElseIf ( $postFix == "ua" ) {
            $Name = "Unitary Authority";
        }

        Return $Name;

    }


    Public Function GenerateSpatialData($SurveyID, $Unit, $SubUnit, $Outline, $Label, $fromColour, $toColour, $Choropleth, $ChoroplethField, $addLabels, $ClassMethod, $Interval) {

        $results = array();

//        $db = New getDBConnections();
//
//        $cnn = $db->getDBConnection("Survey_Data");
//
////            $DT = New DataTable;
//
//        $Link_ID = "";

        $Link_ID = $this::getTableName($SurveyID, $Unit);

        $loweredLink = $Link_ID->ToLower;

        if(!DataAdapter::isTrustedTableName($loweredLink, "Survey_Data")) {
            return null;
        }


        If ( $SubUnit == "All" ) {
//            $queryStr = "SELECT area_name, total, successful, refused, no_contact, ineligible, other, response_rate, adjusted_rr, ST_AsEWKT(st_simplifypreservetopology(the_geom,$0->001)) as the_geom FROM " . $Link_ID->ToLower . ";";

            $queryStr = "SELECT area_name, total, successful, refused, no_contact, ineligible, other,
            response_rate, adjusted_rr, ST_AsEWKT(st_simplifypreservetopology(the_geom,$0->001))
            as the_geom FROM '" . $loweredLink . "';";

            $values = array();

        } Else {
//            $queryStr = "SELECT area_name, total, successful, refused, no_contact, ineligible, other, response_rate, adjusted_rr, ST_AsEWKT(st_simplifypreservetopology(the_geom,$0->001)) as the_geom FROM " . $Link_ID->ToLower . " WHERE area_name= '" . $SubUnit . "';";

            $queryStr = "SELECT area_name, total, successful, refused, no_contact, ineligible, other,
            response_rate, adjusted_rr, ST_AsEWKT(st_simplifypreservetopology(the_geom,$0->001))
            as the_geom FROM '" . $loweredLink . "' WHERE area_name= :SubUnit;";

            $values = array(":SubUnit" => $SubUnit);

        }

//        $resultRows = pg_query($cnn, $queryStr);
//
//        $DA = new DataAdapter();
//
//        $resultObjects = $DA->Read($resultRows);

        $resultObjects = DataAdapter::DefaultPDOExecuteAndRead($queryStr, $values, "Survey_Data");

        $cnt = sizeof($resultObjects->resultObject);


        If ( $cnt > 0 ) {
            // 'calculate choropleth stats;

            // 'If Interval >$DT->Rows->Count ) {
            // '   $Interval = $DT->Rows->Count;
            // '}


            ForEach ( $resultObjects->resultObject as $row ) {
                $total = $row->total;// 'DT->Compute("Sum(" . $ChoroplethField . ")", Nothing);

                $colorList = $this::generateColourRange($fromColour, $toColour, $this::generateEqualInterval($total, $Interval), $Interval);

                If ( $row->the_geom == null) {

                } Else {
                    $SU = New ResponseSpatialUnits();
                    $SU->TotalResp = $row->total;
                    $SU->ChoroField = $ChoroplethField;
                    $SU->ChoroValue = $row->$ChoroplethField;
                    $SU->LabelField = $row->$Label;
                    $SU->Name = $row->area_name;

                    $geomArray = explode(";",$row->the_geom);
                    $SU->WKT = $geomArray[1];
                    $SU->Colour = $this::getColour($colorList, $row->$ChoroplethField);

                    $results[] = ($SU);
                }
            }
        }

        Return $results;

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


        return null;

    }



    Public Function generateEqualInterval($Total, $intervals) {

        // ' $

        $intervalRange = ceil($Total / $intervals);


        Return $intervalRange;
    }


    Public Function generateColourRange($fromColour, $ToColour, $intervalRange , $intervalCount) {


        $classInterval = $intervalRange;

        $colorTranslator = new ColorTranslator();
        $startColor = $colorTranslator->FromHtml($fromColour);
        $endColor = $colorTranslator->FromHtml($ToColour);

        $colourList = array();
        $i = 0;;

        $rMax = $endColor->R;
        $rMin = $startColor->R;
        $gMax = $endColor->G;
        $gMin = $startColor->G;
        $bMax = $endColor->B;
        $bMin = $startColor->B;


        While ($i < $intervalCount) {

            $rAverage = $rMin + (($rMax - $rMin) * $i / $intervalCount);
            $gAverage = $gMin + (($gMax - $gMin) * $i / $intervalCount);
            $bAverage = $bMin + (($bMax - $bMin) * $i / $intervalCount);

            $colourList[$classInterval] = ColorTranslator::FromArgb($rAverage, $gAverage, $bAverage);
            $classInterval = $classInterval + $intervalRange + 1;
            $i += 1;
        }



        Return $colourList;


    }

    Public Function getColour($Colours, $value ) {

        ForEach ( $Colours as $item ) {//Colours;
            If ( $value == ($item->Key) ) {
                Return ColorTranslator::ToHtml($item->Value);
            }

        }
    }


    Public Function VerifySpatialSearch($coords, $type, $dist ){


//        $getTables = "SELECT * FROM geometry_columns where f_table_schema = 'public';";
//
//        $DB = New getDBConnections();
//        $cnn = $DB->getDBConnection("Survey_Data");
//
//        $DA = pg_query($cnn, $getTables);
//
//        $DT = New DataTable;
//
//        $DA->Fill($DT);

        $getTables = "SELECT * FROM geometry_columns where f_table_schema = 'public';";
        $DT = DataAdapter::DefaultPDOExecuteAndRead($getTables, null, "Survey_Data");

        ForEach ( $DT->resultObject as $row ) {

            $tableName = $row->f_table_name;
            $geom_col = $row->f_geometry_column;

            $selStr = ("SELECT * from  ");
            $selStr .= ($tableName . " ");
            $selStr .= ("WHERE ST_DWithin(ST_Transform(ST_SetSRID(ST_MakePoint(");
            $selStr .= ($coords);
            $selStr .= ("), 4326), 4326) ,ST_Transform(");
            $selStr .= ($tableName . "->" . $geom_col . ",");
            $selStr .= (" 4326)," . $dist . ") LIMIT 1;");

            $values = array(":coords" => $coords);

            $results = DataAdapter::DefaultPDOExecuteAndRead($selStr, $values, "Survey_Data");

//            $cmd = pg_query($cnn, $selStr);

            //$cnn->Open();
//            $DR = $cmd->ExecuteReader();

            If ( $results->resultSuccess ) {
//                $cnn->Close();
                Return True;
            }

//               $cnn->Close();

        }


        Return False;



    }

    Public Function SpatialSearch($geography) {

        $results = array();
        $SS = New SpatialSearch2();

//        $getTables = "SELECT * FROM geometry_columns where f_table_schema = 'public';";
//
//        $DB = New getDBConnections();
//
//        $cnn = $DB->getDBConnection("Survey_Data");
//
//        $query = pg_query($cnn, $getTables);
//
//        $DA = New DataAdapter();
//
//        $DT = $DA->Read($query);

        $tableMinMax = array();

        Log::toFile("preparing survey db query " . date("Y-m-d H:i:s"));

        $getTables = "SELECT * FROM geometry_columns where f_table_schema = 'public';";
        $DT = DataAdapter::DefaultPDOExecuteAndRead($getTables, null, "Survey_Data");

        ForEach ( $DT->resultObject as $row ) {
            $selStr = "";
            $tableName = $row->f_table_name;
            $geom_col = $row->f_geometry_column;


            $selStr .= ("SELECT area_name from " . $tableName);
//            $selStr .= (" WHERE ST_Intersects(ST_Transform(ST_GeometryFromText('" . $geography . "', 27700), 4326)," . $geom_col . ");");
            $selStr .= (" WHERE ST_Intersects(ST_Transform(ST_GeometryFromText(:geography, 27700), 4326)," . $geom_col . ");");


//            $queryResult = pg_query($cnn, $selStr);
//            $DA = New DataAdapter();
//            $resultsTable = $DA->Read($queryResult);
            $values = array(":geography" => $geography);
            $resultsTable = DataAdapter::DefaultPDOExecuteAndRead($selStr, $values, "Survey_Data");

            $surveyDetails = $this::getSurveyNameYear($tableName);


            ForEach ( $resultsTable->resultObject as $datarow ) {
                $quantsData = New quantDataRecord2();

                If ( sizeof($surveyDetails) == 0 ) {
                    $quantsData->sName = $tableName;
                    $quantsData->sYear = 9999;
                } Else {
                    $quantsData->sName = $surveyDetails['surveyName'];;
                    $quantsData->sYear = $surveyDetails['year'];;
                }


                // 'Convert text column to integer value and get min and max values;
                $min = "Select min(cast(successful as int)) from " . $tableName;
                $max = "Select max(cast(successful as int)) from " . $tableName;

                $tablenameArray = explode("_", $tableName);
                $quantsData->geography = strtoupper($tablenameArray[3]);
                $quantsData->tName = $tableName;
                $quantsData->sID = $tablenameArray[1] . "_" . $tablenameArray[2];

                If ( !array_key_exists($tableName, $tableMinMax) ) {
//                    $cmd = pg_query($cnn, $min);
//                       If ( $cnn->State = $ConnectionState->Closed ) {
//                           //$cnn->Open();
//
//                       }
//                       $minMax[1] ;
//                    $DA = new DataAdapter();
//                    $res = $DA->Read($cmd);
//                    $quantsData->min = $DA->execute_scalar($cnn, $min);

                    $minResult = DataAdapter::DefaultPDOExecuteAndRead($min, null, "Survey_Data");
                    if($minResult->hasRows()) {
                        $quantsData->min = $minResult->resultObject[0]->min;
                    }
//                    $quantsData->min = $cmd->ExecuteScalar();

//                    $cmd->CommandText = $max;

//                    $quantsData->max = $DA->execute_scalar($cnn, $max);
                    $maxResult = DataAdapter::DefaultPDOExecuteAndRead($max, null, "Survey_Data");
                    if($maxResult->hasRows()) {
                        $quantsData->max = $maxResult->resultObject[0]->max;
                    }
//                    $quantsData->max = $cmd->ExecuteScalar();

//                       $cnn->Close();

                    $minMax[0] = intval($quantsData->min);
                    $minMax[1] = intval($quantsData->max);

                    $tableMinMax[$tableName] = $minMax;

                } Else {


                    $quantsData->min = $tableMinMax[$tableName][0];
                    $quantsData->max = $tableMinMax[$tableName][1];
                }

                $quantsData->gName = $datarow->area_name;

                If ( array_key_exists($tableName, $SS->quantData)) {
                    $SS->quantData[$tableName]->gName = $SS->quantData[$tableName]->gName . "; " . $quantsData->gName;

                } Else {

                    $SS->quantData[$tableName] = $quantsData;
                    $SS->quantCount = intval($SS->quantCount) + 1;
                }


            }


        }
        // ' Qual(Data);

//        $qcnn = $DB->getDBConnection("Qual_Data");
//
//        $QselStr = ("SELECT * FROM qualdata.dc_info WHERE ST_Intersects(ST_Transform(ST_GeometryFromText('" .
//        $geography . "', 27700), 4326), qualdata.dc_info.the_geom);");

//        Log::toFile('Intersects query : ' . $QselStr);

//        $QDataAdapter = pg_query($qcnn, $QselStr);
//        $QresultsTable = New DataTable();
//        $QDataAdapter->Fill($QresultsTable);

//        $res = $DA->Read($QDataAdapter);

        $QselStr = "SELECT * FROM qualdata.dc_info
                WHERE ST_Intersects(ST_Transform(ST_GeometryFromText(:geography, 27700), 4326),
                 qualdata.dc_info.the_geom);";

        $values = array(":geography" => $geography);

        $res = DataAdapter::DefaultPDOExecuteAndRead($QselStr, $values, "Qual_Data");


        ForEach ( $res->resultObject as $Qdatarow ) {

            $coverage = $Qdatarow->coverage;

            $items = explode(";", $coverage);

            $locDetails = "";
            $word_stats = "";

            ForEach ($items as $place ) //items;

            If ( !$place == "") {
//                Log::toFile("each qual place " . $place . " " . date("Y-m-d H:i:s"));

//                    $wordStatArray = explode("wordStats", $place);
//
//                    $locDetails = $wordStatArray[0];
//                    $word_stats = $wordStatArray[1];
//
//                    $word_stats = substr($word_stats, strpos($word_stats, "["));
//
//                    $word_stats = substr($word_stats, -3);
//
////                    $word_stats = $word_stats->Remove(0,$word_stats->IndexOf("["));
////                    $word_stats = $word_stats->Remove(($word_stats->Length - 3), 3);
//
//                    $locDetails .= "wordsStats" . ":" . $word_stats . "}";

                    $pattern = "/\"{name:(.*), data/";
                    $replacement = '{"name":"$1", "data"';
                    $subject = $place;

                    $result = preg_replace($pattern, $replacement, $subject);

                    $locDetails = substr($result, 0, -2) . "}";


                    $places = json_decode($locDetails);

//                    Log::toFile('qual places : ' . print_r($places, true));

                    If ( !$places == null ) {

                        $qualData = New qualDataRecordGroup();
                        $qualData->sName = Trim($Qdatarow->identifier);

                        $coord = New qualCoords();
                        $coord->lat = $places->lat;
                        $coord->lon = $places->lon;
                        $coord->name = $places->Name;
                        $coord->counts = $places->Occurences;
                        $qualData->gName[] = ($coord);
                        $qualData->name = Trim($Qdatarow->title);
                        $qualData->creator = Trim($Qdatarow->creator);
                        $qualData->thematic = Trim($Qdatarow->thematic_group);
                        $qualData->recorddate = Trim($Qdatarow->created);

                        If ( array_key_exists($Qdatarow->identifier, $SS->qualData)) {
                            $SS->qualData[$Qdatarow->identifier]->gName = ($coord);
                        } Else {
                            $SS->qualData[$Qdatarow->identifier] = $qualData;
                            $SS->qualCount = intval($SS->qualCount) + 1;
                        }
                    }

                }

        }

//        Log::toFile("Quant : Qual : " . $SS->quantCount . " : " . $SS->qualCount);

        $results[] = ($SS);

        Return $results;


    }




    Private Function getSurveyNameYear($tableName){

        $details = array();

//        $db = New getDBConnections();
//        $cnn = $db->getDBConnection("Survey_Data");
//
//
//        $selSurveyStr = "Select surveyid from survey_spatial_link where lower(spatial_id) = '" . Trim(strtolower($tableName)) . "'";
////            $DR1 As$Npgsql->NpgsqlDataReader;
//
//        $cmd1 = pg_query($cnn, $selSurveyStr);
//
//        //$cnn->Open();
//
//        $DA = new DataAdapter();
//        $DR = $DA->Read($cmd1);
//        $DT = $DR[0];

        $selSurveyStr = "Select surveyid from survey_spatial_link where lower(spatial_id) = :tablename";
        $values = array(":tablename" => Trim(strtolower($tableName)));
        $results = DataAdapter::DefaultPDOExecuteAndRead($selSurveyStr, $values, "Survey_Data");

        $SID = "";

        if ($results->hasRows()) {
            $SID = $results->resultObject[0]->surveyid;
        }

//        If ( $DT ) {
//            $SID = $DT->surveyid;
//        }

//        $cnn->Close();


        $selstr = "Select short_title, collectionenddate from survey WHERE lower(surveyid) = :SID";
        $values = array(":SID" => Trim(strtolower($SID)));
        $results = DataAdapter::DefaultPDOExecuteAndRead($selstr, $values, "Survey_Data");

//        $cmd = pg_query($cnn, $selstr);

        //$cnn->Open();

//        $DA = new DataAdapter();
//        $DR = $DA->Read($cmd);

//        $DT = $DR[0];
//        If ( $DT ) {

        if ($results->hasRows()) {
            $DT = $results->resultObject[0];
//            Log::toFile('surveyYear' . print_r($DT, true));

            $sName = $DT->short_title;

            $survey_date = DateTime::createFromFormat("Y-m-d", $DT->collectionenddate);

            $year = date_format($survey_date, "Y");
            $details["surveyName"] = $sName;
            $details["year"]= $year;
//               $cnn->Close();

        }

        Return $details;



    }


    Public Function generateQualSpatialData($colour, $ID) {

        $results = array();
//
//        $db = New getDBConnections();
//
//        $selStr = "Select coverage from qualdata.dc_info WHERE identifier = '" . $ID . "'";
//
//        $cnn = $db->getDBConnection("Qual_Data");
//
//        $coverage = "";
//
//
//
//        $cmd = pg_query($cnn, $selStr);
//        $DA = new DataAdapter();
//
//        $resultRows = $DA->Read($cmd);

        $selStr = "Select coverage from qualdata.dc_info WHERE identifier = :ID";
        $values = array(":ID" => $ID);
        $resultRows = DataAdapter::DefaultPDOExecuteAndRead($selStr, $values, "Qual_Data");

        $coverage = Trim($resultRows->resultObject[0]->coverage);

//        $cnn->Close();

        $items = explode(";", $coverage);

        $locDetails = "";
        $word_stats = "";

        ForEach ($items as $place ) {//items;
            If ( !$place == "") {

                $pattern = "/\"{name:(.*), data/";
                $replacement = '{"name":"$1", "data"';
                $subject = $place;

                $result = preg_replace($pattern, $replacement, $subject);

                $result = substr($result, 0, -2) . "}";

                $places = json_decode($result);

//                $wordStatArray = explode("wordStats", $place);
//
//                $locDetails = $wordStatArray[0];
//                $word_stats = $wordStatArray[1];
//
//                $word_stats = substr($word_stats, strpos($word_stats, "["));
//
//                $word_stats = substr($word_stats, -3);
//
//                $locDetails .= "wordsStats" . ":" . $word_stats . "}";
//
//                $places = json_decode($locDetails);

                If ( !$places == null ) {

                    $qualData = New qualDataRecord();
                    $qualData->identifier = $ID;
                    $qualData->lat = $places->lat;
                    $qualData->lon = $places->lon;
                    $qualData->title = $places->Name;
                    $qualData->counts = $places->Occurences;


                    $results[] = ($qualData);
                }

            }

        }

        Return $results;

    }

    Public Function getChosenLayers($layers) {

        $DB = New getDBConnections();
        $cnn = $DB->getDBConnection("Survey_Data");

//        TODO only allow trusted table names in query
//        $untrustedNames = array();
//
//        foreach($layers as $layer) {
//            $untrustedNames[] = $layer->Value->id;
//        }
//
//        $allowedTablesNames = DataAdapter::areTrustedTableNames($untrustedNames, "Survey_Data");

        ForEach ($layers as $layer) {//layers;

            $geographies = explode(";", $layer->Value->geographies);

            $selStr = ("SELECT area_name, ST_AsEWKT(st_simplifypreservetopology(the_geom,$0->000225)) as the_geom from " . $layer->Value->id);
            $selStr .= (" WHERE ");

            ForEach ($geographies as $geog ) { //geographies;

                $selStr .= ("area_name = '" . $geog . "' OR ");

            }


            $selStr = substr(0, -3);

            $resultObject = pg_query($cnn, $selStr);
            $DataAdapter = New DataAdapter();
            $resultRows = $DataAdapter->Read($resultObject);

            ForEach ( $resultRows as $row ) {
                $the_geom = New geom();

                $the_geom->geom = $row->the_geom;
                $layer->Value->geometry[$row->area_name] = $the_geom;


            }

        }

        Return $layers;

    }


    Public Function getFeatureInfoTable($lat, $lon, $tables) {

        $htmlOutputs = array();

        ForEach ( $tables as $Table ) {


            $selStr = "SELECT area_name, total, successful, refused, no_contact, ineligible, other from " .$Table->tableID;
            $selStr .= " WHERE ST_Within(st_transform(ST_GeomFromText('POINT(" . $lon . " " . $lat . ")',27700), 4326), " .$Table->tableID . "->the_geom);";


            $db = New getDBConnections();
            $cnn = $db->getDBConnection("Survey_Data");


            $results = New DataTable;

            $DA = pg_query($cnn, $selStr);

            $DA->Fill($results);

            $htmlOut = $this::ConvertToHtmlFile($results);

            $identResults = New IdentifyResults;
            $identResults->tableName = $Table->tableName;
            $identResults->Html = $htmlOut;

            $htmlOutputs[] = ($identResults);
        }

        Return $htmlOutputs;

    }


// ''' <summary>;
// ''' This is a simple way to convert a DataTable to an HTML$file->
// ''' </summary>;
// ''' <param name= "targetTable">This the table to$convert-></param>;
// ''' <returns>This is the HTML output, which can saved as a$file-></returns>;
    Public Function ConvertToHtmlFile($targetTable) {
        $myHtmlFile = "";



        If ( $targetTable == null ) {
            Throw New ArgumentNullException("targetTable");
            // 'Continue->
        } Else {
        }



        // 'Get a worker$object->
        $myBuilder = "";



        // 'Open tags and write the top$portion->
        // 'myBuilder .= ("<html xmlns= 'http://www->w3->org/1999/xhtml'>");
        // 'myBuilder .= ("<head>");
        // 'myBuilder .= ("<title>");
        // 'myBuilder .= ("Page-");
        // 'myBuilder .= ($Guid->NewGuid()());
        // 'myBuilder .= ("</title>");
        // 'myBuilder .= ("</head>");
        // 'myBuilder .= ("<body>");
        $myBuilder .= ("<table border= '1px' cellpadding= '5' cellspacing= '0' ");
        $myBuilder .= ("style= 'border: solid 1px Silver; font-size: x-small;'>");



        // 'Add the headings$row->



        $myBuilder .= ("<tr align= 'left' valign= 'top'>");



        ForEach ( $targetTable->Columns as $myColumn ) {
            $myBuilder .= ("<td align= 'left' valign= 'top'>");
            $myBuilder .= ($myColumn->ColumnName);
            $myBuilder .= ("</td>");
        }



        $myBuilder .= ("</tr>");



        // 'Add the data$rows->
        ForEach ( $targetTable->Rows as $myRow ) {
            $myBuilder .= ("<tr align= 'left' valign= 'top'>");



            ForEach ( $targetTable->Columns as $myColumn ) {
                $myBuilder .= ("<td align= 'left' valign= 'top'>");
                $myBuilder .= ($myRow[$myColumn->ColumnName]);
                $myBuilder .= ("</td>");
            }



            $myBuilder .= ("</tr>");
        }



        // 'Close$tags->
        $myBuilder .= ("</table>");
        // 'myBuilder .= ("</body>");
        // 'myBuilder .= ("</html>");



        // 'Get the string for$return->
        Return $myBuilder;


    }




}

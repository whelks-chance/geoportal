<?
class SpatialData {

    Public Function getAvailableUnits($SurveyID) {

        $dc = New getDBConnections();

        $cnn = $dc->getDBConnection("Survey_Data");

        $selectStr = "Select * from survey_spatial_link where surveyid = '" . strtolower($SurveyID) . "';";

        $results = array();

        $cmd = pg_query($cnn, $selectStr);

        //$cnn->Open();

        $DA = new DataAdapter();

        $resultObjects = $DA->Read($cmd);

        ForEach ($resultObjects as $DR ) {
            $units = New AvailableSpatialUnits();
            $units->spatial_id = Trim($DR->spatial_id);
            $units->long_start = Trim($DR->long_start);
            $units->long_finish = Trim($DR->long_finish);
            $units->Name = $this::getFullName($DR->spatial_id);
            $units->short_name = strtolower(explode("_", $DR->spatial)[3]);

            $results[] = ($units);
        }


        Return $results;

    }


    Public Function getSpatialLabels($TableName) {

        $dc = New getDBConnections();

        $cnn = $dc->getDBConnection("Survey_Data");

        $selectStr = "Select column_name as name from information_schema.columns where table_name = '" . $TableName . "';";


        $results = array();

        $cmd = pg_query($cnn, $selectStr);

        //$cnn->Open();

        $DR = $cmd->ExecuteReader;


        Do While$DR->Read;
            $label = New SpatialLabels();
        $label->Name = Trim($DR->Name);
        $results[] = ($label);
        Loop;

        $cnn->Close();

        Return $results;

    }

    Public Function getSpatialSubUnits($TableName) {

        $dc = New getDBConnections();

        $cnn = $dc->getDBConnection("Survey_Data");

        $selectStr = "Select * from " . $TableName . ";";


        $results = array();

        $cmd = pg_query($cnn, $selectStr);

        //$cnn->Open();

        $DR = $cmd->ExecuteReader;

        $spatialSUAll = New SpatialSubUnits();
        $spatialSUAll->Name = "All";
        $results[] = ($spatialSUAll);

        Do While$DR->Read;
            $spatialSU = New SpatialSubUnits();
        $spatialSU->Name = Trim($DR->Item[1]);
        $results[] = (spatialSU);
        Loop;

        $cnn->Close();

        Return $results;

    }


    Public Function getRefSpatialSubUnits($TableName) {

        $dc = New getDBConnections();

        $cnn = $dc->getDBConnection("Survey_Data");

        $selectStr = "Select * from " . $TableName . ";";


        $results = array();

        $cmd = pg_query($cnn, $selectStr);

        //$cnn->Open();

        $DR = $cmd->ExecuteReader;


        $spatialSUMap = New SpatialSubUnits();
        $spatialSUMap->Name = "Current Map Extent";
        $results[] = (spatialSUMap);


        Do While$DR->Read;
            $spatialSU = New SpatialSubUnits();
        $spatialSU->Name = Trim($DR->Item[1]);
        $results[] = (spatialSU);
        Loop;

        $cnn->Close();

        Return $results;

    }

    Public Function getRefSpatialIndivudalUnits($UnitName, $MajorUnit, $SubUnit, $the_geom, $SID) {

        $dc = New getDBConnections();

        $cnn = $dc->getDBConnection("Survey_Data");
        $big_geom;
        $suffix = "";

        If ( $the_geom = "N/A" ) {

            $suffix = getTableName(SID, MajorUnit);

            $big_geom = "(SELECT the_geom from spatialdata." . $suffix . " WHERE area_name = '" . $SubUnit . "')";
        } Else {
            $big_geom = the_geom;

        }


        $selectStr = "SELECT * FROM public." . $UnitName . " WHERE ST_Intersects('" . $big_geom . "', the_geom);";


        $results = array();

        $cmd = pg_query($cnn, $selectStr);

        //$cnn->Open();

        $DR = $cmd->ExecuteReader;

        $spatialSUAll = New SpatialSubUnits();
        $spatialSUAll->Name = "All";
        $results[] = (spatialSUAll);

        Do While$DR->Read;
            $spatialSU = New SpatialSubUnits();
        $spatialSU->Name = Trim($DR->Item[1]);
        $results[] = (spatialSU);
        Loop;

        $cnn->Close();

        Return $results;

    }


    Public Function getChoroFields($TableName) {

        $dc = New getDBConnections();

        $cnn = $dc->getDBConnection("Survey_Data");

        $selectStr = "Select column_name as name from$information_schema->columns where table_name = '" . $TableName . "';";


        $results = array();

        $cmd = pg_query($cnn, $selectStr);

        //$cnn->Open();

        $DR = $cmd->ExecuteReader;

        // 'Advance reader through the first two records as not applicable for choropleth mapping;
        $DR->Read();
        $DR->Read();


        Do While$DR->Read;

            $label = New SpatialLabels;
               $label->Name = Trim($DR->Name));
                $results[] = (label);
            Loop;

           $cnn->Close();

            Return $results;

        }


    Public Function getFullName($spatial_id) {
        $Name = "";

        $postFix = explode("_", $spatial_id)[3]->ToLower;

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

        $db = New getDBConnections();

        $cnn = $db->getDBConnection("Survey_Data");

//            $DT = New DataTable;

        $Link_ID = "";

        $Link_ID = $this::getTableName($SurveyID, $Unit);


        If ( $SubUnit = "All" ) {
            $queryStr = "SELECT area_name, total, successful, refused, no_contact, ineligible, other, response_rate, adjusted_rr, ST_AsEWKT(st_simplifypreservetopology(the_geom,$0->001)) as the_geom FROM " . $Link_ID->ToLower . ";";

        } Else {
            $queryStr = "SELECT area_name, total, successful, refused, no_contact, ineligible, other, response_rate, adjusted_rr, ST_AsEWKT(st_simplifypreservetopology(the_geom,$0->001)) as the_geom FROM " . $Link_ID->ToLower . " WHERE area_name= '" . $SubUnit . "';";

        }

        $resultRows = pg_query($cnn, $queryStr);


        $cnt = sizeof($DT->Rows);


        If ( $cnt > 0 ) {
            // 'calculate choropleth stats;

            // 'If Interval >$DT->Rows->Count ) {
            // '   $Interval = $DT->Rows->Count;
            // '}


            ForEach ( $DT->Rows as $row ) {
                $total = $row->total;// 'DT->Compute("Sum(" . $ChoroplethField . ")", Nothing);

                $colorList = $this::generateColourRange($fromColour, $toColour, $this::generateEqualInterval($total, $Interval), $Interval);

                   If ( $IsDBNull($row->the_geom)) ) {

                } Else {
                    $SU = New ResponseSpatialUnits();
                    $SU->TotalResp = $row->total;
                    $SU->ChoroField = $ChoroplethField;
                    $SU->ChoroValue = $row->$ChoroplethField;
                    $SU->LabelField = $row->$Label;
                    $SU->Name = $row->area_name;
                    $SU->WKT = $row->the_geom->Split(";")[1];
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

        $intervalRange = $Math->Round($Total / $intervals,$System->MidpointRounding->AwayFromZero);



        Return $intervalRange;
    }


    Public Function generateColourRange($fromColour, $ToColour, $intervalRange , $intervalCount) {


        $classInterval = $intervalRange;

            $startColor = ColorTranslator->FromHtml($fromColour);
            $endColor = ColorTranslator->FromHtml($ToColour);

            $colourList = array();
            $i = 0;;

            $rMax = $endColor->R;
            $rMin = $startColor->R;
            $gMax = $endColor->G;
            $gMin = $startColor->G;
            $bMax = $endColor->B;
            $bMin = $startColor->B;


            While ($i < $intervalCount) {

                $rAverage = $rMin = $CInt(($rMax - $rMin) * $i / $intervalCount);
                $gAverage = $gMin = $CInt(($gMax - $gMin) * $i / $intervalCount);
                $bAverage = $bMin = $CInt(($bMax - $bMin) * $i / $intervalCount);

                $colourList[] = (classInterval,$Color->FromArgb($rAverage, $gAverage, $bAverage));
               $classInterval = $(classInterval = $intervalRange)$ = $1;
                $i += 1;
            }



            Return $colourList;


        }

    Public Function getColour($Colours, $value ) {

        ForEach ( $Colours as $item ) {//Colours;
            If ( $value->CompareTo($item->Key) < 0 ) {
                    Return ColorTranslator->ToHtml($item->Value);
                }

        }
    }


    Public Function VerifySpatialSearch($coords, $type, $dist ){


        $getTables = "SELECT * FROM geometry_columns where f_table_schema = 'public';";

        $DB = New getDBConnections();
        $cnn = $DB->getDBConnection("Survey_Data");

        $DA = pg_query($cnn, $getTables);

        $DT = New DataTable;

        $DA->Fill(DT);


        ForEach ( $DT->Rows as $row ) {

            $tableName = $row->f_table_name;
            $geom_col = $row->f_geometry_column;

            $selStr = "";
            $selStr .= ("SELECT * from  ");
            $selStr .= ($tableName . " ");
            $selStr .= ("WHERE ST_DWithin(ST_Transform(ST_SetSRID(ST_MakePoint(");
            $selStr .= ($coords);
            $selStr .= ("), 4326), 4326) ,ST_Transform(");
            $selStr .= ($tableName . "->" . $geom_col . ",");
            $selStr .= (" 4326)," . $dist . ") LIMIT 1;");


            $cmd = pg_query($cnn, $selStr);

            //$cnn->Open();
            $DR = $cmd->ExecuteReader();

            If ( $DR->Read ) {
                $cnn->Close();
                Return True;
            }

//               $cnn->Close();



        }


        Return False;



    }


    Public Function SpatialSearch($geography) {

        $results = array();
        $getTables = "SELECT * FROM geometry_columns where f_table_schema = 'public';";

        $DB = New getDBConnections();
        $cnn = $DB->getDBConnection("Survey_Data");

        $DA = pg_query($cnn, $getTables);

        $DT = New DataTable;

        $DA->Fill(DT);
        $SS = New SpatialSearch2;

        $tableMinMax = array();

        ForEach ( $DT->Rows as $row ) {
            $selStr = "";
                $tableName = $row->f_table_name);
                $geom_col = $row->f_geometry_column);


               $selStr .= ("SELECT area_name from " . $tableName);
               $selStr .= (" WHERE ST_Intersects(ST_Transform(ST_GeometryFromText('" . $geography . "', 27700), 4326)," . $geom_col . ");");


                $DataAdapter = pg_query($cnn, $selStr);
                $resultsTable = New DataTable();
               $DataAdapter->Fill($resultsTable);

                $surveyDetails = getSurveyNameYear($tableName);


                ForEach ( $resultsTable->Rows as $datarow ) {
                    $quantsData = New quantDataRecord2();

                    If ( sizeof($surveyDetails) = 0 ) {
                        $quantsData->sName = $tableName;
                        $quantsData->sYear = 9999;
                    } Else {
                        $quantsData->sName = $surveyDetails->surveyName);
                        $quantsData->sYear = $surveyDetails->year);
                    }


                    // 'Convert text column to integer value and get min and max values;
                    $min = "Select min(cast(successful as int)) from " . $tableName;
                    $max = "Select max(cast(successful as int)) from " . $tableName;


                    $quantsData->geography = $tableName->Split("_")(3)->ToUpper;
                    $quantsData->tName = $tableName;
                    $quantsData->sID = $tableName->Split("_")[1] . "_" . $tableName->Split("_")[2];

                   If ( !$tableMinMax->ContainsKey($tableName) ) {
                       $cmd = pg_query($cnn, $min);
                       If ( $cnn->State = $ConnectionState->Closed ) {
                           //$cnn->Open();

                       }
                       $minMax[1] ;

                       $quantsData->min = $cmd->ExecuteScalar();

                       $cmd->CommandText = $max;

                       $quantsData->max = $cmd->ExecuteScalar();

//                       $cnn->Close();

                       $minMax[0] = $quantsData->min;
                       $minMax[1] = $quantsData->max;

                        $tableMinMax[] = ($tableName, $minMax);

                  } Else {


                       $quantsData->min = $tableMinMax->Item($tableName)[0];
                       $quantsData->max = $tableMinMax->Item($tableName)[1];
                   }



                    $quantsData->gName = $datarow->area_name;

                   If ( $SS->quantData->ContainsKey($tableName) ) {
                       $SS->quantData($tableName)->gName .= "; " . $quantsData->gName;

                   } Else {

                       $SS->quantData[$tableName] = $quantsData;
                       $SS->quantCount .= 1;                    }


                }


            }
        // ' Qual(Data);

        $qcnn = $DB->getDBConnection("Qual_Data");

        $QselStr = "";


        $QselStr .= ("SELECT * FROM qualdata.dc_info ");
        $QselStr .= (" WHERE ST_Intersects(ST_Transform(ST_GeometryFromText('" . $geography . "', 27700), 4326),$qualdata->dc_info->the_geom);");


        $QDataAdapter = pg_query($qcnn, $QselStr);
        $QresultsTable = New DataTable;
        $QDataAdapter->Fill($QresultsTable);


        ForEach ( $QresultsTable->Rows as $Qdatarow ) {

            $coverage = $Qdatarow->coverage;

            $items = $coverage->Split(";");

            $locDetails = "";
            $word_stats = "";

            ForEach ($items as $place ) //items;
                If ( !$place == "") {


                    $locDetails = $Regex->Split(place, "wordStats")[0];
                    $word_stats = $Regex->Split(place, "wordStats")[1];

                    $word_stats = $word_stats->Remove(0,$word_stats->IndexOf("["));

                    $word_stats = $word_stats->Remove(($word_stats->Length - 3), 3);

                    $locDetails .= "wordsStats" . ":" . $word_stats . "}";

                        $places = json_decode(Of unlockDetails)(locDetails);

                       If ( !$places = null ) {

                           $qualData = New qualDataRecordGroup;
                           $qualData->sName = Trim($Qdatarow->identifier));

                            $coord = New qualCoords;
                           $coord->lat = $places->lat;
                           $coord->lon = $places->lon;
                           $coord->name = $places->Name;
                           $coord->counts = $places->Occurences;
                           $qualData->gName[] = (coord);
                           $qualData->name = Trim($Qdatarow->title);
                           $qualData->creator = Trim($Qdatarow->creator);
                           $qualData->thematic = Trim($Qdatarow->thematic_group);
                           $qualData->recorddate = Trim($Qdatarow->created);

                           If ( $SS->qualData->ContainsKey($Qdatarow->identifier) ) {
                               $SS->qualData->$Qdatarow->identifier->gName[] = ($coord);
                           } Else {
                               $SS->qualData[$Qdatarow->identifier] = $qualData;
                               $SS->qualCount += 1;
                           }
                        }

                    }

        }


        $results[] = ($SS);

        Return $results;


    }




    Private Function getSurveyNameYear($tableName){

        $details = array();

        $db = New getDBConnections();
        $cnn = $db->getDBConnection("Survey_Data");


        $selSurveyStr = "Select surveyid from survey_spatial_link where lower(spatial_id) = '" . Trim($tableName->ToLower) . "'";
//            $DR1 As$Npgsql->NpgsqlDataReader;

        $cmd1 = pg_query($cnn, $selSurveyStr);

        //$cnn->Open();


        $DR1 = $cmd1->ExecuteReader;



        $SID = "";
        If ( $DR1->Read ) {
               $SID = $DR1->surveyid);
            }

        $cnn->Close();




        $selstr = "Select short_title, collectionenddate from survey WHERE lower(surveyid) = '" . Trim($SID->ToLower) . "'";
            $DR As$Npgsql->NpgsqlDataReader;

            $cmd = pg_query($cnn, $selstr);

            //$cnn->Open();

           $DR = $cmd->ExecuteReader;

           If ( $DR->Read ) {
               $sName = $DR->short_title;

               $survey_date = $DR->collectionenddate;

               $year = $survey_date->Year;
                $details[] = ("surveyName", $sName);
                $details[] = ("year", $year);
//               $cnn->Close();

            }

            Return $details;



        }


    Public Function generateQualSpatialData($colour, $ID) {

        $results = array();

        $db = New getDBConnections();

        $selStr = "Select coverage from qualdata.dc_info WHERE identifier = '" . $ID . "'";

        $cnn = $db->getDBConnection("Qual_Data");

        $coverage = "";



        $cmd = pg_query($cnn, $selStr);
        //$cnn->Open();

        $DR = $cmd->ExecuteReader;

        If ( $DR->Read ) {
               $coverage = $DR->coverage);

            }

        $cnn->Close();

        $items() = $coverage->Split(";");

        $locDetails = "";
        $word_stats = "";

        ForEach ($items as $place ) {//items;
            If ( !$place == "") {


                $locDetails = $Regex->Split($place, "wordStats")[0];
                $word_stats = $Regex->Split($place, "wordStats")[1];

                $word_stats = $word_stats->Remove(0,$word_stats->IndexOf("["));

                $word_stats = $word_stats->Remove(($word_stats->Length - 3), 3);

                $locDetails .= "wordsStats" . ":" . $word_stats . "}";

                    $places = json_decode(Of unlockDetails)(locDetails);

                   If ( !$places = null ) {

                       $qualData = New qualDataRecord;
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

        ForEach ($layers as $layer) {//layers;

            $geographies = explode(";", $layer->Value->geographies);

            $selStr = "";
            $selStr .= ("SELECT area_name, ST_AsEWKT(st_simplifypreservetopology(the_geom,$0->000225)) as the_geom from " . $layer->Value->id);
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

            $htmlOut = ConvertToHtmlFile($results);

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
                   $myBuilder .= ($myRow($myColumn->ColumnName)());
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

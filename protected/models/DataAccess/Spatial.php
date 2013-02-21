<?
    class SpatialData {

        Public Function getAvailableUnits($SurveyID) {

            $dc = New getDBConnections();

            $cnn = $dc->getDBConnection("Survey_Data");

            $selectStr = "Select * from survey_spatial_link where surveyid = '" . $SurveyID->ToLower . "';";

            $results = array();

            $cmd = pg_query($cnn, $selectStr);

            // cnn->Open();

            $DR As Npgsql->NpgsqlDataReader = cmd->ExecuteReader


            Do While DR->Read
                $units = New AvailableSpatialUnits();
                $units->spatial_id = Trim($DR ->spatial_id);
                $units->long_start = Trim($DR ->long_start);
                $units->long_finish = Trim($DR ->long_finish);
                $units->Name = $this::getFullName($DR ->spatial_id);
                $units->short_name = explode("_", $DR->spatial)[3]->ToLower;

                $results[] = ($units);
            Loop


            Return results;

        }


        Public Function getSpatialLabels($TableName) {

            $dc = New getDBConnections();

            $cnn = $dc->getDBConnection("Survey_Data");

            $selectStr = "Select column_name as name from information_schema->columns where table_name ='" . TableName . "';";


            $results = array();

            $cmd = pg_query($cnn, $selectStr);

            // cnn->Open();

            $DR As Npgsql->NpgsqlDataReader = cmd->ExecuteReader


            Do While DR->Read
                $label = New SpatialLabels()
                label->Name = Trim($DR ->Name);
                results[] = (label);
            Loop

            cnn->Close();

            Return results

        }

        Public Function getSpatialSubUnits($TableName) {

            $dc = New getDBConnections();

            $cnn = $dc->getDBConnection("Survey_Data");

            $selectStr = "Select * from " . TableName . ";";


            $results = array();

            $cmd = pg_query($cnn, $selectStr);

            // cnn->Open();

            $DR As Npgsql->NpgsqlDataReader = cmd->ExecuteReader

            $spatialSUAll = New SpatialSubUnits();
            spatialSUAll->Name = "All";
            results[] = ($spatialSUAll);

            Do While DR->Read
                $spatialSU = New SpatialSubUnits();
                spatialSU->Name = Trim($DR->Item[1]);
                results[] = (spatialSU);
            Loop

            cnn->Close();

            Return results

        }


        Public Function getRefSpatialSubUnits($TableName) {

            $dc = New getDBConnections();

            $cnn = $dc->getDBConnection("Survey_Data");

            $selectStr = "Select * from " . TableName . ";";


            $results = array();

            $cmd = pg_query($cnn, $selectStr);

            // cnn->Open();

            $DR As Npgsql->NpgsqlDataReader = cmd->ExecuteReader


            $spatialSUMap = New SpatialSubUnits();
            spatialSUMap->Name = "Current Map Extent";
            results[] = (spatialSUMap);


            Do While DR->Read
                $spatialSU = New SpatialSubUnits();
                spatialSU->Name = Trim($DR->Item[1]);
                results[] = (spatialSU);
            Loop

            cnn->Close();

            Return results

        }

        Public Function getRefSpatialIndivudalUnits($UnitName, $MajorUnit, $SubUnit, $the_geom, $SID) {

            $dc = New getDBConnections();

            $cnn = $dc->getDBConnection("Survey_Data");
            $big_geom
            $suffix = "";

           If ( $the_geom = "N/A" ) {

                suffix = getTableName(SID, MajorUnit);

                big_geom = "(SELECT the_geom from spatialdata->" . suffix . " WHERE area_name ='" . $S ubUnit . "')";
          } Else {
                big_geom = the_geom

            }


            $selectStr = "SELECT * FROM public->" . UnitName . " WHERE ST_Intersects('" . big_geom . "', the_geom);";


            $results = array();

            $cmd = pg_query($cnn, $selectStr);

            // cnn->Open();

            $DR As Npgsql->NpgsqlDataReader = cmd->ExecuteReader

            $spatialSUAll = New SpatialSubUnits();
            spatialSUAll->Name = "All";
            results[] = (spatialSUAll);

            Do While DR->Read
                $spatialSU = New SpatialSubUnits();
                spatialSU->Name = Trim($DR->Item[1]);
                results[] = (spatialSU);
            Loop

            cnn->Close();

            Return results

        }


        Public Function getChoroFields($TableName) {

            $dc = New getDBConnections();

            $cnn = $dc->getDBConnection("Survey_Data");

            $selectStr = "Select column_name as name from information_schema->columns where table_name ='" . TableName . "';";


            $results = array();

            $cmd = pg_query($cnn, $selectStr);

            // cnn->Open();

            $DR As Npgsql->NpgsqlDataReader = cmd->ExecuteReader

          // 'Advance reader through the first two records as not applicable for choropleth mapping
            DR->Read();
            DR->Read();


            Do While DR->Read

                $label = New SpatialLabels
                label->Name = Trim($DR ->Name));
                results[] = (label);
            Loop

            cnn->Close();

            Return results;

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

//            $DT = New DataTable

            $Link_ID = "";

            $Link_ID = $this::getTableName($SurveyID, $Unit);


           If ( $SubUnit = "All" ) {
                $queryStr = "SELECT area_name, total, successful, refused, no_contact, ineligible, other, response_rate, adjusted_rr, ST_AsEWKT(st_simplifypreservetopology(the_geom, 0->001)) as the_geom FROM " . $Link_ID->ToLower . ";";

           } Else {
                $queryStr = "SELECT area_name, total, successful, refused, no_contact, ineligible, other, response_rate, adjusted_rr, ST_AsEWKT(st_simplifypreservetopology(the_geom, 0->001)) as the_geom FROM " . $Link_ID->ToLower . " WHERE area_name='" . $SubUnit . "';";

            }

           $resultRows = pg_query($cnn, $queryStr);


            $cnt As Integer = DT->Rows->Count();


           If ( $cnt > 0 ) {
              // 'calculate choropleth stats

              // 'If Interval > DT->Rows->Count ) {
              // '    Interval = DT->Rows->Count
              // '}


                ForEach ( row As DataRow ) //DT->Rows
                    $total As Integer = row ->total)// 'DT->Compute("Sum(" . ChoroplethField . ")", Nothing);

                    $colorList As Dictionary(Of Integer, Color) = generateColourRange(fromColour, toColour, generateEqualInterval(total, Interval), Interval);

                   If ( $IsDBNull($row ->the_geom)) ) {

                  } Else {
                        $SU = New ResponseSpatialUnits();
                        $SU->TotalResp = $row ->total);
                        $SU->ChoroField = $ChoroplethField
                        $SU->ChoroValue = $row->Item($ChoroplethField);
                        $SU->LabelField = $row->Item($Label);
                        $SU->Name = $row ->area_name);
                        $SU->WKT = $row ->the_geom)->Split(";")[1]
                        $SU->Colour = getColour($colorList, $row->Item($ChoroplethField));

                        $results[] = ($SU);
                    }
                }
            }

            Return results;

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

            $intervalRange = Math->Round(Total / intervals, System->MidpointRounding->AwayFromZero);



            Return intervalRange;
        }


        Public Function generateColourRange($fromColour, $ToColour, $intervalRange As Integer, $intervalCount As Double) As Dictionary(Of Integer, Color);


            $classInterval As Integer = intervalRange

            $startColor As Color = ColorTranslator->FromHtml(fromColour);
            $endColor As Color = ColorTranslator->FromHtml(ToColour);

            $colourList = New Dictionary(Of Integer, Color);
            $i As Integer = 0


            $rMax As Integer = endColor->R
            $rMin As Integer = startColor->R
            $gMax As Integer = endColor->G
            $gMin As Integer = startColor->G
            $bMax As Integer = endColor->B
            $bMin As Integer = startColor->B



            Do While i < intervalCount

                $rAverage As Integer = rMin + CInt((rMax - rMin) * i / intervalCount);
                $gAverage As Integer = gMin + CInt((gMax - gMin) * i / intervalCount);
                $bAverage As Integer = bMin + CInt((bMax - bMin) * i / intervalCount);

                colourList[] = (classInterval, Color->FromArgb(rAverage, gAverage, bAverage));
                classInterval = (classInterval + intervalRange) + 1
                i += 1
            Loop



            Return colourList


        }

        Public Function getColour($Colours As Dictionary(Of Integer, Color), $value As Integer);

            ForEach ( item ) //Colours
               If ( $value->CompareTo(item->Key) < 0 ) {
                    Return ColorTranslator->ToHtml(item->Value);
                }
            }
        }


        Public Function VerifySpatialSearch($coords, $type, $dist As Integer) As Boolean


            $getTables = "SELECT * FROM geometry_columns where f_table_schema = 'public';";

            $DB = New getDBConnections();
            $cnn = $DB-getDBConnection("Survey_Data");

            $DA As Npgsql->NpgsqlDataAdapter = New Npgsql->NpgsqlDataAdapter(getTables, cnn);

            $DT = New DataTable

            DA->Fill(DT);


            ForEach ( row As DataRow ) //DT->Rows

                $$tableName = row ->f_table_name");
                $geom_col = row ->f_geometry_column");

                $selStr = New StringBuilder
                selStr->Append("SELECT * from  ");
                selStr->Append($tableName . " ");
                selStr->Append("WHERE ST_DWithin(ST_Transform(ST_SetSRID(ST_MakePoint(");
                selStr->Append(coords);
                selStr->Append("), 4326), 4326) ,ST_Transform(");
                selStr->Append($tableName . "->" . geom_col . ",");
                selStr->Append(" 4326)," . dist . ") LIMIT 1;");

                $DR As Npgsql->NpgsqlDataReader

                $cmd = New Npgsql->NpgsqlCommand(selStr, cnn);

                // cnn->Open();
                DR = cmd->ExecuteReader();

               If ( $DR->Read ) {
                    cnn->Close();
                    Return True
                }

                cnn->Close();



            }


            Return False



        }


        Public Function SpatialSearch($geography) {

            $results = array();
            $getTables = "SELECT * FROM geometry_columns where f_table_schema = 'public';";

            $DB = New getDBConnections();
            $cnn = $DB-getDBConnection("Survey_Data");

            $DA As Npgsql->NpgsqlDataAdapter = New Npgsql->NpgsqlDataAdapter(getTables, cnn);

            $DT = New DataTable

            DA->Fill(DT);
            $SS = New SpatialSearch2

            $tableMinMax = New Dictionary(Of String, Integer());

            ForEach ( row As DataRow ) //DT->Rows
                $selStr = New StringBuilder
                $$tableName = row ->f_table_name");
                $geom_col = row ->f_geometry_column");


                selStr->Append("SELECT area_name from " . $tableName);
                selStr->Append(" WHERE ST_Intersects(ST_Transform(ST_GeometryFromText('" . geography . "', 27700), 4326)," . geom_col . ");");


                $DataAdapter = New Npgsql->NpgsqlDataAdapter(selStr, cnn);
                $resultsTable = New DataTable
                DataAdapter->Fill(resultsTable);

                $surveyDetails As Dictionary(Of String, String) = getSurveyNameYear($tableName);


                ForEach ( datarow As DataRow ) //resultsTable->Rows
                    $quantsData = New quantDataRecord2

                   If ( $surveyDetails->Count = 0 ) {
                        $quantsData->sName = $tableName
                        $quantsData->sYear = 9999
                  } Else {
                        $quantsData->sName = surveyDetails ->surveyName");
                        $quantsData->sYear = surveyDetails ->year");
                    }


                  // 'Convert text column to integer value and get min and max values
                    $min = "Select min(cast(successful as int)) from " . $tableName
                    $max = "Select max(cast(successful as int)) from " . $tableName


                    $quantsData->geography = $tableName->Split("_")(3)->ToUpper
                    $quantsData->tName = $tableName
                    $quantsData->sID = $tableName->Split("_")[1] . "_" . $tableName->Split("_")[2]

                   If ( !$tableMinMax->ContainsKey($tableName) ) {
                        $cmd = New Npgsql->NpgsqlCommand(min, cnn);
                       If ( $cnn->State = ConnectionState->Closed ) {
                            // cnn->Open();

                        }
                        $minMax[1] As Integer

                      $quantsData->min = cmd->ExecuteScalar();

                        cmd->CommandText = max

                      $quantsData->max = cmd->ExecuteScalar();

                        cnn->Close();

                        minMax[0] = quantsData->min
                        minMax[1] = quantsData->max

                        tableMinMax[] = ($tableName, minMax);

                  } Else {


                      $quantsData->min = tableMinMax->Item($tableName)[0]
                      $quantsData->max = tableMinMax->Item($tableName)[1]
                    }



                    $quantsData->gName = datarow ->area_name");

                   If ( $SS->quantData->ContainsKey($tableName) ) {
                        SS->quantData($tableName)->gName += "; " . $quantsData->gName

                  } Else {

                        SS->quantData[] = ($tableName, quantsData);
                        SS->quantCount += 1
                    }


                }


            }
          // ' Qual(Data);

            $qcnn = $DB-getDBConnection("Qual_Data");

            $QselStr = New StringBuilder


            QselStr->Append("SELECT * FROM qualdata->dc_info ");
            QselStr->Append(" WHERE ST_Intersects(ST_Transform(ST_GeometryFromText('" . geography . "', 27700), 4326), qualdata->dc_info->the_geom);");


            $QDataAdapter = New Npgsql->NpgsqlDataAdapter(QselStr, qcnn);
            $QresultsTable = New DataTable
            QDataAdapter->Fill(QresultsTable);


            ForEach ( Qdatarow As DataRow ) //QresultsTable->Rows

                $coverage = Qdatarow ->coverage");

                $items() = coverage->Split(";");

                $locDetails = "";
                $word_stats = "";

                ForEach ( place ) //items
                   If ( !$place == "") {


                        locDetails = Regex->Split(place, "wordStats")[0]
                        word_stats = Regex->Split(place, "wordStats")[1]

                        word_stats = word_stats->Remove(0, word_stats->IndexOf("["));

                        word_stats = word_stats->Remove((word_stats->Length - 3), 3);

                        locDetails += "wordsStats"":" . word_stats . "}";

                        $places As unlockDetails = Newtonsoft->Json->JsonConvert->DeserializeObject(Of unlockDetails)(locDetails);

                       If ( !$places Is Nothing ) {

                            $qualData = New qualDataRecordGroup
                            qualData->sName = Trim(Qdatarow ->identifier"));

                            $coord = New qualCoords
                            coord->lat = places->lat
                            coord->lon = places->lon
                            coord->name = places->Name
                            coord->counts = places->Occurences
                            qualData->gName[] = (coord);
                            qualData->name = Trim(Qdatarow ->title"));
                            qualData->creator = Trim(Qdatarow ->creator"));
                            qualData->thematic = Trim(Qdatarow ->thematic_group"));
                            qualData->recorddate = Trim(Qdatarow ->created"));

                           If ( $SS->qualData->ContainsKey(Qdatarow ->identifier")) ) {
                                SS->qualData->Item(Qdatarow ->identifier"))->gName[] = (coord);
                          } Else {
                                SS->qualData[] = (Qdatarow ->identifier"), qualData);
                                SS->qualCount += 1
                            }
                        }

                    }

                }


            }



            results[] = (SS);

            Return results


        }



          // 'Public Function SpatialSearch($shape, $type, $dist As Integer) {

          // '$results = array();
          // '$getTables = "SELECT * FROM geometry_columns where f_table_schema = 'public';";

          // '$DB = New getDBConnections();
          // '$cnn = $DB-getDBConnection("Survey_Data");

          // '$DA As Npgsql->NpgsqlDataAdapter = New Npgsql->NpgsqlDataAdapter(getTables, cnn);

          // '$DT = New DataTable

          // 'DA->Fill(DT);

          // 'Survey Data

          // '$SS = New SpatialSearch
          // '$oneDegree As Double = 110570

          // '$approx_deg_dist As Double = (dist * 1000) / oneDegree

          // 'ForEach ( row As DataRow ) //DT->Rows

          // '    $$tableName = row ->f_table_name");
          // '    $geom_col = row ->f_geometry_column");




          // '    $selStr = New StringBuilder

          // '    selStr->Append("SELECT area_name, ST_AsEWKT(st_simplifypreservetopology(the_geom, 0->000225)) as the_geom from  ");
          // '    selStr->Append($tableName . " ");
          // '    selStr->Append("WHERE ST_Intersects(ST_Buffer(ST_SetSRID(ST_MakePoint(");
          // '    selStr->Append(coords);
          // '    selStr->Append("), 4326), 0->045 ");
          // '    selStr->Append($tableName . "->" . geom_col);
          // '    selStr->Append("," . approx_deg_dist . ")");


          // '    selStr->Append("SELECT area_name, ST_AsEWKT(st_simplifypreservetopology(the_geom, 0->000225)) as the_geom from  ");
          // '    selStr->Append($tableName . " ");
          // '    selStr->Append("WHERE ST_DWithin(ST_SetSRID(ST_MakePoint(");
          // '    selStr->Append(coords);
          // '    selStr->Append("), 4326),");
          // '    selStr->Append($tableName . "->" . geom_col);
          // '    selStr->Append("," . approx_deg_dist . ")");


          // '    ###With reprojection of data - more accurate but takes far longer to run->->->->###
          // '    selStr->Append("SELECT area_name, ST_AsEWKT(st_simplifypreservetopology(the_geom, 0->000225)) as the_geom from  ");
          // '    selStr->Append($tableName . " ");
          // '    selStr->Append("WHERE ST_DWithin(ST_Transform(ST_SetSRID(ST_MakePoint(");
          // '    selStr->Append(coords);
          // '    selStr->Append("), 4326), 27700), ST_Transform( ");
          // '    selStr->Append($tableName . "->" . geom_col);
          // '    selStr->Append(",27700)," . (dist * 1000) . ")");



          // '    $DataAdapter = New Npgsql->NpgsqlDataAdapter(selStr, cnn);
          // '    $resultsTable = New DataTable
          // '    DataAdapter->Fill(resultsTable);

          // '    $surveyDetails As Dictionary(Of String, String) = getSurveyNameYear($tableName);


          // '    ForEach ( datarow As DataRow ) //resultsTable->Rows
          // '        $quantsData = New quantDataRecord

          // '       If ( $surveyDetails->Count = 0 ) {
          // '            $quantsData->surveyName = $tableName
          // '            $quantsData->year = 9999
          // '      } Else {
          // '            $quantsData->surveyName = surveyDetails ->surveyName");
          // '            $quantsData->year = surveyDetails ->year");
          // '        }

          // '        $quantsData->unit = $tableName->Split("_")(3);

          // '        $quantsData->survey_id = $tableName->Split("_")[1] . "_" . $tableName->Split("_")[2]
          // '        $geom = New geom
          // '        geom->Name = datarow ->area_name");
          // '        geom->geom = datarow ->the_geom")->Split(";")[1]

          // '       If ( $SS->quantData->ContainsKey($tableName) ) {


          // '            SS->quantData->Item($tableName)->the_geom[] = (datarow ->area_name"), geom);

          // '      } Else {

          // '            $quantsData->the_geom[] = (datarow ->area_name"), geom);

          // '            SS->quantData[] = ($tableName, quantsData);
          // '            SS->quantCount += 1
          // '        }


          // '    }




          // '}
          // ''Qual Data

          // '$qcnn = $DB-getDBConnection("Qual_Data");

          // '$QselStr = New StringBuilder
          // 'QselStr->Append("SELECT * from ");
          // 'QselStr->Append("qualdata->dc_info ");
          // 'QselStr->Append("WHERE ST_DWithin(ST_SetSRID(ST_MakePoint(");
          // 'QselStr->Append(coords);
          // 'QselStr->Append("), 4326),");
          // 'QselStr->Append("qualdata->dc_info->the_geom");
          // 'QselStr->Append("," . approx_deg_dist . ")");


          // '$QDataAdapter = New Npgsql->NpgsqlDataAdapter(QselStr, qcnn);
          // '$QresultsTable = New DataTable
          // 'QDataAdapter->Fill(QresultsTable);


          // 'ForEach ( Qdatarow As DataRow ) //QresultsTable->Rows

          // '    $coverage = Qdatarow ->coverage");

          // '    $items() = coverage->Split(";");

          // '    $locDetails = "";
          // '    $word_stats = "";

          // '    ForEach ( place ) //items
          // '       If ( !$place == "") {


          // '            locDetails = Regex->Split(place, "wordStats")[0]
          // '            word_stats = Regex->Split(place, "wordStats")[1]

          // '            word_stats = word_stats->Remove(0, word_stats->IndexOf("["));

          // '            word_stats = word_stats->Remove((word_stats->Length - 3), 3);

          // '            locDetails += "wordsStats"":" . word_stats . "}";

          // '            $places As unlockDetails = Newtonsoft->Json->JsonConvert->DeserializeObject(Of unlockDetails)(locDetails);

          // '           If ( !$places Is Nothing ) {

          // '                $qualData = New qualDataRecordGroup
          // '                qualData->identifier = Trim(Qdatarow ->identifier"));

          // '                $coord = New qualCoords
          // '                coord->lat = places->lat
          // '                coord->lon = places->lon
          // '                coord->name = places->Name
          // '                coord->counts = places->Occurences
          // '                qualData->coords[] = (coord);
          // '                qualData->title = Trim(Qdatarow ->title"));


          // '               If ( $SS->qualData->ContainsKey(Qdatarow ->identifier")) ) {
          // '                    SS->qualData->Item(Qdatarow ->identifier"))->coords[] = (coord);
          // '              } Else {
          // '                    SS->qualData[] = (Qdatarow ->identifier"), qualData);
          // '                    SS->qualCount += 1
          // '                }
          // '            }

          // '        }

          // '    }


          // '}

          // 'results[] = (SS);

          // 'Return results


        Private Function getSurveyNameYear($$tableName) As Dictionary(Of String, String);

            $details = New Dictionary(Of String, String);

            $db = New getDBConnections();
            $cnn = $db->getDBConnection("Survey_Data");


            $selSurveyStr = "Select surveyid from survey_spatial_link where lower(spatial_id) ='" . Trim($tableName->ToLower) . "'";
            $DR1 As Npgsql->NpgsqlDataReader

            $cmd1 = New Npgsql->NpgsqlCommand(selSurveyStr, cnn);

            // cnn->Open();


            DR1 = cmd1->ExecuteReader



            $SID = "";
           If ( $DR1->Read ) {
                SID = DR1 ->surveyid");
            }

            cnn->Close();




            $selstr = "Select short_title, collectionenddate from survey WHERE lower(surveyid) ='" . Trim(SID->ToLower) . "'";
            $DR As Npgsql->NpgsqlDataReader

            $cmd = New Npgsql->NpgsqlCommand(selstr, cnn);

            // cnn->Open();

            DR = cmd->ExecuteReader

           If ( $DR->Read ) {
                $sName = DR ->short_title");

                $survey_date As Date = DR ->collectionenddate");

                $year As Integer = survey_date->Year
                details[] = ("surveyName", sName);
                details[] = ("year", year);
                cnn->Close();

            }

            Return details



        }


        Public Function generateQualSpatialData($colour, $ID) {

            $results = array();

            $db = New getDBConnections();

            $selStr = "Select coverage from qualdata->dc_info WHERE identifier ='" . ID . "'";

            $cnn = $db->getDBConnection("Qual_Data");

            $coverage = "";



            $cmd = New Npgsql->NpgsqlCommand(selStr, cnn);
            // cnn->Open();

            $DR As Npgsql->NpgsqlDataReader = cmd->ExecuteReader

           If ( $DR->Read ) {
                coverage = DR ->coverage");

            }

            cnn->Close();

            $items() = coverage->Split(";");

            $locDetails = "";
            $word_stats = "";

            ForEach ( place ) //items
               If ( !$place == "") {


                    locDetails = Regex->Split(place, "wordStats")[0]
                    word_stats = Regex->Split(place, "wordStats")[1]

                    word_stats = word_stats->Remove(0, word_stats->IndexOf("["));

                    word_stats = word_stats->Remove((word_stats->Length - 3), 3);

                    locDetails += "wordsStats"":" . word_stats . "}";

                    $places As unlockDetails = Newtonsoft->Json->JsonConvert->DeserializeObject(Of unlockDetails)(locDetails);

                   If ( !$places Is Nothing ) {

                        $qualData = New qualDataRecord
                        qualData->identifier = ID
                        qualData->lat = places->lat
                        qualData->lon = places->lon
                        qualData->title = places->Name
                        qualData->counts = places->Occurences


                        results[] = (qualData);
                    }

                }

            }




            Return results


        }

        Public Function getChosenLayers($layers As Dictionary(Of String, spatialSearchLayers)) As Dictionary(Of String, spatialSearchLayers);

            $DB = New getDBConnections();
            $cnn = $DB-getDBConnection("Survey_Data");

            ForEach ( layer As KeyValuePair(Of String, spatialSearchLayers) ) //layers

                $geographies() = layer->Value->geographies->Split(";");

                $selStr = New StringBuilder
                selStr->Append("SELECT area_name, ST_AsEWKT(st_simplifypreservetopology(the_geom, 0->000225)) as the_geom from " . layer->Value->id);
                selStr->Append(" WHERE ");

                ForEach ( geog ) //geographies

                    selStr->Append("area_name = '" . geog . "' OR ");


                }


                selStr->Remove((selStr->Length - 3), 3);

                $DataAdapter = New Npgsql->NpgsqlDataAdapter(selStr, cnn);
                $resultsTable = New DataTable
                DataAdapter->Fill(resultsTable);

                ForEach ( row As DataRow ) //resultsTable->Rows
                    $the_geom = New geom

                    the_geom->geom = row ->the_geom");
                    layer->Value->geometry[] = (row ->area_name"), the_geom);


                }




            }


            Return layers


        }


        Public Function getFeatureInfoTable($lat As Double, lon As Double, tables As List(Of IdentifyFeatures)) {

            $htmlOutputs = array();

            ForEach ( Table As IdentifyFeatures ) //tables


                $selStr = "SELECT area_name, total, successful, refused, no_contact, ineligible, other from " . Table->tableID
                selStr += " WHERE ST_Within(st_transform(ST_GeomFromText('POINT(" . lon . " " . lat . ")',27700), 4326), " . Table->tableID . "->the_geom);";


                $db = New getDBConnections();
                $cnn = $db->getDBConnection("Survey_Data");


                $results = New DataTable

                $DA = New Npgsql->NpgsqlDataAdapter(selStr, cnn);

                DA->Fill(results);

                $htmlOut = ConvertToHtmlFile(results);

                $identResults = New IdentifyResults
                identResults->$tableName = Table->$tableName
                identResults->Html = htmlOut

                htmlOutputs[] = (identResults);
            }

            Return htmlOutputs

        }


      // ''' <summary>
      // ''' This is a simple way to convert a DataTable to an HTML file->
      // ''' </summary>
      // ''' <param name="targetTable">This the table to convert-></param>
      // ''' <returns>This is the HTML output, which can saved as a file-></returns>
        Public Shared Function ConvertToHtmlFile(targetTable As DataTable);
            $myHtmlFile = "";



           If ( $targetTable Is Nothing ) {
                Throw New System->ArgumentNullException("targetTable");
              // 'Continue->
          } Else {
            }



          // 'Get a worker object->
            $myBuilder = New StringBuilder();



          // 'Open tags and write the top portion->
          // 'myBuilder->Append("<html xmlns='http://www->w3->org/1999/xhtml'>");
          // 'myBuilder->Append("<head>");
          // 'myBuilder->Append("<title>");
          // 'myBuilder->Append("Page-");
          // 'myBuilder->Append(Guid->NewGuid()());
          // 'myBuilder->Append("</title>");
          // 'myBuilder->Append("</head>");
          // 'myBuilder->Append("<body>");
            myBuilder->Append("<table border='1px' cellpadding='5' cellspacing='0' ");
            myBuilder->Append("style='border: solid 1px Silver; font-size: x-small;'>");



          // 'Add the headings row->



            myBuilder->Append("<tr align='left' valign='top'>");



            ForEach ( myColumn As DataColumn ) //targetTable->Columns
                myBuilder->Append("<td align='left' valign='top'>");
                myBuilder->Append(myColumn->ColumnName);
                myBuilder->Append("</td>");
            }



            myBuilder->Append("</tr>");



          // 'Add the data rows->
            ForEach ( myRow As DataRow ) //targetTable->Rows
                myBuilder->Append("<tr align='left' valign='top'>");



                ForEach ( myColumn As DataColumn ) //targetTable->Columns
                    myBuilder->Append("<td align='left' valign='top'>");
                    myBuilder->Append(myRow(myColumn->ColumnName)());
                    myBuilder->Append("</td>");
                }



                myBuilder->Append("</tr>");
            }



          // 'Close tags->
            myBuilder->Append("</table>");
          // 'myBuilder->Append("</body>");
          // 'myBuilder->Append("</html>");



          // 'Get the string for return->
            myHtmlFile = myBuilder();



            Return myHtmlFile
        }




}

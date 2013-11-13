<?
class getMetaData {

    Public Function getQuestionMetaData($Id) {

//
//        $db = New getDBConnections();
//
//        $cnn = $db->getDBConnection("Survey_Data");
//
//        $DA = new DataAdapter();
//
//        $selectStr = "SELECT * FROM questions where qid ='" . $Id . "';";
//
//        $cmd = pg_query($cnn, $selectStr);
//
//        $resultRows = $DA->Read($cmd);

        $selectStr = "SELECT * FROM questions where qid =:Id;";

        $values = array(":Id" => $Id);
        $resultRows = DataAdapter::DefaultPDOExecuteAndRead($selectStr, $values, "Survey_Data");

        If (count($resultRows->resultObject) > 0) {
            $DR = $resultRows->resultObject[0];

            $qMetaData = new QuestionMetaData();

            $qMetaData->QuestionID = $DR->qid;
            $qMetaData->QuestionLinkedFrom = $DR->link_from;
            $qMetaData->QuestionNumber = $DR->questionnumber;
            $qMetaData->QuestionNotesPrompts = $DR->notes;
            $qMetaData->QuestionSubOf = $DR->subof;
            $qMetaData->QuestionText = $DR->literal_question_text;
            $qMetaData->QuestionThematicGroups = $DR->thematic_groups;
            $qMetaData->QuestionThematicTags = $DR->thematic_tags;
            $qMetaData->QuestionType = $DR->type;
            $qMetaData->QuestionVariable = $DR->variableid;

            Return $qMetaData;

        } Else {

            Return null;

        }



    }


    Public Function getSurveyMetaData($SID) {

//        $db = New getDBConnections();
//
//        $cnn = $db->getDBConnection("Survey_Data");
//
//        $selectStr = "SELECT * FROM survey where surveyid ='" . $SID . "';";
//
//        $cmd = pg_query($cnn, $selectStr);
//
//        $DA = new DataAdapter();
//
//
//        $resultRows = $DA->Read($cmd);

        $selectStr = "SELECT * FROM survey where surveyid =:SID;";
        $values = array(":SID" => $SID);
        $resultRows = DataAdapter::DefaultPDOExecuteAndRead($selectStr, $values, "Survey_Data");

        If (count($resultRows->resultObject) > 0) {

            $DR = $resultRows->resultObject[0];

            $sMetaData = new SurveyMetaData();

            $sMetaData->surveyID = Trim($DR->surveyid);
            $sMetaData->surveyWeighting = Trim($DR->des_weighting);
            $sMetaData->surveyURL = Trim($DR->link);
            $sMetaData->surveyTitle = Trim($DR->survey_title);
            $sMetaData->surveyStart = Trim($DR->collectionstartdate);
            $sMetaData->surveyEnd = Trim($DR->collectionenddate);
            $sMetaData->surveySeries = Trim($DR->long);
            $sMetaData->surveySamplingProcedure = Trim($DR->samp_procedure);
            $sMetaData->surveySamplingError = Trim($DR->descriptionofsamplingerror);
            $sMetaData->surveySampleSize = Trim($DR->samplesize);
            $sMetaData->surveyResponseRate = Trim($DR->responserate);
            $sMetaData->surveyNotes = Trim($DR->notes);
            $sMetaData->surveyLocation = Trim($DR->location);
            $sMetaData->surveyFrequency = Trim($DR->surveyfrequency);
            $sMetaData->surveyDataCollectionMethod = Trim($DR->moc_description);
            $sMetaData->surveyCollector = Trim($DR->datacollector);
            $sMetaData->surveyCollectionSituation = Trim($DR->collectionsituation);

            Return $sMetaData;

        } Else {

            Return null;

        }

    }


    Public Function getResponseMetaData($QID) {

        $selectStr = "SELECT * FROM questions_responses_link where qid =:QID;";
        $values = array(":QID" => $QID);
        $resultLinks = DataAdapter::DefaultPDOExecuteAndRead($selectStr, $values, "Survey_Data");

        If (sizeof($resultLinks->resultObject) > 0) {

            $DR = $resultLinks->resultObject[0];
            $responseID = Trim($DR->responseid);

            $responsesCmd = "SELECT * FROM responses WHERE responseid = :responseID;";
            $values = array(":responseID" => $responseID);
            $resultRows = DataAdapter::DefaultPDOExecuteAndRead($responsesCmd, $values, "Survey_Data");

            If (sizeof($resultRows->resultObject) > 0) {

                $DR = $resultRows->resultObject[0];

                $rMetaData = new ResponseMetaData();

                $rMetaData->questionID = $QID;
                $rMetaData->responseID = $responseID;
                $rMetaData->responseText = $DR->responsetext;
                $rMetaData->responseType = $DR->response_type . "; " . $DR->routetype;
                $rMetaData->responseRouting = $DR->route_notes;
                $rMetaData->responseVariables = $DR->computed_var;
                $rMetaData->responseChecks = $DR->checks;

                Return $rMetaData;

            }

        } Else {
            $rMetaData = new ResponseMetaData();

            $rMetaData->questionID = $QID;
            $rMetaData->responseID = "N/A";
            $rMetaData->responseText = "N/A";
            $rMetaData->responseType = "N/A";
            $rMetaData->responseRouting = "N/A";
            $rMetaData->responseVariables = "N/A";
            $rMetaData->responseChecks = "N/A";
            Return $rMetaData;

        }

        return null;
    }


    Public Function getDublinCore($SID) {

        $selectStr = "Select * from dc_info where identifier=(select identifier from survey where surveyid=:SID);";
        $values = array(":SID" => $SID);
        $resultRows = DataAdapter::DefaultPDOExecuteAndRead($selectStr, $values, "Survey_Data");

        If (count($resultRows->resultObject) > 0) {

            $DR = $resultRows->resultObject[0];

            $dcMeta = new DublinCore();

            $dcMeta->dcContributor = Trim($DR->contributor);
            $dcMeta->dcCoverage = Trim($DR->coverage);
            $dcMeta->dcCreator = Trim($DR->creator);
            $dcMeta->dcDate = Trim($DR->date);
            $dcMeta->dcDescription = Trim($DR->description);
            $dcMeta->dcFormat = Trim($DR->format);
            $dcMeta->dcLanguage = Trim($DR->language);
            $dcMeta->dcPublisher = Trim($DR->publisher);
            $dcMeta->dcRelation = Trim($DR->relation);
            $dcMeta->dcRights = Trim($DR->rights);
            $dcMeta->dcSource = Trim($DR->source);
            $dcMeta->dcSubject = Trim($DR->subject);
            $dcMeta->dcTitle = Trim($DR->title);
            $dcMeta->dcType = Trim($DR->type);
            $dcMeta->dcWiserdID = Trim($DR->identifier);
            Return $dcMeta;

        } Else {
            Return null;
        }

    }

    Public Function getResponseTable($SID, $unit) {

// The response table name comes from the client, so we can't trust it.
        // Check if the constructed tablename is exactly equal to an existing tables name
        // If the string is an exact match, continue, otherwise throw an error.

        $tablename = $this::getTableName($SID, $unit);

        if(!DataAdapter::isTrustedTableName($tablename, "Survey_Data")){
            Log::toFile("Tablename not trusted : " . $tablename );
            return null;
        }

        $selectStr = "Select * from " . $tablename . ";";

        $results = DataAdapter::DefaultPDOExecuteAndRead($selectStr, null, "Survey_Data");


        $responses = array();

        ForEach ($results->resultObject as $DR) {
            $rMeta = new ResponseTable();

            $rMeta->name = $DR->area_name;
            $rMeta->total = $DR->total;
            $rMeta->successful = $DR->successful;
            $rMeta->responseRate = $DR->response_rate;
            $rMeta->refused = $DR->refused;
            $rMeta->other = $DR->other;
            $rMeta->noContact = $DR->no_contact;
            $rMeta->ineligible = $DR->ineligible;
            $rMeta->adjustedRRate = $DR->adjusted_rr;

            $responses[] = $rMeta;

        }

        Return $responses;

    }

    Public Function getFields( $SID, $unit ) {

        $TableName = $this::getTableName($SID, $unit);

        $selectStr = "Select column_name as name from information_schema.columns
        where table_name =:TableName;";

        $values = array(":TableName" => $TableName);
        $results = array();

        $fieldResults = DataAdapter::DefaultPDOExecuteAndRead($selectStr, $values, "Survey_Data");

        ForEach ($fieldResults->resultObject as $DR) {

            $label = new Fields();
            $label->Name = Trim($DR->name);
            $results[] = ($label);
        }


        Return $results;


    }


    Private Function getTableName( $SID, $unit ) {

//        Log::toFile("getTableName " . $SID . " " . $unit);

        If ($unit == "Police Region" ) {
            Return "x_" . $SID . "_police_";
        } ElseIf ($unit == "Assembly Economic Fora Area" ) {
            Return "x_" . $SID . "_aefa_";
        } ElseIf ($unit == "Fire Brigade Region" ) {
            Return "x_" . $SID . "_fire_";
        } ElseIf ($unit == "Lower Super Output Area" ) {
            Return "x_" . $SID . "_lsoa_";
        } ElseIf ($unit == "Parliamentary Constituencies" ) {
            Return "x_" . $SID . "_parl_";
        } ElseIf ($unit == "Postcode Sector" ) {
            Return "x_" . $SID . "_pcode_";
        } ElseIf ($unit == "Unitary Authority" ) {
            Return "x_" . $SID . "_ua_";
        }
        return null;
    }



    Public Function getQDublinCore( $SID  ) {

        $selStr = "Select * from qualdata.dc_info WHERE identifier = :SID;";
        $values = array(":SID" => $SID);
        $resultRows = DataAdapter::DefaultPDOExecuteAndRead($selStr, $values, "Qual_Data");


        ForEach ($resultRows->resultObject as $DR) {



            $dcMeta = New DublinCore();

            $coverage = Trim($DR->coverage);

//            Log::toFile("QDC item : " . $coverage);

            $placeNames = "";

            $items = explode(";", $coverage );

//            Log::toFile("coverageItems : " . count($items));

            $locDetails = "";
            $word_stats = "";


            ForEach ($items as $place) {
                If (! $place == "" ) {

//                    Log::toFile("QDC : " . print_r($place, true));

                    $pattern = "/\"{name:(.*), data/";
                    $replacement = '{"name":"$1", "data"';
                    $subject = $place;

                    $result = preg_replace($pattern, $replacement, $subject);

                    $result = substr($result, 0, -2);
                    $result .= "}";

                    $placeObject = json_decode($result);

//                    ForEach ($placeObject->wordStats as $wordData){

                    $placeNames .= $placeObject->wordStats->name . ";";

//                    $placeArray = explode("wordStats", $place);
//                    $locDetails = $placeArray[0];
//                    $word_stats = $placeArray[1];
//
//                    $word_stats = substr($word_stats, 0, strpos($word_stats, '['));
//
//                    $word_stats = substr($word_stats, (strlen($word_stats) - 3));
//
//                    $locDetails .= "wordsStats" . ":" . $word_stats . "}";
//
//                    $places = json_decode(utf8_encode($locDetails));
//
//                    If (! $places == null ) {
//                        $placeNames .= $places->Name . ";";
//                    }

                }

            }



            $dcMeta->dcContributor = Trim($DR->contributor);
            $dcMeta->dcCoverage = $placeNames;
            $dcMeta->dcCreator = Trim($DR->creator);
            $dcMeta->dcDate = Trim($DR->date);
            $dcMeta->dcDescription = Trim($DR->description);
            $dcMeta->dcFormat = Trim($DR->format);
            $dcMeta->dcLanguage = Trim($DR->language);
            $dcMeta->dcPublisher = Trim($DR->publisher);
            $dcMeta->dcRelation = Trim($DR->relation);
            $dcMeta->dcRights = Trim($DR->rights);
            $dcMeta->dcSource = Trim($DR->source);
            $dcMeta->dcSubject = Trim($DR->subject);
            $dcMeta->dcTitle = Trim($DR->title);
            $dcMeta->dcType = Trim($DR->type);
            $dcMeta->dcWiserdID = Trim($DR->identifier);

            Return $dcMeta;


        }
        return null;
    }


    Public Function getQualWords( $ID , $place1  ,  $Place2  ,  $Place3 ) {
        $wordStats = array();
        $wordCol = array();
        $pageCol = array();
        $docwords = array();

//        $db = New getDBConnections();

//        $cnn = $db->getDBConnection("Qual_Data");


        $selStr = "Select coverage from qualdata.dc_info WHERE identifier =:ID;";

        $values = array(":ID" => $ID);
//        $cmd = pg_query($cnn, $selStr);

//        $DA = new DataAdapter();

//        $resultRows = $DA->Read($cmd);


//        $resultRows = DataAdapter::DefaultExecuteAndRead($selStr, "Qual_Data");
        $results = DataAdapter::DefaultPDOExecuteAndRead($selStr, $values, "Qual_Data");

//        Log::toFile("qual word : " . print_r($resultRows, true));

        ForEach ($results->resultObject[0] as $DR) {

//            $coverage = Trim($DR->coverage);

//            Log::toFile("DR : " . print_r($DR, true));

            $placeNames = "";

            $items = explode(";", $DR);


            $locDetails = "";
            $word_stats = "";

            ForEach( $items as $place) {
                If (! $place == "" ) {

                    $w = array();

                    $pattern = "/\"{name:(.*), data/";
                    $replacement = '{"name":"$1", "data"';
                    $subject = $place;

//                    Log::toFile("qual words places pre reg : " . print_r($subject, true));


                    $result = preg_replace($pattern, $replacement, $subject);

                    $result = substr($result, 0, -2) . "}";

//                    Log::toFile("qual words places string : " . print_r($result, true));

                    $places = json_decode($result);

//                    Log::toFile("qual words places : " . print_r($places, true));


//                    $placeArray = explode($place, "wordStats");
//                    $locDetails = $placeArray[0];
//                    $word_stats = $placeArray[1];
//
//                    $word_stats = substr($word_stats, 0, strpos($word_stats, '[')); // $word_stats.Remove(0, $word_stats.IndexOf("["));
//
//                    $word_stats = substr($word_stats, (strlen($word_stats) - 3));  //$word_stats.Remove((strlen($word_stats) - 3), 3);
//
//                    $locDetails .= "wordsStats" . ":" . $word_stats . "}";
//
//                    $places = json_decode($locDetails);

                    If ($place1 == $places->Name Or $Place2 == $places->Name Or $Place3 == $places->Name ) {

                        $wordData = $places->wordStats->data;

//                        Log::toFile("wordData : " . print_r($wordData, true));


                        ForEach ($wordData as $stat) {
                            $wordcountPos = New qualWords();

                            $wordcountPos->count = $stat->count;
                            $wordcountPos->page = $stat->page;
                            $wordcountPos->name = $places->Name;

                            $w[] = ($wordcountPos);

                        }

                        $docwords[] = ($w);
                    }
                }

                $wordCol = ($docwords);

            }

        }

//        Log::toFile("wordCol : " . print_r($wordCol, true));

        ForEach ( $wordCol as $placeCollection) {

            ForEach ($placeCollection as $wrd) {

                If (array_key_exists($wrd->page, $pageCol) ) {

                    $page = $pageCol[$wrd->page];

                    If ($page->place2 == "" ) {
                        $page->place2 = $wrd->name;
                        $page->place2Count = $wrd->count;
                    } Else {
                        $page->place3 = $wrd->name;
                        $page->place3Count = $wrd->count;

                    }

                } Else {
                    $page = New pageCollection();
                    $page->place1 = $wrd->name;
                    $page->place1Count = $wrd->count;
                    $page->page = $wrd->page;
                    $pageCol[$wrd->page] = $page;

                }


            }

//            Log::toFile("pageCol : " . print_r($pageCol, true));
        }

        ForEach ($pageCol as $key => $obj) {
            $wordStats[$key] = ($obj);

        }

        Return $wordStats;


    }

    Public Function getPlaces($ID) {

        $placeNames = array();

        $selStr = "Select coverage from qualdata.dc_info WHERE identifier = :ID;";
        $values = array(":ID" => $ID);
        $resultRows = DataAdapter::DefaultPDOExecuteAndRead($selStr, $values, "Qual_Data");

        ForEach ($resultRows->resultObject as $DR) {

            $coverage = Trim($DR->coverage);

            $items = explode(";", $coverage);

            $locDetails = "";
            $word_stats = "";

            ForEach ($items as $place) { //place As String In items
                If (! $place == "" ) {

                    $pattern = "/\"{name:(.*), data/";
                    $replacement = '{"name":"$1", "data"';
                    $subject = $place;

                    $result = preg_replace($pattern, $replacement, $subject);

                    $result = substr($result, 0, -2) . "}";

                    $placeObject = json_decode($result);

                    $pl = New place();
                    $pl->place = $placeObject->wordStats->name;
                    $placeNames[] = ($pl);

                }

            }

        }

        Return $placeNames;

    }

    Public Function getCloud($ID) {

        $Tags = array();

        $selStr = "Select calais from qualdata.dc_info WHERE identifier =:ID;";

        $values = array(":ID" => $ID);
        $resultRows = DataAdapter::DefaultPDOExecuteAndRead($selStr, $values, "Qual_Data");

        $tagsDetails = "";

        ForEach ($resultRows->resultObject as $DR) {

            $calais = $DR->calais;

            $calaisTrim = trim($calais);

            $calaisTrim = utf8_encode( rtrim($calaisTrim, ",") );

            $createJson = "[" . $calaisTrim . "]";

            $jsonObject = json_decode($createJson);

            forEach($jsonObject As $jsonRow) {
                $tagsDetails .= '{"word":"' . $jsonRow->Value . '","count":' . $jsonRow->Count . "},";
            }

        }
        Return '{"tags":[' . $tagsDetails . ']}';

    }

    public function getResponseOptionsTable($QID)
    {

        $selectStr = "Select table_name, column_name from information_schema.columns
        where lower(table_name) in
        (select lower(table_ids) from responses where responseID =
        ( SELECT responseID FROM questions_responses_link where qid = :QID)
                )
                and column_name != 'table_pk' and column_name != 'user_id' and
        column_name != 'date_time' and column_name != 'res_table_id';";
        $values = array(":QID" => $QID);
        $responseColumns = DataAdapter::DefaultPDOExecuteAndRead($selectStr, $values, "Survey_Data");

        If (sizeof($responseColumns->resultObject) > 0) {

            $columns = "";
            $columnArray = array();
            $tablename = "";

            ForEach ($responseColumns->resultObject as $columnName) {

                $columns .= $columnName->column_name . ",";
                $columnArray[] = $columnName->column_name;
                $tablename = $columnName->table_name;
            }
            $columns = rtrim($columns, ",");

            $responseValuesQuery = "Select " . $columns . " from " . $tablename;

            Log::toFile($responseValuesQuery);

            $responseValues = DataAdapter::DefaultPDOExecuteAndRead($responseValuesQuery, null, "Survey_Data");

            Log::toFile(print_r($responseValues, true));

            $returnArray = array();
            $returnArray['headers'] = $columnArray;
            $returnArray['data'] = $responseValues->resultObject;

            return $returnArray;
        }
        return null;
    }

}



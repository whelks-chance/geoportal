<?
class getMetaData {

    Public Function getQuestionMetaData($Id) {


        $db = New getDBConnections();

        $cnn = $db->getDBConnection("Survey_Data");

        $DA = new DataAdapter();

        $selectStr = "SELECT * FROM questions where qid ='" . $Id . "';";

        $cmd = pg_query($cnn, $selectStr);

        $resultRows = $DA->Read($cmd);

        If (count($resultRows) > 0) {
            $DR = $resultRows[0];

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

        $db = New getDBConnections();

        $cnn = $db->getDBConnection("Survey_Data");

        $selectStr = "SELECT * FROM survey where surveyid ='" . $SID . "';";

        $cmd = pg_query($cnn, $selectStr);

        $DA = new DataAdapter();

        $resultRows = $DA->Read($cmd);

        If (count($resultRows) > 0) {

            $DR = $resultRows[0];

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

        $db = New getDBConnections();

        $cnn = $db->getDBConnection("Survey_Data");

        $selectStr = "SELECT * FROM questions_responses_link where qid ='" . $QID . "';";

        $cmd = pg_query($cnn, $selectStr);

        $DA = new DataAdapter();

        $resultRows = $DA->Read($cmd);

        If (count($resultRows) > 0) {

            $DR = $resultRows[0];
            $responseID = Trim($DR->responseid);

            $responsesCmd = "SELECT * FROM responses WHERE responseid = '" . $responseID . "';";

            $cmd = pg_query($cnn, $responsesCmd);

            $DA = new DataAdapter();

            $resultRows = $DA->Read($cmd);

            If (count($resultRows) > 0) {

                $DR = $resultRows[0];

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

        $db = new getDBConnections();

        $cnn = $db->getDBConnection("Survey_Data");

        $selectStr = "Select * from dc_info WHERE identifier ='wi" . $SID . "';";

        $cmd = pg_query($cnn, $selectStr);

        $DA = new DataAdapter();

        $resultRows = $DA->Read($cmd);

        If (count($resultRows) > 0) {

            $DR = $resultRows[0];

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

        $db = new getDBConnections();

        $cnn = $db->getDBConnection("Survey_Data");

        $tablename = $this::getTableName($SID, $unit);


        $selectStr = "Select * from " . $tablename . ";";

        $cmd = pg_query($cnn, $selectStr);

        $DA = new DataAdapter();

        $resultRows = $DA->Read($cmd);

        $responses = array();

        ForEach ($resultRows as $DR) {
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
        $dc = New getDBConnections();

        $cnn = $dc->getDBConnection("Survey_Data");


        $TableName = $this::getTableName($SID, $unit);


        $selectStr = "Select column_name as name from information_schema.columns where table_name ='" . $TableName . "';";


        $results = array();

        $cmd = pg_query($cnn, $selectStr);

        $DA = new DataAdapter();

        $resultRows = $DA->Read($cmd);

        ForEach ($resultRows as $DR) {

            $label = new Fields();
            $label->Name = Trim($DR->Name);
            $results[] = ($label);
        }


        Return $results;


    }


    Private Function getTableName( $SID, $unit ) {

        If ($unit = "Police Region" ) {
            Return "x_" . $SID . "_police_";
        } ElseIf ($unit = "Assembly Economic Fora Area" ) {
            Return "x_" . $SID . "_aefa_";
        } ElseIf ($unit = "Fire Brigade Region" ) {
            Return "x_" . $SID . "_fire_";
        } ElseIf ($unit = "Lower Super Output Area" ) {
            Return "x_" . $SID . "_lsoa_";
        } ElseIf ($unit = "Parliamentary Constituencies" ) {
            Return "x_" . $SID . "_parl_";
        } ElseIf ($unit = "Postcode Sector" ) {
            Return "x_" . $SID . "_pcode_";
        } ElseIf ($unit = "Unitary Authority" ) {
            Return "x_" . $SID . "_ua_";
        }
        return null;
    }



    Public Function getQDublinCore( $SID  ) {


        $db = New getDBConnections();

        $cnn = $db->getDBConnection("Qual_Data");


        $selStr = "Select * from qualdata.dc_info WHERE identifier ='" . $SID . "';";

        $cmd = pg_query($cnn, $selStr);

        $DA = new DataAdapter();

        $resultRows = $DA->Read($cmd);

        ForEach ($resultRows as $DR) {
            $dcMeta = New DublinCore();


            $coverage = Trim($DR->coverage);

            $placeNames = "";

            $items[] = explode($coverage, ";");

            $locDetails = "";
            $word_stats = "";

            ForEach ($items as $place) {
                If (! $place == "" ) {

                    Log::toFile("wordStats : " . $place);

                    $placeArray = explode($place, "wordStats");
                    $locDetails = $placeArray[0];
                    $word_stats = $placeArray[1];

                    $word_stats = $word_stats.Remove(0, strpos("[", $word_stats));

                    $word_stats = $word_stats.Remove((strlen($word_stats) - 3), 3);

                    $locDetails .= "wordsStats" . ":" . $word_stats . "}";

                    $places = json_decode($locDetails);

                    If (! $places == null ) {
                        $placeNames .= $places->Name . ";";
                    }

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
//                cnn.Close()
            Return $dcMeta;


        }
        return null;
    }


    Public Function getQualWords( $ID , $place1  ,  $Place2  ,  $Place3 ) {
        $wordStats = array();
        $wordCol = array();
        $pageCol = New Dictionary(String, new pageCollection());
        $docwords = array();

        $db = New getDBConnections();

        $cnn = $db->getDBConnection("Qual_Data");


        $selStr = "Select coverage from qualdata.dc_info WHERE identifier ='" . $ID . "';";

        $cmd = pg_query($cnn, $selStr);

        $DA = new DataAdapter();

        $resultRows = $DA->Read($cmd);

        ForEach ($resultRows as $DR) {


            $coverage = Trim($DR->coverage);

            $placeNames = "";

            $items = explode($coverage, ";");

            $locDetails = "";
            $word_stats = "";

            ForEach( $items as $place) {
                If (! $place == "" ) {

                    $w = array();

                    Log::toFile(print_r($place, true));

                    $placeArray = explode($place, "wordStats");
                    $locDetails = $placeArray[0];
                    $word_stats = $placeArray[1];

                    $word_stats = substr($word_stats, 0, strpos($word_stats, '[')); // $word_stats.Remove(0, $word_stats.IndexOf("["));

                    $word_stats = substr($word_stats, (strlen($word_stats) - 3));  //$word_stats.Remove((strlen($word_stats) - 3), 3);

                    $locDetails .= "wordsStats" . ":" . $word_stats . "}";

                    $places = json_decode($locDetails);

                    If ($place1 == $places->Name Or $Place2 == $places->Name Or $Place3 == $places->Name ) {

                        ForEach ($places->wordsStats as $stat) {
                            $wordcountPos = New qualWords();

                            $wordcountPos->count = $stat->count;
                            $wordcountPos->page = $stat->page;
                            $wordcountPos->name = $places->Name;

                            $w[] = ($wordcountPos);

                        }

                        $docwords[] = ($w);
                    }
                }

                $wordCol[] = ($docwords);

            }

        }



        ForEach ( $wordCol as $placeCollection) {

            ForEach ($placeCollection as $wrd) {

                If (array_key_exists($pageCol, $wrd->page) ) {

                    $page = $pageCol[$wrd->page];

                    If ($page->place2 == null ) {
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


            ForEach ($pageCol as $obj) {
                $wordStats[] = ($obj->Value);

            }

            Return $wordStats;


        }
    }

//    Public Function getPlaces($ID) {
//
//        $placeNames = array();
//
//        $db = New getDBConnections();
//
//        $cnn = $db->getDBConnection("Qual_Data");
//
//
//        $selStr = "Select coverage from qualdata.dc_info WHERE identifier ='" . $ID . "';";
//
//            $DR As NpgsqlDataReader
//            $cmd = pg_query($cnn, $selStr);
//
//            cnn.Open()
//            DR = cmd.ExecuteReader
//
//            If (DR.Read ) {
//
//
//                $coverage = Trim($DR->coverage);
//
//                $items() = $coverage.Split(";");
//
//                $locDetails = ""
//                $word_stats = ""
//
//                For Each place As String In items
//                    If (! $place = "" ) {
//
//
//
//
//                        $locDetails = Regex.Split(place, "wordStats")(0)
//                        $word_stats = Regex.Split(place, "wordStats")(1)
//
//                        word_stats = word_stats.Remove(0, word_stats.IndexOf("[")
//
//                        word_stats = word_stats.Remove((word_stats.Length - 3), 3)
//
//                        locDetails += "wordsStats"":" . word_stats . "}"
//
//                        $places As unlockDetails = Newtonsoft.Json.JsonConvert.DeserializeObject(Of unlockDetails)(locDetails)
//
//                        $pl As New place
//                        pl.place = places.Name
//                        placeNames.Add(pl)
//
//
//                    }
//
//                Next
//
//            }
//
//            Return placeNames
//
//        }
//
//    Public Function getCloud($ID) {
//
//        $Tags = array();
//
//        $db = New getDBConnections();
//
//        $cnn = $db->getDBConnection("Qual_Data");
//
//
//            $selStr = "Select calais from qualdata.dc_info WHERE identifier ='" . ID . "';"
//
//            $DR As NpgsqlDataReader
//            $cmd = pg_query($cnn, $selStr);
//
//            cnn.Open()
//            DR = cmd.ExecuteReader
//
//
//
//            If (DR.Read ) {
//
//                $json = $DR->calais;
//
//                json = json.TrimEnd("," . vbCrLf . "")
//
//
//                $jsons() = json.Split("},")
//
//                $tagsDetails = ""
//
//                For Each item As String In jsons
//
//                    If (! item = " " ) {
//                        item = item.TrimStart(",")
//
//                        $subItems() = item.Split(",")
//
//                        If (subItems.Length = 6 ) {
//
//                            $dict As Dictionary(Of String, String) = subItems.ToDictionary(Function(value As String)
//                                                                                                  Return value.Split(":")(0)
//                                                                                              },
//                                                  Function(value As String)
//                                                      Return value.Split(":")(1)
//                                                  })
//
//                            $word = dict.Item("""Value""").Replace(Char.ConvertFromUtf32(34), String.Empty)
//                            $cnt = dict.Item("""Count""").Replace(Char.ConvertFromUtf32(34), String.Empty)
//
//                            tagsDetails += "{""word"":""" . word . """,""count"":" . CInt(cnt) . "},"
//                        }
//                    }
//Next
//
//    // ' $obj As List(Of Calais) = Newtonsoft.Json.JsonConvert.DeserializeObject(Of List(Of Calais))(json)
//
////                'Newtonsoft.Json.JsonConvert.DeserializeObject($DR->calais)
//
//Return '{"tags":[' . $tagsDetails . ']}';
//
//}
//
//
//
//
//}

}



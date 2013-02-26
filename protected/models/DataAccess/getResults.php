<?
class getResults {

//    Public $count;


    Public Function getQuestionnaireData($start, $limit, $keywords, $verify, $mappable) {

//ByVal start As Integer, ByVal limit As Integer, ByVal keywords As String, ByVal verify As Boolean, ByVal mappable As Boolean
//        $dt = New DataTable();

        $keywordsArray = explode(",", $keywords);

        $SSearch = "";
        //'keywords = "wales"

    //    Log::toFile(var_export($keywordsArray));

        If (sizeof($keywordsArray) > 1) {

            $multiKeyword = "";

//            For Each keyword As String In keywordsArray
            Foreach ($keywordsArray as $keyword) {
                $multiKeyword .= $keyword . " &";
            }
//            Next

            $multiKeyword = rtrim($multiKeyword, "&");
            $SSearch.=("SELECT qid, questionnumber as qnumber, link_from, thematic_groups, thematic_tags, ts_headline('english',literal_question_text, plainto_tsquery('english','" . $multiKeyword . "')) as original_text, notes as q_notes, subof as subof, type as q_type, link_from as parent_q, ts_rank_cd(to_tsvector(literal_question_text), plainto_tsquery('english','" . $multiKeyword . "'),0) AS rank");
            $SSearch.=(" FROM questions ");
            $SSearch.=("WHERE qtext_index @@ to_tsquery('english','" . $multiKeyword . "')");
        } Else {

            $SSearch.=("SELECT qid, questionnumber as qnumber, link_from, thematic_groups, thematic_tags, ts_headline('english',literal_question_text, plainto_tsquery('english','" . $keywords . "')) as original_text, notes as q_notes, subof as subof, type as q_type, link_from as parent_q, ts_rank_cd(to_tsvector(literal_question_text), plainto_tsquery('english','" . $keywords . "'),0) AS rank");
            $SSearch.=(" FROM questions ");
            $SSearch.=("WHERE qtext_index @@ to_tsquery('english','" . $keywords . "')");
        }

        //'SSearch.Append(" WHERE query @@ to_tsvector(literal_question_text)")
        $SSearch.=("ORDER BY rank DESC");

        If ($verify == True) {
            $SSearch.=(" LIMIT 1 ");
        } Else {
            $SSearch.=(" LIMIT 1000 ");

        }

        $db = new getDBConnections();
        $cnn = $db->getDBConnection("Survey_Data");


//Npgsql.NpgsqlCommand
        $cmd = pg_query($cnn, $SSearch);

        $DA = new DataAdapter();

        $rows = $DA->Read($cmd);

        $id = 1;
//        $cnn.Open();
//        $DA->Fill($dt);
//        $cnn.Close();



//        $results = new SortedDictionary(Of String, Object);
        $results = array();


//        $qtype = "";

//        For Each row As DataRow In dt.Rows
        foreach ($rows as $row) {

//            @var DataRow $row

            $qtype = Trim($row->q_type);
            Log::toFile("qType : " . $qtype);

            If ($qtype == "ROOT Question") {
                $rootQ = new rootQuestionDetails();
                $rootQ->QuestionID = Trim($row->qid);
                $rootQ->QuestionNumber = Trim($row->qnumber);
                $rootQ->QuestionText = Trim($row->original_text);
                $rootQ->QuestionNotes = Trim($row->q_notes);
                $rootQ->QuestionThematicGroup = Trim($row->thematic_groups);
                $rootQ->QuestionThematicTag = Trim($row->thematic_tags);
                $rootQ->QuestionType = "ROOT Question";
                $rootQ->Rank = $row->rank;
                $rootQ->DataSource = "WISERD DB";
                $rootQ->RecordID = $id;

                $survey_ID = Trim($row->link_from);

                $survey_details = "Select * from Survey WHERE surveyid = (Select surveyid as query from survey_questions_link WHERE qid ='" . $survey_ID . "');";

                $surveycmd = pg_query($cnn, $survey_details);

                $DA = new DataAdapter();
                $surveyResults = $DA->Read($surveycmd);

                If (count($surveyResults) > 0) {
                    $surDRdr = $surveyResults[0];

                    $rootQ->SurveyID = Trim($surDRdr->surveyid);
                    $rootQ->DataSource = $this::getDataSourceType($surDRdr->surveyid);
                    $rootQ->SurveyName = Trim($surDRdr->survey_title);
                    $rootQ->SurveyCollectionFrequency = Trim($surDRdr->surveyfrequency);
                    $rootQ->spatial = ($surDRdr->spatialdata == 't');
                }

//                cnn.Close();

                $toFind = Trim($row->qid);
                If (!array_key_exists($toFind, $results)) {
                    $results[$toFind] = $rootQ;
                }


                // 'Exit For
            } ElseIf ($qtype == "SINGLE Question") {


                $singleQ = new SingleQuestion();
                $singleQ->QuestionID = Trim($row->qid);
                $singleQ->QuestionNumber = Trim($row->qnumber);
                $singleQ->QuestionText = Trim($row->original_text);
                $singleQ->QuestionNotes = Trim($row->q_notes);
                $singleQ->QuestionThematicGroup = Trim($row->thematic_groups);
                $singleQ->QuestionThematicTag = Trim($row->thematic_tags);
                $singleQ->QuestionType = "Single Question";
                $singleQ->Rank = $row->rank;
                $singleQ->DataSource = "WISERD DB";
                $singleQ->RecordID = $id;


                $survey_ID = Trim($row->link_from);

                $survey_details = "Select * from Survey WHERE surveyid = (Select surveyid as query from survey_questions_link WHERE qid ='" . $survey_ID . "');";

                $surveycmd = pg_query($cnn, $survey_details);

                $DA = new DataAdapter();
                $surveyRows = $DA->Read($surveycmd);

//                cnn.Open();
//                $surDRdr = surveycmd.ExecuteReader;

                If (count($surveyRows) > 0) {
                    $surDRdr = $surveyRows[0];

                    $singleQ->SurveyID = Trim($surDRdr->surveyid);
                    $singleQ->DataSource = $this::getDataSourceType($surDRdr->surveyid);
                    $singleQ->SurveyName = Trim($surDRdr->survey_title);
                    $singleQ->SurveyCollectionFrequency = Trim($surDRdr->surveyfrequency);
                    $singleQ->spatial = ($surDRdr->spatialdata == 't');
                }
//                cnn.Close();
                $toFind = Trim($row->qid);
                If (!array_key_exists($toFind, $results)) {
                    $results[$toFind] = $singleQ;
                }

            } ElseIf ($qtype == "SUB Question") {

                $subQ = new subQuestionDetails();
                $subQ->QuestionID = Trim($row->qid);
                $subQ->QuestionNumber = Trim($row->qnumber);
                $subQ->QuestionText = Trim($row->original_text);
                $subQ->QuestionNotes = Trim($row->q_notes);
                $subQ->QuestionThematicGroup = Trim($row->thematic_groups);
                $subQ->QuestionThematicTag = Trim($row->thematic_tags);
                $subQ->QuestionType = "Sub Question Question";
                $subQ->Rank = $row->rank;
                $subQ->DataSource = "WISERD DB";
                $subQ->RecordID = $id;
                $subQ->RootQuestion = Trim($row->subof);

                $survey_ID = Trim($row->link_from);

                $survey_details = "Select * from Survey WHERE lower(surveyid) = lower((Select distinct(surveyid) from survey_questions_link WHERE qid = lower('" . $survey_ID . "')));";

                $surveycmd = pg_query($cnn, $survey_details);

                $DA = new DataAdapter();
                $surveyResults = $DA->Read($surveycmd);

//                cnn.Open();
//                $surDRdr = surveycmd.ExecuteReader;

                If (count($surveyResults) > 0) {
                    $surDRdr = $surveyResults[0];

                    $subQ->SurveyID = Trim($surDRdr->surveyid);
                    $subQ->DataSource = $this::getDataSourceType($surDRdr->surveyid);
                    $subQ->SurveyName = Trim($surDRdr->survey_title);
                    $subQ->SurveyCollectionFrequency = Trim($surDRdr->surveyfrequency);
                    $subQ->spatial = ($surDRdr->spatialdata == 't');
                } Else {

                }


//                cnn.Close();

                $toFind = Trim($row->qid);
                If (!array_key_exists($toFind, $results)) {
                    $results[$toFind] = $subQ;
                }


            } ElseIf ($qtype == "COMPOUND Question") {

                $compoundQ = new compoundQuestionDetails();
                $compoundQ->QuestionID = Trim($row->qid);
                $compoundQ->QuestionNumber = Trim($row->qnumber);
                $compoundQ->QuestionText = Trim($row->original_text);
                $compoundQ->QuestionNotes = Trim($row->q_notes);
                $compoundQ->QuestionThematicGroup = Trim($row->thematic_groups);
                $compoundQ->QuestionThematicTag = Trim($row->thematic_tags);
                $compoundQ->QuestionType = "Compound Question";
                $compoundQ->Rank = $row->rank;
                $compoundQ->DataSource = "WISERD DB";
                $compoundQ->RecordID = $id;

                $survey_ID = Trim($row->link_from);

                $survey_details = "Select * from Survey WHERE surveyid = (Select surveyid as query from survey_questions_link WHERE qid ='" . $survey_ID . "');";

                $surveycmd = pg_query($cnn, $survey_details);

                $DA = new DataAdapter();
                $surveyResults = $DA->Read($surveycmd);

                If (count($surveyResults) > 0) {
                    $surDRdr = $surveyResults[0];

                    $compoundQ->SurveyID = Trim($surDRdr->surveyid);
                    $compoundQ->DataSource = $this::getDataSourceType($surDRdr->surveyid);
                    $compoundQ->SurveyName = Trim($surDRdr->survey_title);
                    $compoundQ->SurveyCollectionFrequency = Trim($surDRdr->surveyfrequency);
                    $compoundQ->spatial = ($surDRdr->spatialdata == 't');
                }

                $toFind = Trim($row->qid);
                If (!array_key_exists($toFind, $results)) {
                    $results[$toFind] = $compoundQ;
                }

            } ElseIf ($qtype == "SUB of SUB Question") {

                $subsubQ = new subQuestionDetails();
                $subsubQ->QuestionID = Trim($row->qid);
                $subsubQ->QuestionNumber = Trim($row->qnumber);
                $subsubQ->QuestionText = Trim($row->original_text);
                $subsubQ->QuestionNotes = Trim($row->q_notes);
                $subsubQ->QuestionThematicGroup = Trim($row->thematic_groups);
                $subsubQ->QuestionThematicTag = Trim($row->thematic_tags);
                $subsubQ->QuestionType = "Sub of A Sub Question";
                $subsubQ->Rank = $row->rank;
                $subsubQ->RecordID = $id;
                $subsubQ->RootQuestion = Trim($row->subof);

                $survey_ID = Trim($row->link_from);

                $survey_details = "Select * from Survey WHERE surveyid = (Select surveyid as query from survey_questions_link WHERE qid ='" . $survey_ID . "');";

                $surveycmd = pg_query($cnn, $survey_details);

                $DA = new DataAdapter();
                $surveyResults = $DA->Read($surveycmd);

                If (count($surveyResults) > 0) {
                    $surDRdr = $surveyResults[0];

                    $subsubQ->SurveyID = Trim($surDRdr->surveyid);
                    $subsubQ->DataSource = $this::getDataSourceType($surDRdr->surveyid);
                    $subsubQ->SurveyName = Trim($surDRdr->survey_title);
                    $subsubQ->SurveyCollectionFrequency = Trim($surDRdr->surveyfrequency);
                    $subsubQ->spatial = ($surDRdr->spatialdata == 't');
                }
//                cnn.Close();
//                If (Not results.ContainsKey(Trim($row->qid")))) {
//                    results.Add(Trim($row->qid")), subsubQ);
//                }
                $toFind = Trim($row->qid);
                If (!array_key_exists($toFind, $results)) {
                    $results[$toFind] = $subsubQ;
                }
            }

            $id += 1;
        }

//        cnn.Dispose();
        $cnn = null;
        $db = null;
//        GC.Collect();

        $finalResults = array();

//        $count = sizeof($results);
        If ($mappable == True) {
//            For Each (result As KeyValuePair(Of String, Object) In results){
            ForEach ($results as $result) {
                If ($result->spatial == 't') {
                    $finalResults[] = $result;
                }
            }

        } Else {
//            ForEach (result As KeyValuePair(Of String, Object) In results) {
            ForEach ($results as $result) {
                $finalResults[] = $result;

            }

        }


        return $finalResults;



    }


    Public Function getDataSourceType($ID) {

        $DataSourceType = "";

        $idArray = explode($ID, ("_"));
        $prefix = $idArray[0];


        If ($prefix == "sid") {
            $DataSourceType = "Survey Data";

        }

        Return $DataSourceType;
    }

    Public Function getSurveyQuestion($SID) {

        $Qs = array();

        $selStr = "SELECT * from survey_questions_link WHERE surveyid ='" . Trim($SID) . "';";

        $db = new getDBConnections();

        $cnn = $db->getDBConnection("Survey_Data");

//        $DT = new DataTable();
        $queryResult = pg_query($cnn, $selStr);

        $DA = new DataAdapter();
        $rows = $DA->Read($queryResult);

//        $DA->Fill($DT);

        ForEach ($rows as $row) { // row As DataRow In DT.Rows
            $qid = $row->Item["qid"];

            If (!$qid == "") {
                $DR = new DataReader();

                $selQ = "Select questionnumber, literal_question_text, thematic_groups, thematic_tags FROM questions WHERE qid = '" . Trim($qid) . "';";

                $cmd = pg_query($cnn, $selQ);
//                        cnn.Open();
                $DR->ExecuteReader($cmd);
//                        DR.Read();

                $question = new allQuestions();
                $question->qid = $qid;
                $question->questionNumber = Trim($DR->Item("questionnumber"));
                $question->questionText = Trim($DR->Item("literal_question_text"));
                $question->group = Trim($DR->Item("thematic_groups"));
                $question->tag = Trim($DR->Item("thematic_tags"));

//                        cnn.Close();


                $Qs[] = $question;

            }


        }

        $db = null;
        $DA = null;
//        GC.Collect();
        Return $Qs;

    }

    Public Function getQualData($Keywords) {

        $keywordsArray = explode(",", $Keywords);

        $SSearch = "SELECT DISTINCT(id), stats, pages FROM qualdata.transcript_data ";

        If (sizeof($keywordsArray)> 1) {

            $multiKeyword = "";

            ForEach ($keywordsArray as $keyword) { //keyword As String In keywordsArray
                $multiKeyword .= $keyword . " &";

            }

            $multiKeyword = rtrim($multiKeyword, "&");

            $SSearch.=(" WHERE text_index @@ to_tsquery('english','" . $multiKeyword . "')");
        } Else {
            $SSearch.=(" WHERE text_index @@ to_tsquery('english','" . $Keywords . "')");
        }



        // 'keywords = "wales"
        // '$SSearch.Append("SELECT DISTINCT(id), stats, pages ,ts_headline('english',rawtext, query) FROM qualdata.transcript_data, plainto_tsquery('english', '" . keywords . "') query WHERE query @@ to_tsvector(rawtext)");


        $db = new getDBConnections();
        $cnn = $db->getDBConnection("Qual_Data");
//        Npgsql.NpgsqlConnection.ClearAllPools();
//        $qDT = new DataTable();

//        Npgsql.NpgsqlDataAdapter
//        $DA = new DataAdapter($cnn, $SSearch);

//        $DA->Fill($qDT);

        $queryResult = pg_query($cnn, $SSearch);

        $DA = new DataAdapter();
        $rows = $DA->Read($queryResult);

        $results = array();

        ForEach($rows as $row) { // row As DataRow In qDT.Rows

            $id = Trim($row->id);

            $DCStr = "SELECT * FROM qualdata.dc_info WHERE identifier = '" . $id . "';";

//            $DR = new DataReader();
            $cnn = $db->getDBConnection("Qual_Data");


            $cmd = pg_query($cnn, $DCStr);
//                        If ($cnn.State = ConnectionState.Closed) {
//                            $cnn.Open();
//                        } Else {
//
//                            $cnn.Close();
//                            $cnn.Open();
//                        }

            If ( pg_num_rows($cmd) > 0) {

            $DR = pg_fetch_object($cmd);

//            } Else {



                $qData = new QualData();
                $qData->id = $id;
                $qData->creator = $DR->creator;
                $qData->pages = $row->pages;
                $qData->thematicgroup = $DR->thematic_group;
                $qData->qdate = $DR->date;
                $qData->title = $DR->title;

                $results[] = $qData;

            }

//            $cnn.Close();
        }


//                    $cnn.Dispose();

        $DA = null;
        $cnn = null;
        $db = null;
//                    GC.Collect();

        Return $results;


    }



}

?>
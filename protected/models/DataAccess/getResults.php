<?
class getResults {

//    Public $count;


    Public Function getQuestionnaireData($start, $limit, $keywords, $verify, $mappable) {

//ByVal start As Integer, ByVal limit As Integer, ByVal keywords As String, ByVal verify As Boolean, ByVal mappable As Boolean
        $dt = New DataTable();

        $keywords = "";
        $keywordsArray = explode(",", $keywords);


        $SSearch = "";
        //'keywords = "wales"

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

        If ($verify = True) {
            $SSearch.=(" LIMIT 1 ");
        } Else {
            $SSearch.=(" LIMIT 1000 ");

        }

        Log::toFile($SSearch);

        $db = new getDBConnections();
        $cnn = $db->getDBConnection("Survey_Data");


//Npgsql.NpgsqlCommand
        $cmd = pg_query($cnn, $SSearch);

        Log::toFile($SSearch);

        $DA = new DataAdapter($cmd);

        $id = 1;
//        $cnn.Open();
        $DA->Fill($dt);
//        $cnn.Close();



//        $results = new SortedDictionary(Of String, Object);
        $results = array();


//        $qtype = "";

//        For Each row As DataRow In dt.Rows
        foreach ($dt->rows as $row) {

//            @var DataRow $row

            $qtype = Trim($row->Item["Q_type"]);

            If ($qtype == "ROOT Question") {
                $rootQ = new rootQuestionDetails();
                $rootQ->QuestionID = Trim($row->Item["qid"]);
                $rootQ->QuestionNumber = Trim($row->Item["qNumber"]);
                $rootQ->QuestionText = Trim($row->Item["original_text"]);
                $rootQ->QuestionNotes = Trim($row->Item["q_notes"]);
                $rootQ->QuestionThematicGroup = Trim($row->Item["thematic_groups"]);
                $rootQ->QuestionThematicTag = Trim($row->Item["thematic_tags"]);
                $rootQ->QuestionType = "ROOT Question";
                $rootQ->Rank = $row->Item["rank"];
                $rootQ->DataSource = "WISERD DB";
                $rootQ->RecordID = $id;

                $survey_ID = Trim($row->Item["link_from"]);

                $survey_details = "Select * from Survey WHERE surveyid = (Select surveyid as query from survey_questions_link WHERE qid ='" . $survey_ID . "');";

                $surveycmd = pg_query($cnn, $survey_details);

                $surDRdr = new DataReader($surveycmd);

//                cnn.Open();
//                $surDRdr = surveycmd.ExecuteReader;

                If ($surDRdr->Read()) {
                    $rootQ->SurveyID = Trim($surDRdr->Item("surveyid"));
                    $rootQ->DataSource = $this::getDataSourceType($surDRdr->Item("surveyid"));
                    $rootQ->SurveyName = Trim($surDRdr->Item("survey_title"));
                    $rootQ->SurveyCollectionFrequency = Trim($surDRdr->Item("surveyfrequency"));
                    $rootQ->spatial = $surDRdr->Item("spatialdata");
                }

//                cnn.Close();

                $toFind = Trim($row->Item["qid"]);
                If (!array_key_exists($toFind, $results)) {
                    $results[$toFind] = $rootQ;
                }


                // 'Exit For
            } ElseIf ($qtype == "SINGLE Question") {

                $singleQ = new SingleQuestion();
                $singleQ->QuestionID = Trim($row->Item["qid"]);
                $singleQ->QuestionNumber = Trim($row->Item["qNumber"]);
                $singleQ->QuestionText = Trim($row->Item["original_text"]);
                $singleQ->QuestionNotes = Trim($row->Item["q_notes"]);
                $singleQ->QuestionThematicGroup = Trim($row->Item["thematic_groups"]);
                $singleQ->QuestionThematicTag = Trim($row->Item["thematic_tags"]);
                $singleQ->QuestionType = "Single Question";
                $singleQ->Rank = $row->Item["rank"];
                $singleQ->DataSource = "WISERD DB";
                $singleQ->RecordID = $id;


                $survey_ID = Trim($row->Item["link_from"]);

                $survey_details = "Select * from Survey WHERE surveyid = (Select surveyid as query from survey_questions_link WHERE qid ='" . $survey_ID . "');";

                $surveycmd = pg_query($cnn, $survey_details);

                $surDRdr = new DataReader($surveycmd);

//                cnn.Open();
//                $surDRdr = surveycmd.ExecuteReader;

                If ($surDRdr->Read()) {
                    $singleQ->SurveyID = Trim($surDRdr->Item("surveyid"));
                    $singleQ->DataSource = $this::getDataSourceType($surDRdr->Item("surveyid"));
                    $singleQ->SurveyName = Trim($surDRdr->Item("survey_title"));
                    $singleQ->SurveyCollectionFrequency = Trim($surDRdr->Item("surveyfrequency"));
                    $singleQ->spatial = $surDRdr->Item("spatialdata");
                }
//                cnn.Close();
                $toFind = Trim($row->Item["qid"]);
                If (!array_key_exists($toFind, $results)) {
                    $results[$toFind] = $singleQ;
                }

            } ElseIf ($qtype == "SUB Question") {

                $subQ = new subQuestionDetails();
                $subQ->QuestionID = Trim($row->Item["qid"]);
                $subQ->QuestionNumber = Trim($row->Item["qNumber"]);
                $subQ->QuestionText = Trim($row->Item["original_text"]);
                $subQ->QuestionNotes = Trim($row->Item["q_notes"]);
                $subQ->QuestionThematicGroup = Trim($row->Item["thematic_groups"]);
                $subQ->QuestionThematicTag = Trim($row->Item["thematic_tags"]);
                $subQ->QuestionType = "Sub Question Question";
                $subQ->Rank = $row->Item["rank"];
                $subQ->DataSource = "WISERD DB";
                $subQ->RecordID = $id;
                $subQ->RootQuestion = Trim($row->Item["subof"]);

                $survey_ID = Trim($row->Item["link_from"]);

                $survey_details = "Select * from Survey WHERE lower(surveyid) = lower((Select distinct(surveyid) from survey_questions_link WHERE qid = lower('" . $survey_ID . "')));";

                $surveycmd = pg_query($cnn, $survey_details);

                $surDRdr = new DataReader($surveycmd);

//                cnn.Open();
//                $surDRdr = surveycmd.ExecuteReader;

                If ($surDRdr->Read()) {
                    $subQ->SurveyID = Trim($surDRdr->Item("surveyid"));
                    $subQ->DataSource = $this::getDataSourceType($surDRdr->Item("surveyid"));
                    $subQ->SurveyName = Trim($surDRdr->Item("survey_title"));
                    $subQ->SurveyCollectionFrequency = Trim($surDRdr->Item("surveyfrequency"));
                    $subQ->spatial = $surDRdr->Item("spatialdata");
                } Else {

                }


//                cnn.Close();
//                If (Not results.ContainsKey(Trim($row->Item["qid"]))) {
//                    results.Add(Trim($row->Item["qid"]), subQ);
//                }
                $toFind = Trim($row->Item["qid"]);
                If (!array_key_exists($toFind, $results)) {
                    $results[$toFind] = $subQ;
                }


            } ElseIf ($qtype == "COMPOUND Question") {

                $compoundQ = new compoundQuestionDetails();
                $compoundQ->QuestionID = Trim($row->Item["qid"]);
                $compoundQ->QuestionNumber = Trim($row->Item["qNumber"]);
                $compoundQ->QuestionText = Trim($row->Item["original_text"]);
                $compoundQ->QuestionNotes = Trim($row->Item["q_notes"]);
                $compoundQ->QuestionThematicGroup = Trim($row->Item["thematic_groups"]);
                $compoundQ->QuestionThematicTag = Trim($row->Item["thematic_tags"]);
                $compoundQ->QuestionType = "Compound Question";
                $compoundQ->Rank = $row->Item["rank"];
                $compoundQ->DataSource = "WISERD DB";
                $compoundQ->RecordID = $id;

                $survey_ID = Trim($row->Item["link_from"]);

                $survey_details = "Select * from Survey WHERE surveyid = (Select surveyid as query from survey_questions_link WHERE qid ='" . $survey_ID . "');";

                $surveycmd = pg_query($cnn, $survey_details);

                $surDRdr = new DataReader($surveycmd);

//                cnn.Open();
//                $surDRdr = surveycmd.ExecuteReader;

                If ($surDRdr->Read()) {
                    $compoundQ->SurveyID = Trim($surDRdr->Item("surveyid"));
                    $compoundQ->DataSource = $this::getDataSourceType($surDRdr->Item("surveyid"));
                    $compoundQ->SurveyName = Trim($surDRdr->Item("survey_title"));
                    $compoundQ->SurveyCollectionFrequency = Trim($surDRdr->Item("surveyfrequency"));
                    $compoundQ->spatial = $surDRdr->Item("spatialdata");
                }
//                cnn.Close()
//                If (Not results.ContainsKey(Trim($row->Item["qid"]))) {
//                    results.Add(Trim($row->Item["qid"]), compoundQ);
//                }
                $toFind = Trim($row->Item["qid"]);
                If (!array_key_exists($toFind, $results)) {
                    $results[$toFind] = $compoundQ;
                }

            } ElseIf ($qtype == "SUB of SUB Question") {

                $subsubQ = new subQuestionDetails();
                $subsubQ->QuestionID = Trim($row->Item["qid"]);
                $subsubQ->QuestionNumber = Trim($row->Item["qNumber"]);
                $subsubQ->QuestionText = Trim($row->Item["original_text"]);
                $subsubQ->QuestionNotes = Trim($row->Item["q_notes"]);
                $subsubQ->QuestionThematicGroup = Trim($row->Item["thematic_groups"]);
                $subsubQ->QuestionThematicTag = Trim($row->Item["thematic_tags"]);
                $subsubQ->QuestionType = "Sub of A Sub Question";
                $subsubQ->Rank = $row->Item["rank"];
                $subsubQ->RecordID = $id;
                $subsubQ->RootQuestion = Trim($row->Item["subof"]);

                $survey_ID = Trim($row->Item["link_from"]);

                $survey_details = "Select * from Survey WHERE surveyid = (Select surveyid as query from survey_questions_link WHERE qid ='" . $survey_ID . "');";

                $surveycmd = pg_query($cnn, $survey_details);

                $surDRdr = new DataReader($surveycmd);

//                cnn.Open();
//                $surDRdr = surveycmd.ExecuteReader;

                If ($surDRdr->Read()) {
                    $subsubQ->SurveyID = Trim($surDRdr->Item("surveyid"));
                    $subsubQ->DataSource = $this::getDataSourceType($surDRdr->Item("surveyid"));
                    $subsubQ->SurveyName = Trim($surDRdr->Item("survey_title"));
                    $subsubQ->SurveyCollectionFrequency = Trim($surDRdr->Item("surveyfrequency"));
                    $subsubQ->spatial = $surDRdr->Item("spatialdata");
                }
//                cnn.Close();
//                If (Not results.ContainsKey(Trim($row->Item["qid")))) {
//                    results.Add(Trim($row->Item["qid")), subsubQ);
//                }
                $toFind = Trim($row->Item["qid"]);
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
        If ($mappable = True) {
//            For Each (result As KeyValuePair(Of String, Object) In results){
            ForEach ($results as $result) {
                If ($result->spatial = True) {
                    $finalResults[] = $result->Value;
                }
            }

        } Else {
//            ForEach (result As KeyValuePair(Of String, Object) In results) {
            ForEach ($results as $result) {
                $finalResults[] = $result->Value;

            }

        }


        Return $finalResults;



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

        $DT = new DataTable();

        $DA = new DataAdapter($cnn, $selStr);

        $DA->Fill($DT);

        ForEach ($DT->rows as $row) { // row As DataRow In DT.Rows
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

        $keywordsArray = explode($Keywords, ",");

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
        $qDT = new DataTable();

//        Npgsql.NpgsqlDataAdapter
        $DA = new DataAdapter($cnn, $SSearch);

        $DA->Fill($qDT);


        $results = array();

        ForEach($qDT->rows as $row) { // row As DataRow In qDT.Rows

            $id = Trim($row->Item["id"]);

            $DCStr = "SELECT * FROM qualdata.dc_info WHERE identifier = '" . $id . "';";

            $DR = new DataReader();
            $cnn = $db->getDBConnection("Qual_Data");


            $cmd = pg_query($cnn, $DCStr);
//                        If ($cnn.State = ConnectionState.Closed) {
//                            $cnn.Open();
//                        } Else {
//
//                            $cnn.Close();
//                            $cnn.Open();
//                        }

            $DR->ExecuteReader($cmd);


            If ( !$DR->Read()) {

            } Else {



                $qData = new QualData();
                $qData->id = $id;
                $qData->creator = $DR->Item("creator");
                $qData->pages = $row->Item["pages"];
                $qData->thematicgroup = $DR->Item("thematic_group");
                $qData->qdate = $DR->Item("date");
                $qData->title = $DR->Item("title");

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
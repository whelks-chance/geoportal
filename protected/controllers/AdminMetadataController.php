<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 14/08/13
 * Time: 15:11
 * To change this template use File | Settings | File Templates.
 */
class AdminMetadataController extends Controller
{

    function actiongetDCinfo() {

        $dcInfoQuery = "SELECT surveyid, identifier, title FROM dc_info;";

        $results = DataAdapter::DefaultExecuteAndRead($dcInfoQuery, "Survey_Data");

        Log::toFile(print_r($results, true));

        $addDCInfoArray = array();

        foreach ($results as $dcInfo) {
//            Log::toFile("Survey : " . print_r($surveyData, true));
            $dcInfoArray['sid'] = trim($dcInfo->surveyid);
            $dcInfoArray['wid'] = trim($dcInfo->identifier);
            $dcInfoArray['name'] = trim($dcInfo->title);
            $addDCInfoArray[] = $dcInfoArray;
        }

        $returnArray['DublinCoreId'] = $addDCInfoArray;

        echo json_encode($returnArray);
    }

    function actiongetSurveyQuestions() {
        $SID = "";
        if(isset($_POST['SID'])) {
            $SID = $_POST['SID'];
        }

        $dcInfoQuery = "SELECT FROM survey_questions_link, questions where surveyid='" . $SID . "';";

        $surveyQuestionQuery = "select q.qid, q.questionnumber from questions q join survey_questions_link surql on q.qid = surql.qid where surql.surveyid='" . $SID . "';";

        $results = DataAdapter::DefaultExecuteAndRead($surveyQuestionQuery, "Survey_Data");

        Log::toFile(print_r($results, true));

        $allQuestionArray = array();

        foreach ($results as $dcInfo) {
//            Log::toFile("Survey : " . print_r($surveyData, true));
            $questionArray['QuestionName'] = trim($dcInfo->questionnumber);
            $questionArray['QuestionID'] = trim($dcInfo->qid);
            $allQuestionArray[] = $questionArray;
        }

        $returnArray['questionData'] = $allQuestionArray;

        echo json_encode($returnArray);
    }

    function actionInsertDC(){
        Log::toFile(print_r($_POST, true));
        $Email = "";
        if(isset($_POST['txtEmail'])) {
            $Email = $_POST['txtEmail'];
        }

        $dbInsert = "INSERT INTO dc_info(
            identifier, title, creator, subject, description, publisher,
            contributor, date, type, format, source, language, relation,
            coverage, rights, user_id, created, updated)
    VALUES (?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?);";

        $results = DataAdapter::DefaultExecuteAndRead($dbInsert, "Survey_Data");
    }

}

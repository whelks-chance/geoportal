<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 16/04/13
 * Time: 22:02
 * To change this template use File | Settings | File Templates.
 */

class AdvancedSearchController extends Controller {

    public function actionAdvancedSearch() {
//        Log::toFile("advanced post vars : " . print_r($_POST, true));

        $Keywords = '';
        if(isset($_POST['keywords'])) {
            $Keywords = $_POST['keywords'];
        }
        if(isset($_POST['start'])) {
            $start = $_POST['start'];
        }
        if(isset($_POST['limit'])) {
            $limit = $_POST['limit'];
        }
        if(isset($_POST['Mappable'])) {
            $Mappable = $_POST['Mappable'];
        }
        $dateFrom = '';
        if(isset($_POST['dateFrom'])) {
            $dateFrom = $_POST['dateFrom'];
        } // => 13/05/2009
        $dateTo = '';
        if(isset($_POST['dateTo'])) {
            $dateTo = $_POST['dateTo'];
        } // => 04/12/2013
        $Survey = '';
        if(isset($_POST['SurveysId'])) {
            $Survey = $_POST['SurveysId'];
        } // => ffasdfd
        $Thematic = '';
        if(isset($_POST['Thematic'])) {
            $Thematic = $_POST['Thematic'];
        } // => fEFWefSFD
        $cbSurvey = '';
        if(isset($_POST['cbSurvey'])) {
            $cbSurvey = $_POST['cbSurvey'];
        } // => on
        $cbGreyData = '';
        if(isset($_POST['cbGreyData'])) {
            $cbGreyData = $_POST['cbGreyData'];
        } // => on
        $cbQual = '';
        if(isset($_POST['cbQual'])) {
            $cbQual = $_POST['cbQual'];
        } // => on
        $cbAdmin = '';
        if(isset($_POST['cbAdmin'])) {
            $cbAdmin = $_POST['cbAdmin'];
        } // => on
        $cbTitle = '';
        if(isset($_POST['cbTitle'])) {
            $cbTitle = $_POST['cbTitle'];
        } // => on
        $cbDescription = '';
        if(isset($_POST['cbDescription'])) {
            $cbDescription = $_POST['cbDescription'];
        } // => on
        $cbSrchDescription = '';
        if(isset($_POST['cbSrchDescription'])) {
            $cbSrchDescription = $_POST['cbSrchDescription'];
        } // => on

        $res = New getResults();

        $results = array();
        $qualResults = array();

        $resultsArray = array();
        $qualResultsArray = array();
        $count = 0;
        $qualCount = 0;

        if($cbSurvey == 'on') {
            $results = $res->getQuestionnaireData(0, 30, $Keywords, False, True);

            if ($Survey != '') {
                foreach ($results as $surveyResult) {
//                Log::toFile(print_r($surveyResult, true));

                    if ($surveyResult->SurveyName === $Survey) {
                        $resultsArray[] = $surveyResult;
                    }
                }
                $results = $resultsArray;
            }

            $resultsArray = array();

            // Filter out survey dates which don't lay between users requested dates

            if ($dateFrom != '' && $dateTo != '') {

                Log::toFile("user dates " . $dateFrom . " - " . $dateTo);

                $dateBegin = DateTime::createFromFormat('Y/m/d', $dateFrom);
                $dateEnd = DateTime::createFromFormat('Y/m/d', $dateTo);

//                Log::toFile($dateBegin . " " . $dateEnd->getTimestamp());

                foreach ($results as $surveyResult) {

//                    Log::toFile(print_r($surveyResult, true));

                    $surveyStart = DateTime::createFromFormat('Y-m-d', $surveyResult->surveyStart);
                    $surveyEnd = DateTime::createFromFormat('Y-m-d', $surveyResult->surveyEnd);

                    Log::toFile("survey dates " . $surveyResult->surveyStart . " - " . $surveyResult->surveyEnd);

//                    Log::toFile("res time " . $qualDate->getTimestamp());

                    if (($surveyStart >= $dateBegin && $surveyStart <= $dateEnd) || ($surveyEnd >= $dateBegin && $surveyEnd <= $dateEnd) )
                    {
                        Log::toFile('Dates are within the range.');
                        $resultsArray[] = $surveyResult;
                    }

                }
            }

            $count = count($resultsArray);
        }

        if($cbQual == 'on') {
            $qualResults = $res->getQualData($Keywords);

            if ($dateFrom != '' && $dateTo != '') {

                Log::toFile($dateFrom . " " . $dateTo);

                $dateBegin = DateTime::createFromFormat('Y/m/d', $dateFrom);
                $dateEnd = DateTime::createFromFormat('Y/m/d', $dateTo);

//                Log::toFile($dateBegin . " " . $dateEnd->getTimestamp());

                foreach ($qualResults as $qualResult) {

                    Log::toFile(print_r($qualResult, true));

                    $qualDate = DateTime::createFromFormat('Y-m-d', $qualResult->qdate);

//                    Log::toFile("res time " . $qualDate->getTimestamp());

                    if ($qualDate >= $dateBegin && $qualDate <= $dateEnd)
                    {
                        $qualResultsArray[] = $qualResult;
                    }

                }
            }

            $qualCount = count($qualResultsArray);
        }

//        $resultsset = New results();
//        $resultsset->totalCount = $count;
//        $resultsset->questions = json_encode($results);
//
//        $resultsset = New results();
//        $resultsset->totalCount = $count;
//        $resultsset->questions = json_encode($results);

        $str = '{"success":"' . true . '", "results":' . json_encode($resultsArray) . ', "totalCount":"' . $count . '", "qualTotalCount":"' . $qualCount . '", "qualResults":' . json_encode($qualResultsArray) . '}';

        echo $str;
    }

    public function actionAdvancedSearchMeta() {
        echo $this::getSurveyNames();
    }

    public function actiongetKeywordQuestions() {

        $Keyword = '';
        if(isset($_POST['Keyword'])) {
            $Keyword = $_POST['Keyword'];
        }

        $dataAdapter = new DataAdapter();

        //The Survey titles as value, and surveyID for the key
        $surveyNameQuery = "Select qid, literal_question_text from questions WHERE qtext_index @@ to_tsquery('english','" . $Keyword . "')";

        Log::toFile($surveyNameQuery);

        $surveyNameResults = $dataAdapter->DefaultExecuteAndRead($surveyNameQuery, "Survey_Data");

        Log::toFile(print_r($surveyNameResults, true));

        $surveyDataArray = array();

        foreach ($surveyNameResults as $surveyData) {
//            Log::toFile("Survey : " . print_r($surveyData, true));
            $surveyObject['id'] = trim($surveyData->qid);
            $surveyObject['name'] = trim($surveyData->literal_question_text);
            $surveyDataArray[] = $surveyObject;
        }

        $returnArray = array();
        $returnArray['questionData'] = $surveyDataArray;
        $returnArray['count'] = sizeof($surveyDataArray);

        echo json_encode($returnArray);
    }

    public function getSurveyNames() {

        $dataAdapter = new DataAdapter();

        //The Survey titles as value, and surveyID for the key
        $surveyNameQuery = "Select survey_title, surveyid From Survey";

        $surveyNameResults = $dataAdapter->DefaultExecuteAndRead($surveyNameQuery, "Survey_Data");

//        Log::toFile("Surveys : " . print_r($surveyNameResults, true));

        $surveyDataArray = array();

        foreach ($surveyNameResults as $surveyData) {
//            Log::toFile("Survey : " . print_r($surveyData, true));
            $surveyObject['SurveyID'] = trim($surveyData->surveyid);
            $surveyObject['SurveyName'] = trim($surveyData->survey_title);
            $surveyDataArray[] = $surveyObject;
        }

        //Thematic group name and ID
        $thematicQuery = "Select tgroupid, grouptitle From thematic_groups";

        $thematicQueryResults = $dataAdapter->DefaultExecuteAndRead($thematicQuery, "Survey_Data");

//        Log::toFile("Surveys : " . print_r($thematicQueryResults, true));

        $thematicDataArray = array();

        foreach ($thematicQueryResults as $thematicData) {
//            Log::toFile("Survey : " . print_r($thematicData, true));
            $thematicObject['tgroupid'] = trim($thematicData->tgroupid);
            $thematicObject['grouptitle'] = trim($thematicData->grouptitle);
            $thematicDataArray[] = $thematicObject;
        }

        $returnArray = array();
        $returnArray['success'] = true;
        $returnArray['surveyData'] = $surveyDataArray;
        $returnArray['thematicData'] = $thematicDataArray;

        return json_encode($returnArray);

    }

}
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
        Log::toFile("advanced post vars : " . print_r($_POST, true));

        $Keywords = '';
        if(isset($_POST['Keywords'])) {
            $Keywords = $_POST['Keywords'];
        }
        $dateFrom = '';
        if(isset($_POST['dateFrom'])) {
            $dateFrom = $_POST['dateFrom'];
        } // => 13/05/2009
        $dateTo = '';
        if(isset($_POST['dateTo'])) {
            $dateTo = $_POST['dateTo'];
        } // => 04/12/2013
        $Keywords = '';
        if(isset($_POST['Keywords'])) {
            $Keywords = $_POST['Keywords'];
        } // => word words wordy
        $Survey = '';
        if(isset($_POST['Survey'])) {
            $Survey = $_POST['Survey'];
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

        $resultsExsist = $res->getQuestionnaireData(0, 1, $Keywords, True, false);

        $qualResults = $res->getQualData($Keywords);

        $results = array();

        $results[] = $resultsExsist;
        $results[] = $qualResults;

        $str = '{"success":"' . true . '", "data":' . json_encode($results) . "}";

        echo $str;
    }

    public function actionAdvancedSearchMeta() {
        echo $this::getSurveyNames();
    }

    public function getSurveyNames() {

        $dataAdapter = new DataAdapter();

        $query = "Select survey_title, surveyid From Survey";

        $results = $dataAdapter->DefaultExecuteAndRead($query, "Survey_Data");

        Log::toFile("Surveys : " . print_r($results, true));

        $resultArray = array();

        foreach ($results as $result) {
            Log::toFile("Survey : " . print_r($result, true));
            $resultObject['SurveyID'] = trim($result->surveyid);
            $resultObject['SurveyName'] = trim($result->survey_title);
            $resultArray[] = $resultObject;
        }

        $returnArray = array();
        $returnArray['success'] = true;
        $returnArray['data'] = $resultArray;

        return json_encode($returnArray);

    }

}
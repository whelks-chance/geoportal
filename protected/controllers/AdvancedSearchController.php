<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 16/04/13
 * Time: 22:02
 * To change this template use File | Settings | File Templates.
 */

class AdvancedSearchController extends Controller {

    public function actionAdvancedSpatialSearch() {
//        Log::toFile("advanced post vars : " . print_r($_POST, true));

        $allSpatialResults = new SpatialSearch2();
        $Geography = '';
        if(isset($_POST['geography'])) {
            $Geography = $_POST['geography'];

            // Get standard spatial search results, then filter by user inputs
            $SD = New SpatialData();

            $spatial = $SD->SpatialSearch($Geography);
//            Log::toFile('spatial ' . print_r($spatial, true));
            $allSpatialResults = $spatial[0];
        }
        $Keywords = '';
        if(isset($_POST['keywords'])) {
            $Keywords = $_POST['keywords'];
        }
        $start = 0;
        if(isset($_POST['start'])) {
            $start = $_POST['start'];
        }
        $limit = 15;
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
        if(isset($_POST['ThematicID'])) {
            $Thematic = $_POST['ThematicID'];
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

        if(isset($_POST['type'])) {
            if($_POST['type'] === 'Qual') {
                $cbQual = 'on';
            }
            if($_POST['type'] === 'Quant') {
                $cbSurvey = 'on';
            }
        }

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


        $results = array();
        $qualResults = array();

        $count = 0;
        $qualCount = 0;

        if($cbSurvey == 'on') {

            if ($Geography === '') {

                $results = Yii::app()->session["spatialResults"];
                $count = Yii::app()->session["spatialResCount"];

            } else {

                $results = $allSpatialResults->quantData;
//                Log::toFile('quant ' . print_r($results, true));

                // Temp array for filtered results
                $resultsArray = array();

                if ($Thematic != '') {

                    // For each result, add those with desired theme to temp array

                    foreach ($results as $surveyResult) {
                        Log::toFile(print_r($surveyResult, true));

                        if ($surveyResult->QuestionThematicGroup === $Thematic) {
                            $resultsArray[] = $surveyResult;
                        }
                    }

                    // Copy temp array to results array
                    $results = $resultsArray;
                }

                // Clear temp array
                $resultsArray = array();

                if ($Survey != '') {
                    foreach ($results as $surveyResult) {
//                Log::toFile(print_r($surveyResult, true));

                        if ($surveyResult->SurveyName === $Survey) {
                            $resultsArray[] = $surveyResult;
                        }
                    }
                    // Copy temp array to results
                    $results = $resultsArray;
                }

                //Clear temp array
                $resultsArray = array();

                // Filter out survey dates which don't lay between users requested dates

                if ($dateFrom != '' && $dateTo != '') {

                    Log::toFile("user dates " . $dateFrom . " - " . $dateTo);

                    // Create date objects from users input field values
                    $dateBegin = DateTime::createFromFormat('Y/m/d', $dateFrom);
                    $dateEnd = DateTime::createFromFormat('Y/m/d', $dateTo);

                    foreach ($results as $surveyResult) {

//                    Log::toFile(print_r($surveyResult, true));

                        // Create date objects from database survey record

                        $surveyStart = DateTime::createFromFormat('Y-m-d', $surveyResult->surveyStart);
                        $surveyEnd = DateTime::createFromFormat('Y-m-d', $surveyResult->surveyEnd);

//                    Log::toFile("survey dates " . $surveyResult->surveyStart . " - " . $surveyResult->surveyEnd);

                        // If Survey start date is between user dates,
                        // or if survey end date is between user dates,
                        // then the survey overlaps the desired time period,
                        // so add it to the temp array

                        if (($surveyStart >= $dateBegin && $surveyStart <= $dateEnd) || ($surveyEnd >= $dateBegin && $surveyEnd <= $dateEnd) )
                        {
                            Log::toFile('Dates are within the range.');
                            $resultsArray[] = $surveyResult;
                        }

                    }
                    // copy temp array to results
                    $results = $resultsArray;
                }

                $count = count($results);

                Yii::app()->session["spatialResults"] = $results;
                Yii::app()->session["spatialResCount"] = $count;

            }

            $pageResults = array();
            $cnt = $start;
            $cnt_end = $cnt + $limit;
            $keys = array_keys($results);

            //this uses the complete set of results instead of the filtered stuff, so is very wrong
            while ( $cnt < $cnt_end && $cnt < $count) {
                $pageResults[] = ($results[$keys[$cnt]]);
                $cnt ++;
            }

            $results = $pageResults;
        }

        // Qual searches

        if($cbQual == 'on') {

            if ($Keywords == '') {

                $qualResults = Yii::app()->session["spatialQualResults"];
                $qualCount = Yii::app()->session["spatialQualResCount"];

            } else {

                $qualResults = $allSpatialResults->qualData;

                // Temp array for filtered results
                $resultsArray = array();

                if ($Thematic != '') {

                    // For each result, add those with desired theme to temp array
                    foreach ($qualResults as $qualResult) {
//                    Log::toFile(print_r($surveyResult, true));

                        if ($qualResult->thematicgroup === $Thematic) {
                            $resultsArray[] = $qualResult;
                        }
                    }

                    // Copy temp array to results array
                    $qualResults = $resultsArray;
                }

                // Temp array for date matching of qual results
                $qualResultsArray = array();
                if ($dateFrom != '' && $dateTo != '') {

                    // Create date objects from users input field values
                    $dateBegin = DateTime::createFromFormat('Y/m/d', $dateFrom);
                    $dateEnd = DateTime::createFromFormat('Y/m/d', $dateTo);

                    foreach ($qualResults as $qualResult) {

                        Log::toFile(print_r($qualResult, true));

                        // Create date object for db qual result
                        // check if it lays between user specified dates
                        $qualDate = DateTime::createFromFormat('Y-m-d', $qualResult->qdate);

                        if ($qualDate >= $dateBegin && $qualDate <= $dateEnd)
                        {
                            $qualResultsArray[] = $qualResult;
                        }

                    }
                    // Copy temp array to qual results
                    $qualResults = $qualResultsArray;
                }

                $qualCount = count($qualResults);

                Yii::app()->session["spatialQualResults"] = $qualResults;
                Yii::app()->session["spatialQualResCount"] = $qualCount;
            }

            $pageResults = array();
            $cnt = $start;
            $cnt_end = $cnt + $limit;
            $keys = array_keys($qualResults);

            //this uses the complete set of results instead of the filtered stuff, so is very wrong
            while ( $cnt < $cnt_end && $cnt < $qualCount) {
                $pageResults[] = ($qualResults[$keys[$cnt]]);
                $cnt ++;
            }

            $qualResults = $pageResults;
        }


        $str = '{"success":"' . true . '", "quantData":' . json_encode($results) . ', "totalCount":"' . $count . '", "qualTotalCount":"' . $qualCount . '", "qualData":' . json_encode($qualResults) . '}';

        echo $str;
    }


    public function actionAdvancedSearch() {
//        Log::toFile("advanced post vars : " . print_r($_POST, true));

        $Keywords = '';
        if(isset($_POST['keywords'])) {
            $Keywords = $_POST['keywords'];
        }
        $start = 0;
        if(isset($_POST['start'])) {
            $start = $_POST['start'];
        }
        $limit = 15;
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
        if(isset($_POST['ThematicID'])) {
            $Thematic = $_POST['ThematicID'];
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

        if(isset($_POST['searchType'])) {
            if($_POST['searchType'] === 'qual') {
                $cbQual = 'on';
            }
            if($_POST['searchType'] === 'survey') {
                $cbSurvey = 'on';
            }
        }

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

        $count = 0;
        $qualCount = 0;

        if($cbSurvey == 'on') {

            if ($Keywords == '') {

                $results = Yii::app()->session["results"];
                $count = Yii::app()->session["resCount"];

            } else {

                // Get standard simple search results, then filter by user inputs
                $results = $res->getQuestionnaireData(0, 15, $Keywords, False, False);

                // Temp array for filtered results
                $resultsArray = array();

                if ($Thematic != '') {

                    // For each result, add those with desired theme to temp array

                    foreach ($results as $surveyResult) {
                        Log::toFile(print_r($surveyResult, true));

                        if ($surveyResult->QuestionThematicGroup === $Thematic) {
                            $resultsArray[] = $surveyResult;
                        }
                    }

                    // Copy temp array to results array
                    $results = $resultsArray;
                }

                // Clear temp array
                $resultsArray = array();

                if ($Survey != '') {
                    foreach ($results as $surveyResult) {
//                Log::toFile(print_r($surveyResult, true));

                        if ($surveyResult->SurveyName === $Survey) {
                            $resultsArray[] = $surveyResult;
                        }
                    }
                    // Copy temp array to results
                    $results = $resultsArray;
                }

                //Clear temp array
                $resultsArray = array();

                // Filter out survey dates which don't lay between users requested dates

                if ($dateFrom != '' && $dateTo != '') {

                    Log::toFile("user dates " . $dateFrom . " - " . $dateTo);

                    // Create date objects from users input field values
                    $dateBegin = DateTime::createFromFormat('Y/m/d', $dateFrom);
                    $dateEnd = DateTime::createFromFormat('Y/m/d', $dateTo);

                    foreach ($results as $surveyResult) {

//                    Log::toFile(print_r($surveyResult, true));

                        // Create date objects from database survey record

                        $surveyStart = DateTime::createFromFormat('Y-m-d', $surveyResult->surveyStart);
                        $surveyEnd = DateTime::createFromFormat('Y-m-d', $surveyResult->surveyEnd);

//                    Log::toFile("survey dates " . $surveyResult->surveyStart . " - " . $surveyResult->surveyEnd);

                        // If Survey start date is between user dates,
                        // or if survey end date is between user dates,
                        // then the survey overlaps the desired time period,
                        // so add it to the temp array

                        if (($surveyStart >= $dateBegin && $surveyStart <= $dateEnd) || ($surveyEnd >= $dateBegin && $surveyEnd <= $dateEnd) )
                        {
                            Log::toFile('Dates are within the range.');
                            $resultsArray[] = $surveyResult;
                        }

                    }
                    // copy temp array to results
                    $results = $resultsArray;
                }

                $count = count($results);

                Yii::app()->session["results"] = $results;
                Yii::app()->session["resCount"] = $count;
            }

            $pageResults = array();
            $cnt = $start;
            $cnt_end = $cnt + $limit;
            while( $cnt <= $cnt_end && $cnt < sizeof($results)){
                $pageResults[] = ($results[$cnt]);
                $cnt += 1;
            }

            $results = $pageResults;
        }

        // Qual searches

        if($cbQual == 'on') {

            if ($Keywords == '') {

                $qualResults = Yii::app()->session["qualResults"];
                $qualCount = Yii::app()->session["qualResCount"];

            } else {


                $qualResults = $res->getQualData($Keywords);

                // Temp array for filtered results
                $resultsArray = array();

                if ($Thematic != '') {

                    // For each result, add those with desired theme to temp array
                    foreach ($qualResults as $qualResult) {
//                    Log::toFile(print_r($surveyResult, true));

                        if ($qualResult->thematicgroup === $Thematic) {
                            $resultsArray[] = $qualResult;
                        }
                    }

                    // Copy temp array to results array
                    $qualResults = $resultsArray;
                }

                // Temp array for date matching of qual results
                $qualResultsArray = array();
                if ($dateFrom != '' && $dateTo != '') {

                    // Create date objects from users input field values
                    $dateBegin = DateTime::createFromFormat('Y/m/d', $dateFrom);
                    $dateEnd = DateTime::createFromFormat('Y/m/d', $dateTo);

                    foreach ($qualResults as $qualResult) {

                        Log::toFile(print_r($qualResult, true));

                        // Create date object for db qual result
                        // check if it lays between user specified dates
                        $qualDate = DateTime::createFromFormat('Y-m-d', $qualResult->qdate);

                        if ($qualDate >= $dateBegin && $qualDate <= $dateEnd)
                        {
                            $qualResultsArray[] = $qualResult;
                        }

                    }
                    // Copy temp array to qual results
                    $qualResults = $qualResultsArray;
                }

                $qualCount = count($qualResults);

                Yii::app()->session["qualResults"] = $qualResults;
                Yii::app()->session["qualResCount"] = $qualCount;
            }

            $pageResults = array();
            $cnt = $start;
            $cnt_end = $cnt + $limit;
            while( $cnt <= $cnt_end && $cnt < sizeof($qualResults)){
                $pageResults[] = ($qualResults[$cnt]);
                $cnt += 1;
            }

            $qualResults = $pageResults;
        }


        $str = '{"success":"' . true . '", "results":' . json_encode($results) . ', "totalCount":"' . $count . '", "qualTotalCount":"' . $qualCount . '", "qualResults":' . json_encode($qualResults) . '}';

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
        $thematicQuery = "Select tgroupid, grouptitle, groupdescription From thematic_groups";

        $thematicQueryResults = $dataAdapter->DefaultExecuteAndRead($thematicQuery, "Survey_Data");

//        Log::toFile("Surveys : " . print_r($thematicQueryResults, true));

        $thematicDataArray = array();

        foreach ($thematicQueryResults as $thematicData) {
//            Log::toFile("Survey : " . print_r($thematicData, true));
            $thematicObject['tgroupid'] = trim($thematicData->tgroupid);
            $thematicObject['grouptitle'] = trim($thematicData->grouptitle);
            $thematicObject['groupdescription'] = trim($thematicData->groupdescription);
            $thematicDataArray[] = $thematicObject;
        }

        $returnArray = array();
        $returnArray['success'] = true;
        $returnArray['surveyData'] = $surveyDataArray;
        $returnArray['thematicData'] = $thematicDataArray;

        return json_encode($returnArray);

    }

}
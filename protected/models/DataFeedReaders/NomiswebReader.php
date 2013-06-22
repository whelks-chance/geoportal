<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 22/06/13
 * Time: 11:35
 * To change this template use File | Settings | File Templates.
 */

class NomiswebReader implements FeedReaderInterface{


    public function keywordSearch($keyword)
    {
        $url = "http://www.nomisweb.co.uk/api/v01/dataset/def.sdmx.json?search=*wiserd_dataportal_replace_me*";

        $urlArray = explode('wiserd_dataportal_replace_me', $url);

        Log::toFile(print_r($urlArray, true));

        $searchURL = $urlArray[0] . $keyword;

        if(sizeof($urlArray) > 1) {
            $searchURL .= $urlArray[1];
        }

        Log::toFile(print_r($searchURL, true));

        $output = RemoteDataController::curlURL($searchURL);

        Log::toFile(print_r($output, true));

        $decoded = json_decode($output, false);

        Log::toFile(print_r($decoded, true));

        $allFound = array();

        if($decoded->structure->keyfamilies != null){

            foreach($decoded->structure->keyfamilies->keyfamily as $family) {
                $foundWord = array();
                $foundWord["id"] = $family->id;
                $foundWord["name"] = $family->name->value;

                $dataAdapter = new DataAdapter();
                $findQuery = "select id, wiserd_id from question_link where remote_id='" . $family->id . "';";

                $results = $dataAdapter->DefaultExecuteAndRead($findQuery, "Survey_Data");

                $foundWord["wiserd"] = "";
                $foundWord["wiserd_survey"] = "";
                forEach($results as $DR) {
                    $foundWord["wiserd"] = $DR->wiserd_id;
                    $survey_details = "Select * from Survey WHERE surveyid = (Select surveyid as query from survey_questions_link WHERE qid ='" . strtolower($DR->wiserd_id) . "');";

                    $results = $dataAdapter->DefaultExecuteAndRead($survey_details, "Survey_Data");

                    if(sizeof($results) > 0 ){
                        $foundWord["wiserd_survey"] = $results[0]->surveyid;
                    }
                }

                $allFound[] = $foundWord;
            }
        }

        return json_encode($allFound);
    }

    public function getRemoteDataset($dataset)
    {
        $url = "https://www.nomisweb.co.uk/api/v01/dataset/" . $dataset . "/geography.def.sdmx.json";

        $output = RemoteDataController::curlURL($url);

        $decoded = json_decode($output, false);

        $regions = array();

        if($decoded->structure->codelists != null){

            foreach($decoded->structure->codelists->codelist as $codelist) {
                foreach($codelist->code as $code) {

                    $regionName = $code->description->value;

                    $foundRegion = array();
                    $foundRegion["id"] = $code->value;
                    $foundRegion["name"] = $regionName;

                    $regions[] = $foundRegion;
                }
            }
        }

        //variables info

        $url = "https://www.nomisweb.co.uk/api/v01/dataset/" . $dataset . "/measures.def.sdmx.json";

        $output = RemoteDataController::curlURL($url);

        $decoded = json_decode($output, false);

        $measures = array();

        if($decoded->structure->codelists != null){

            foreach($decoded->structure->codelists->codelist as $codelist) {
                foreach($codelist->code as $code) {

                    $measureName = $code->description->value;

                    $foundMeasure = array();
                    $foundMeasure["id"] = $code->value;
                    $foundMeasure["name"] = $measureName;

                    $measures[] = $foundMeasure;
                }
            }
        }

        $allFound['regions'] = $regions;
        $allFound['measures'] = $measures;

        return json_encode($allFound);
    }
}
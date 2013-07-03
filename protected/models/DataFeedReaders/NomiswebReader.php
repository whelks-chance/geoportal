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

        $tempKeys = array();
        foreach( $allFound as $toCheck) {
            $tempKeys[$toCheck['id']] = $toCheck;
        }

        $allFound = array();
        foreach( $tempKeys as $toAdd ) {
            $allFound[] = $toAdd;
        }

        return json_encode($allFound);
    }



    public function getRegionBreakdown($datasetID, $regionID)
    {
        $url = "https://www.nomisweb.co.uk/api/v01/dataset/" . $datasetID . "/geography/" . $regionID . ".def.sdmx.json";

        $output = RemoteDataController::curlURL($url);

        $decoded = json_decode($output, false);

        Log::toFile($url);
        Log::toFile(print_r($decoded, true));

        $allFound = array();

        if($decoded->structure->codelists != null){

            foreach($decoded->structure->codelists->codelist as $codelist) {
                foreach($codelist->code as $code) {

//                    $typeID = "";
//
//                    foreach($code->annotations->annotation as $region) {
//
//                        if ( $region->annotationtitle == "TypeCode") {
//                            $typeID = $region->annotationtext;
//                        }
//
//                    }

                    $regionName = $code->description->value;

                    $foundRegion = array();
                    $foundRegion["id"] = $code->value;
                    $foundRegion["name"] = $regionName;

                    $allFound[] = $foundRegion;
                }
            }
        }
        return json_encode($allFound);
    }

    public function getRemoteVariables($datasetID)
    {
        $url = "https://www.nomisweb.co.uk/api/v01/dataset/" . $datasetID . "/measures.def.sdmx.json";

        $output = RemoteDataController::curlURL($url);

        $decoded = json_decode($output, false);

        Log::toFile(print_r($url, true));

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

        $allFound['measures'] = $measures;

        return json_encode($allFound);
    }

    public function getRemoteGeographies($datasetID, $topGeography)
    {
        $url = "https://www.nomisweb.co.uk/api/v01/dataset/" . $datasetID . "/geography.def.sdmx.json";

        Log::toFile($url);

        $output = RemoteDataController::curlURL($url);

        $decoded = json_decode($output, false);

        Log::toFile(print_r($decoded, true));

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

        $allFound['regions'] = $regions;

        return json_encode($allFound);
    }

    public function getRemoteDataset($datasetID, $boundaryID, $measuresID)
    {
        $url = "https://www.nomisweb.co.uk/api/v01/dataset/" . $datasetID . ".data.json?";
        $url .= "geography=" . $boundaryID;
        $url .= "&&measures" . $measuresID;

        $output = RemoteDataController::curlURL($url);

        $decoded = json_decode($output, false);

        $dataSets = array();

        if($decoded->obs != null){

            foreach($decoded->obs as $ob) {
                if( $ob->measures->value == $measuresID) {
                    $foundData = array();

                    $foundData['value'] = $ob->obs_value->value;
                    $foundData['description'] = $ob->obs_value->description;

                    $dataSets[] = $foundData;
                }
            }

        }

        $data['length'] = sizeof($dataSets);
        $data['data'] = $dataSets;

        return json_encode($data);
    }
}
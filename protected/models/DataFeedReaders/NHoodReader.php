<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 22/06/13
 * Time: 11:48
 * To change this template use File | Settings | File Templates.
 */

class NHoodReader implements FeedReaderInterface {

    public function keywordSearch($keyword)
    {
        $url = "http://www.neighbourhood.statistics.gov.uk/NDE2/Disco/FindDatasets?Metadata=wiserd_dataportal_replace_me";

        $urlArray = explode('wiserd_dataportal_replace_me', $url);

//        Log::toFile(print_r($urlArray, true));

        $searchURL = $urlArray[0] . $keyword;

        if(sizeof($urlArray) > 1) {
            $searchURL .= $urlArray[1];
        }

//        Log::toFile(print_r($searchURL, true));

        $output = RemoteDataController::curlURL($searchURL);

//        Log::toFile(print_r($output, true));

        $nhoodXML = simplexml_load_string($output);

//        Log::toFile(print_r($nhoodXML, true));

        $allFound = array();

        if($nhoodXML->SubjectsWithMatchingDSFamilies->SubjectWithMatchingDSFamilies != null){

            foreach($nhoodXML->SubjectsWithMatchingDSFamilies->SubjectWithMatchingDSFamilies as $subject) {

                foreach($subject->MatchingDSFamilies as $familys) {

                    foreach($familys->DSFamily as $family) {
                        $foundWord = array();

                        $id = (string) $family->DSFamilyId;

                        $name = (string) $family->Name;

                        $foundWord["id"] = $id ;

                        $foundWord["name"] = $name;

                        $dataAdapter = new DataAdapter();
                        $findQuery = "select id, wiserd_id from question_link where remote_id='" . $id . "';";

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

    public function getRemoteDataset($datasetID, $boundaryID, $measuresID)
    {

        $wsdlDoc = "http://neighbourhood.statistics.gov.uk/NDE2/Deli?wsdl";

        $soapclient = new SoapClient($wsdlDoc);

        $functions = $soapclient->__getFunctions();


        Log::toFile(print_r($functions, true));
//
//        $result = $soapclient->__soapCall("getTables", null);
//
//        Log::toFile(print_r($result, true));

    }

    public function getRegionBreakdown($datasetId, $regionId)
    {
        return NeSS::getNeSSareas();
        // TODO: Match this to data areas
    }

    public function getRemoteVariables($datasetID)
    {
        $url = "http://www.neighbourhood.statistics.gov.uk/NDE2/Disco/GetVariables?DSFamilyId=" . $datasetID;

        $output = RemoteDataController::curlURL($url);

        $nhoodXML = simplexml_load_string($output);

        $measures = array();

        if($nhoodXML->VarFamilies->VarFamily != null){

            foreach($nhoodXML->VarFamilies->VarFamily as $variableType) {

                $foundMeasure = array();
                $foundMeasure["id"] = (string) $variableType->VarFamilyId;

                $displayName = (string) $variableType->Name;
                $displayUnit = (string) $variableType->MeasurementUnit->Name;

                $foundMeasure["name"] = $displayName . " - " . $displayUnit;

                $measures[] = $foundMeasure;

            }
        }
        $allFound['measures'] = $measures;

        return json_encode($allFound);

    }

    public function getRemoteGeographies($datasetID, $topGeography)
    {
        if ($topGeography === "") {
            $topGeography = "6274990"; //England and Wales from NeSS, good luck in future dealing with that mess
        }

        $url = "http://www.neighbourhood.statistics.gov.uk/NDE2/Disco/GetAreaChildren?AreaId=" . $topGeography;

        $output = RemoteDataController::curlURL($url);

        $regionXML = simplexml_load_string($output);

        $regions = array();

        if($regionXML->Areas != null){

            foreach($regionXML->Areas->Area as $area) {

                $foundMeasure = array();
                $foundMeasure["id"] = (string) $area->AreaId;

//                $displayName = (string) $variableType->Name;
//                $displayUnit = (string) $variableType->MeasurementUnit->Name;

                $foundMeasure["name"] = (string) $area->Name;

                $regions[] = $foundMeasure;

            }
        }

        $allFound['regions'] = $regions;

        return json_encode($allFound);
    }
}
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
                            $survey_details = "Select * from Survey WHERE surveyid = (Select surveyid as query from survey_questions_link WHERE qid ='" . strtolower($id) . "');";

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

        Log::toFile(print_r($allFound, true));

        return json_encode($allFound);
    }

    public function getRemoteDataset($dataset)
    {
        return "";
        // TODO: Implement getRemoteDataset() method.
    }
}
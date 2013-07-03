<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 28/04/13
 * Time: 15:23
 * To change this template use File | Settings | File Templates.
 */

class RemoteDataController extends Controller {

    public static function curlURL($url) {
        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $url);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);

        return $output;
    }

    public function actiongetRemoteSources() {
        $remoteSources = array();

        $sourceNomis = array();
        $sourceNomis['name'] = "nomisweb";
        $sourceNomis['url'] = "http://www.nomisweb.co.uk/api/v01/";
//        $sourceNomis['wordsearch'] = "http://www.nomisweb.co.uk/api/v01/dataset/def.sdmx.json?search=*wiserd_dataportal_replace_me*";

        $remoteSources[] = $sourceNomis;

        $sourceNHood = array();
        $sourceNHood['name'] = "neighbourhood.statistics";
        $sourceNHood['url'] = "http://www.neighbourhood.statistics.gov.uk/";
//        $sourceNHood['wordsearch'] = "http://www.neighbourhood.statistics.gov.uk/NDE2/Disco/FindDatasets?Metadata=wiserd_dataportal_replace_me";

        $remoteSources[] = $sourceNHood;

        echo json_encode($remoteSources);
    }

    public function actiongetRemoteData() {
        $boundaryID = $_POST['BoundaryID'];
        $datasetID = $_POST['DatasetID'];
        $measuresID = $_POST['MeasuresID'];

        $feedReader = "";
        if(isset($_POST['apiName'])) {
            $feedReader = $_POST['apiName'];
        }

        $jsonEncodedResults = "";

        if ($feedReader === "nomisweb") {
            $nomisReader = new NomiswebReader();

            $jsonEncodedResults = $nomisReader->getRemoteDataset($datasetID, $boundaryID, $measuresID);
        }

        if ($feedReader === "neighbourhood.statistics") {
            $nhoodReader = new NHoodReader();

            $jsonEncodedResults = $nhoodReader->getRemoteDataset($datasetID, $boundaryID, $measuresID);
        }

        echo $jsonEncodedResults;

    }

    public function actiongetRemoteDatasetGeographies() {

        $regionID = "";
        if(isset($_POST['RegionID'])) {
            $regionID = $_POST['RegionID'];
        }

        $datasetID = "";
        if(isset($_POST['DatasetID'])) {
            $datasetID = $_POST['DatasetID'];
        }

        $feedReader = "";
        if(isset($_POST['apiName'])) {
            $feedReader = $_POST['apiName'];
        }

        $jsonEncodedResults = "";

        if ($feedReader === "nomisweb") {
            $nomisReader = new NomiswebReader();

            $jsonEncodedResults = $nomisReader->getRemoteGeographies($datasetID, $regionID);
        }

        if ($feedReader === "neighbourhood.statistics") {
            $nhoodReader = new NHoodReader();

            $jsonEncodedResults = $nhoodReader->getRemoteGeographies($datasetID, $regionID);
        }

        echo $jsonEncodedResults;
    }

    public function actiongetRegionBreakdown() {
        //        https://www.nomisweb.co.uk/api/v01/dataset/NM_621_1.data.csv?geography=2092957700
//        https://www.nomisweb.co.uk/api/v01/dataset/NM_621_1/geography/2092957700.def.sdmx.json
        $regionID = $_POST['RegionID'];
        $datasetID = $_POST['DatasetID'];

        $feedReader = "";
        if(isset($_POST['apiName'])) {
            $feedReader = $_POST['apiName'];
        }

        $jsonEncodedResults = "";

        if ($feedReader === "nomisweb") {
            $nomisReader = new NomiswebReader();

            $jsonEncodedResults = $nomisReader->getRegionBreakdown($datasetID, $regionID);
        }

        if ($feedReader === "neighbourhood.statistics") {
            $nhoodReader = new NHoodReader();

            $jsonEncodedResults = $nhoodReader->getRegionBreakdown($datasetID, $regionID);
        }

        echo $jsonEncodedResults;
    }

    public function actiongetRemoteVariables() {

        $dataset = $_POST['DatasetID'];

        $feedReader = "";
        if(isset($_POST['apiName'])) {
            $feedReader = $_POST['apiName'];
        }

        $jsonEncodedResults = "";

        if ($feedReader === "nomisweb") {
            $nomisReader = new NomiswebReader();

            $jsonEncodedResults = $nomisReader->getRemoteVariables($dataset);
        }

        if ($feedReader === "neighbourhood.statistics") {
            $nhoodReader = new NHoodReader();

            $jsonEncodedResults = $nhoodReader->getRemoteVariables($dataset);
        }

        echo $jsonEncodedResults;

    }



    public function actionlinkRemoteQuestion() {
        $wiserdID = '';
        if(isset($_POST['wiserdID'])) {
            $wiserdID = trim($_POST['wiserdID']);
        }
        $remoteID = '';
        if(isset($_POST['remoteID'])) {
            $remoteID = trim($_POST['remoteID']);
        }
        $remoteAPI = 'nomisweb';
        if(isset($_POST['remoteAPI'])) {
            $remoteAPI = $_POST['remoteAPI'];
        }

        $dataAdapter = new DataAdapter();

        //The Survey titles as value, and surveyID for the key
        $surveyNameQuery = 'insert into question_link(id, wiserd_id, remote_id, remote_api)';
        $surveyNameQuery .= " values (default, '" . $wiserdID . "', '";
        $surveyNameQuery .= $remoteID . "', '";
        $surveyNameQuery .= $remoteAPI . "');";

//        Log::toFile($surveyNameQuery);

        $surveyNameResults = $dataAdapter->DefaultExecuteAndRead($surveyNameQuery, "Survey_Data");

//        Log::toFile(print_r($surveyNameResults, true));

        $returnArray['success'] = true;

        echo json_encode($returnArray);

    }

    public function actiondoKeywordSearch() {

        $keyword = $_POST['Keyword'];

        $feedReader = "";
        if(isset($_POST['apiName'])) {
            $feedReader = $_POST['apiName'];
        }

        $jsonEncodedResults = "";

        if ($feedReader === "nomisweb") {
            $nomisReader = new NomiswebReader();

             $jsonEncodedResults = $nomisReader->keywordSearch($keyword);
        }

        if ($feedReader === "neighbourhood.statistics") {
            $nhoodReader = new NHoodReader();

            $jsonEncodedResults = $nhoodReader->keywordSearch($keyword);
        }

        echo $jsonEncodedResults;
    }

    public function actionfindQuestionLinks() {
        $remoteID = '';
        if(isset($_POST['remoteID'])) {
            $remoteID = trim($_POST['remoteID']);
        }

        $dataAdapter = new DataAdapter();

        $findQuery = 'select id, wiserd_id from question_link where remote_id="' . $remoteID . '";';

        $questionResults = $dataAdapter->DefaultExecuteAndRead($findQuery, "Survey_Data");

        echo json_encode($questionResults);
    }
}
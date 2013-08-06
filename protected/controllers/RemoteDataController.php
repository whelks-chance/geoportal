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

    /**
     * @return FeedReaderInterface[]
     */
    public static function getFeedReaderClasses() {
        $modelPath = Yii::getPathOfAlias('application.models');

        Yii::setPathOfAlias('application.models.DataFeedReaders', $modelPath . '/DataFeedReaders');

        $declaredClasses = get_declared_classes();
        foreach (glob(Yii::getPathOfAlias('application.models.DataFeedReaders') . "/*.php") as $controller){
            $class = basename($controller, ".php");
//            Log::toFile($class . ' ' . $controller);
            if (!in_array($class, $declaredClasses)) {
                Yii::import("application.models.DataFeedReaders." . $class, true);
            }
        }

        $readers = array();
        foreach(get_declared_classes() as $className) {
            if( in_array('FeedReaderInterface', class_implements($className)) ) {
                $readers[] = new $className();
            }
        }
        return $readers;
    }

    /**
     * @param $name
     * @return FeedReaderInterface
     */

    public static function getFeedReaderClassByName( $name ) {
        $feedReaderClasses = RemoteDataController::getFeedReaderClasses();

        foreach($feedReaderClasses as $class ) {

//            $reflectionMethod = new ReflectionMethod($className, 'getFeedName');
//            $feedName = $reflectionMethod->invoke(new $className());

            $feedName = $class->getFeedName();

            if ($feedName === $name ) {
                return $class;
            }
        }
        return new Exception("No Feed Reader Found with name " . $name);
    }

    public function actiongetRemoteSources() {
//        $remoteSources = array();

//        $sourceNomis = array();
//        $sourceNomis['name'] = "nomisweb";
//        $sourceNomis['url'] = "http://www.nomisweb.co.uk/api/v01/";
////        $sourceNomis['wordsearch'] = "http://www.nomisweb.co.uk/api/v01/dataset/def.sdmx.json?search=*wiserd_dataportal_replace_me*";
//
//        $remoteSources[] = $sourceNomis;
//
//        $sourceNHood = array();
//        $sourceNHood['name'] = "neighbourhood.statistics";
//        $sourceNHood['url'] = "http://www.neighbourhood.statistics.gov.uk/";
////        $sourceNHood['wordsearch'] = "http://www.neighbourhood.statistics.gov.uk/NDE2/Disco/FindDatasets?Metadata=wiserd_dataportal_replace_me";
//
//        $remoteSources[] = $sourceNHood;


//you should see a list of all Controllers/Models
//        Log::toFile( print_r(get_declared_classes(), true));

//        $modelPath = Yii::getPathOfAlias('application.models');

//        Log::toFile('models ' . $modelPath);

//        Yii::setPathOfAlias('application.models.DataFeedReaders', $modelPath . '/DataFeedReaders');

//        $feedPath = Yii::getPathOfAlias('application.models.DataFeedReaders');

//        Log::toFile('feeds ' . $feedPath);

//        Yii::import("application.models.feedreaders.*", true);

//
//        $declaredClasses = get_declared_classes();
//        foreach (glob(Yii::getPathOfAlias('application.models.DataFeedReaders') . "/*.php") as $controller){
//            $class = basename($controller, ".php");
//            Log::toFile($class . ' ' . $controller);
//            if (!in_array($class, $declaredClasses)) {
//                Yii::import("application.models.DataFeedReaders." . $class, true);
//            }
//        }

//
//        $readers = array();
//        $feedNames = array();
//        $remoteSources = array();
//        foreach(get_declared_classes() as $className) {
//            if( in_array('FeedReaderInterface', class_implements($className)) ) {
//                $readers[] = $className;
//                Log::toFile($className);
//
//
//                $reflectionMethod = new ReflectionMethod($className, 'getFeedName');
//                $feedName = $reflectionMethod->invoke(new $className());
//
//                $feedNames[] = $feedName;
//                $reflectedInfo['name'] = $feedName;
//
//                $remoteSources[] = $reflectedInfo;
//            }
////            Log::toFile($className);
//        }

//        Log::toFile( print_r(get_declared_classes(), true));

        $feedReaderClasses = RemoteDataController::getFeedReaderClasses();

        $remoteSources = array();
        foreach($feedReaderClasses as $feedClass ) {
//            $reflectionMethod = new ReflectionMethod($className, 'getFeedName');
//            $feedName = $reflectionMethod->invoke(new $className());

//            $feedNames[] = $feedClass->getFeedName();
            $reflectedInfo['name'] = $feedClass->getFeedName();

            $remoteSources[] = $reflectedInfo;
        }

//        Log::toFile('feeds : ' . print_r($feedNames, true));

//        Log::toFile('readers : ' . print_r($readers, true));


        echo json_encode($remoteSources);
    }

    public function actiongetRemoteDatasetDownloadUrl() {
        $boundaryID = $_POST['BoundaryID'];
        $datasetID = $_POST['DatasetID'];
        $measuresID = $_POST['MeasuresID'];
        $format = $_POST['format'];

        $feedReader = "";
        if(isset($_POST['apiName'])) {
            $feedReader = $_POST['apiName'];
        }

        $readerClass = RemoteDataController::getFeedReaderClassByName($feedReader);
        $urlArray = $readerClass->getRemoteDatasetDownloadURL($datasetID, $boundaryID, $measuresID, $format);

        echo json_encode($urlArray);
    }

    public function actiongetRemoteData() {
        $boundaryID = $_POST['BoundaryID'];
        $datasetID = $_POST['DatasetID'];
        $measuresID = $_POST['MeasuresID'];

        $start = 0;
        if(isset($_POST['start'])) {
            $start = $_POST['start'];
        }
        $limit = 30;
        if(isset($_POST['limit'])) {
            $limit = $_POST['limit'];
        }

        $feedReader = "";
        if(isset($_POST['apiName'])) {
            $feedReader = $_POST['apiName'];
        }

//        $dataResults = array();
//
//        if ($feedReader === "nomisweb") {
//            $nomisReader = new NomiswebReader();
//
//            $dataResults = $nomisReader->getRemoteDataset($datasetID, $boundaryID, $measuresID);
//        }
//
//        if ($feedReader === "neighbourhood.statistics") {
//            $nhoodReader = new NHoodReader();
//
//            $dataResults = $nhoodReader->getRemoteDataset($datasetID, $boundaryID, $measuresID);
//        }

        $readerClass = RemoteDataController::getFeedReaderClassByName($feedReader);

        $dataResults = $readerClass->getRemoteDataset($datasetID, $boundaryID, $measuresID, $limit, $start);


//        $dataResults['fullset'] = $dataResults;
        $totalCount = sizeof($dataResults['data']);

        $pageResults = array();
        $cnt = $start;
        $cnt_end = $cnt + $limit;
//        $keys = array_keys($dataResults['data']);

        while ( $cnt < $cnt_end && $cnt < sizeof($dataResults['data'])) {
            $pageResults[] = $dataResults['data'][$cnt];
            $cnt ++;
        }

        $dataResults['data'] = $pageResults;
        $dataResults['totalCount'] = $totalCount;

        echo json_encode($dataResults);

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

//        $results = "";
//
//        if ($feedReader === "nomisweb") {
//            $nomisReader = new NomiswebReader();
//
//            $results = $nomisReader->getRemoteGeographies($datasetID, $regionID);
//        }
//
//        if ($feedReader === "neighbourhood.statistics") {
//            $nhoodReader = new NHoodReader();
//
//            $results = $nhoodReader->getRemoteGeographies($datasetID, $regionID);
//        }

        $readerClass = RemoteDataController::getFeedReaderClassByName($feedReader);

        $results = $readerClass->getRemoteGeographies($datasetID, $regionID);

        echo json_encode($results);
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

//        $regionResults = "";

//        if ($feedReader === "nomisweb") {
//            $nomisReader = new NomiswebReader();
//
//            $regionResults = $nomisReader->getRegionBreakdown($datasetID, $regionID);
//        }
//
//        if ($feedReader === "neighbourhood.statistics") {
//            $nhoodReader = new NHoodReader();
//
//            $regionResults = $nhoodReader->getRegionBreakdown($datasetID, $regionID);
//        }

        $readerClass = RemoteDataController::getFeedReaderClassByName($feedReader);

        $regionResults = $readerClass->getRegionBreakdown($datasetID, $regionID);


        echo json_encode($regionResults);
    }

    public function actiongetRemoteVariables() {

        $dataset = $_POST['DatasetID'];

        $feedReader = "";
        if(isset($_POST['apiName'])) {
            $feedReader = $_POST['apiName'];
        }

//        $variableResults = "";
//
//        if ($feedReader === "nomisweb") {
//            $nomisReader = new NomiswebReader();
//
//            $variableResults = $nomisReader->getRemoteVariables($dataset);
//        }
//
//        if ($feedReader === "neighbourhood.statistics") {
//            $nhoodReader = new NHoodReader();
//
//            $variableResults = $nhoodReader->getRemoteVariables($dataset);
//        }

        $readerClass = RemoteDataController::getFeedReaderClassByName($feedReader);

        $variableResults = $readerClass->getRemoteVariables($dataset);

        echo json_encode($variableResults);

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

//        $keywordResults = "";
//
//        if ($feedReader === "nomisweb") {
//            $nomisReader = new NomiswebReader();
//
//            $keywordResults = $nomisReader->keywordSearch($keyword);
//        }
//
//        if ($feedReader === "neighbourhood.statistics") {
//            $nhoodReader = new NHoodReader();
//
//            $keywordResults = $nhoodReader->keywordSearch($keyword);
//        }

        $readerClass = RemoteDataController::getFeedReaderClassByName($feedReader);

        $keywordResults = $readerClass->keywordSearch($keyword);

        echo json_encode($keywordResults);
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
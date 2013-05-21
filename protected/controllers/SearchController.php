<?

class SearchController extends Controller {

    public $results; // As ArrayList
    private $QualResults; // As ArrayList
    private $count; // As Integer
    private $qCount; // As Integer

    public function actionverifySearch() {
// $Keywords = "";  $mappable As Boolean
        $Keywords = "";
        $Mappable = false;

        //POST for panel submit

        if(isset($_POST['Keywords'])) {
            $Keywords = $_POST['Keywords'];
        }
        $mappableString = "";
        if(isset($_POST['mappable'])) {
            $mappableString = $_POST['mappable'];
        }

        //GET for API

        if(isset($_GET['Keywords'])) {
            $Keywords = $_GET['Keywords'];
        }
        if(isset($_GET['mappable'])) {
            $mappableString = $_GET['mappable'];
        }

        if( $mappableString == "true") {
            $Mappable = true;
        } else {
            $Mappable = false;
        }

//        Log::toFile(PHP_EOL . PHP_EOL . "A search is requested" . PHP_EOL);

        $res = New getResults();
        $resultsExsist = $res->getQuestionnaireData(0, 1, $Keywords, True, $Mappable);
        $qualResults = $res->getQualData($Keywords);
        $strVerify = "";

        If (sizeof($resultsExsist) == 0 && sizeof($qualResults) == 0) {
            $strVerify = '{"failure": true}';
        }Else{
            $strVerify = '{"success": true}';
        }

        echo $strVerify;
    }

    Function actionSimpleSearch() {
//        $Keywords = "";  $start; $limit As Integer,  $Mappable As Boolean
//            Npgsql.NpgsqlConnection.ClearAllPools()
        $start = 0;
        $limit = 15;
        $Mappable = false;
        $Keywords = "";

    //POSTs for panel submit

        if(isset($_POST['start'])) {
            $start = $_POST['start'];
        }
        if(isset($_POST['limit'])) {
            $limit = $_POST['limit'];
        }
        if(isset($_POST['Mappable'])) {
            $Mappable = $_POST['Mappable'];
        }
        if(isset($_POST['keywords'])) {
            $Keywords = $_POST['keywords'];
        }

    //GETs for API calls

        if(isset($_GET['start'])) {
            $start = $_GET['start'];
        }
        if(isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }
        if(isset($_GET['Mappable'])) {
            $Mappable = $_GET['Mappable'];
        }
        if(isset($_GET['keywords'])) {
            $Keywords = $_GET['keywords'];
        }

        $count = 0;

        $results = null;
        If ($Keywords == "") {
            $results = Yii::app()->session["results"];
            $count = Yii::app()->session["resCount"];

        }Else{
            If ($results == null) {
                $res = New getResults();
                $results = $res->getQuestionnaireData($start, $limit, $Keywords, False, $Mappable);
                $count = count($results);

                Yii::app()->session["results"] = $results;
                Yii::app()->session["resCount"] = $count;
            }
        }

        $resultsset = New results();

        $resultsset->totalCount = $count;
        $resultsset->questions = json_encode($results);



        $pageResults = array();

        $cnt = $start;
        $cnt_end = $cnt + $limit;

        while( $cnt <= $cnt_end && $cnt < sizeof($results)){
            $pageResults[] = ($results[$cnt]);
            $cnt += 1;
        }



        $str = '{"totalCount":"' . $count . '", "results":' . json_encode($pageResults) . '}';

        echo $str;


        //' Return Json(res, JsonRequestBehavior.AllowGet)
    }

    Function actionQualSimpleSearch( ){
//            $start As Integer,  $limit As Integer,  $keywords = ""
//            Npgsql.NpgsqlConnection.ClearAllPools();

        $start = 0;
        $limit = 15;
        $keywords = "";

    //POST for panel submit

        if(isset($_POST['start'])) {
            $start = $_POST['start'];
        }
        if(isset($_POST['limit'])) {
            $limit = $_POST['limit'];
        }
        if(isset($_POST['keywords'])) {
            $keywords = $_POST['keywords'];
        }

    //GET for API

        if(isset($_GET['start'])) {
            $start = $_GET['start'];
        }
        if(isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }
        if(isset($_GET['keywords'])) {
            $keywords = $_GET['keywords'];
        }

        If ($keywords == "" ) {
            $QualResults = Yii::app()->session["QualResults"];
            $qCount = Yii::app()->session["QualresCount"];
        }Else{
            $res = New getResults();
            $QualResults = $res->getQualData($keywords);
            $qCount = sizeof($QualResults);

            Yii::app()->session["QualResults"] = $QualResults;
            Yii::app()->session["QualresCount"] = $qCount;

        }

        $resultsset = New results();

        $resultsset->totalCount = $qCount;
        $resultsset->questions = json_encode($QualResults);

//        Log::toFile(print_r($QualResults, true));

        $pageResults = array();

        $cnt = $start;
        $cnt_end = $cnt + $limit;

//        Do Until $cnt = $cnt_end Or $cnt = $QualResults->Count;
        while ($cnt <= $cnt_end && $cnt < sizeof($QualResults)) {
            $pageResults[] = ($QualResults[$cnt]);

//            Log::toFile($cnt . ' of ' . $qCount . ' : ' . print_r($QualResults, true));

            $cnt += 1;
        }

        $str = '{"totalCount":"' . $qCount . '", "results":' . json_encode($pageResults) . '}';
        echo $str;
    }


    //<CompressFilter()>
    Function actiongetQuestions() {

        $start = 0;
        $limit = 0;
        $SID = "";

    //POST for panel submit

        if(isset($_POST['start'])) {
            $start = intval($_POST['start']);
        }
        if(isset($_POST['limit'])) {
            $limit = intval($_POST['limit']);
        }
        if(isset($_POST['SID'])) {
            $SID = $_POST['SID'];
        }

    //GET for API

        if(isset($_GET['start'])) {
            $start = intval($_GET['start']);
        }
        if(isset($_GET['limit'])) {
            $limit = intval($_GET['limit']);
        }
        if(isset($_GET['SID'])) {
            $SID = $_GET['SID'];
        }

        $res = New getResults();
        $results = $res->getSurveyQuestion($SID);
        $count = count($results);

        $resultsset = New results();

        $resultsset->totalCount = $count;
        $resultsset->questions = json_encode($results);


        $pageResults = array();

        $cnt = $start;
        $cnt_end = $cnt + $limit;

//            Do Until cnt = cnt_end Or cnt = results->Count;
        while ($cnt <= $cnt_end && $cnt < sizeof($results)) {
            $pageResults[] = ($results[$cnt]);
            $cnt += 1;
        }


        $str = '{"totalCount":"' . $count . '", "questions":' . json_encode($pageResults) . "}";

        echo $str;


    }

}

?>
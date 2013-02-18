<?

class SearchController extends Controller {

    private $results; // As ArrayList
    private $QualResults; // As ArrayList
    private $count; // As Integer
    private $qCount; // As Integer

    public function actionverifySearch() {
// $Keywords = "";  $mappable As Boolean
        $Keywords = "";
        $Mappable = false;

        if(isset($_POST['Keywords'])) {
            $Keywords = $_POST['Keywords'];
        }
        if(isset($_POST['mappable'])) {
            $mappableString = $_POST['mappable'];
            if( $mappableString = "true") {
                $Mappable = true;
            } else {
                $Mappable = false;
            }
        }

        $res = New getResults();
        $resultsExsist = $res->getQuestionnaireData(0, 1, $Keywords, True, $Mappable);
        $qualResults = $res->getQualData($Keywords);
        $strVerify = "";

        If (sizeof($resultsExsist) == 0 && sizeof($qualResults) == 0) {
            $strVerify = '{"failure": "true"}';

        }Else{
            $strVerify = '{"success": "true"}';

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

        if(isset($_POST['start'])) {
            $start = $_POST['start'];
        }
        if(isset($_POST['limit'])) {
            $limit = $_POST['limit'];
        }
        if(isset($_POST['Mappable'])) {
            $Mappable = $_POST['Mappable'];
        }
        if(isset($_POST['Keywords'])) {
            $Keywords = $_POST['Keywords'];
        }

        $count = 0;

        If ($Keywords = "") {
            $results = $_SESSION["results"];
            $count = $_SESSION["resCount"];

        }Else{
            If ($results = null) {
                $res = New getResults();
                $results = $res->getQuestionnaireData($start, $limit, $Keywords, False, $Mappable);
                $count = $res->count;

                $_SESSION["results"] = $results;
                $_SESSION["resCount"] = $count;
            }
        }

        $resultsset = New results();

        $resultsset->totalCount = $count;
        $resultsset->questions = json_encode($results);



        $pageResults = array();

        $cnt = $start;
        $cnt_end = $cnt + $limit;

        while( $cnt = $cnt_end Or $cnt = sizeof($results)){
            $pageResults[] = ($results->Item($cnt));
            $cnt += 1;
        }



        $str = '{"totalCount":"' . $count . '", "results":"' . json_encode($pageResults) . '"}';

        Return $str;


        //' Return Json(res, JsonRequestBehavior.AllowGet)
    }

    Function actionQualSimpleSearch( ){
//            $start As Integer,  $limit As Integer,  $keywords = ""
//            Npgsql.NpgsqlConnection.ClearAllPools();

        $start = 0;
        $limit = 15;
        $Mappable = false;
        $Keywords = "";

        if(isset($_POST['start'])) {
            $start = $_POST['start'];
        }
        if(isset($_POST['limit'])) {
            $limit = $_POST['limit'];
        }
        if(isset($_POST['Mappable'])) {
            $Mappable = $_POST['Mappable'];
        }
        if(isset($_POST['Keywords'])) {
            $Keywords = $_POST['Keywords'];
        }

        If ($keywords = "" ) {
            $QualResults = Session("QualResults");
            $qCount = Session("QualresCount");
        }Else{
            $res = New getResults();
            $QualResults = $res->getQualData($keywords);
            $qCount = sizeof($QualResults);

                $_Session["QualResults"] = $QualResults;
                $_Session["QualresCount"] = $qCount;

            }


        $resultsset = New results();

        $resultsset->totalCount = $qCount;
        $resultsset->questions = json_encode($QualResults);


        $pageResults = array();

        $cnt = $start;
        $cnt_end = $cnt + $limit;

//        Do Until $cnt = $cnt_end Or $cnt = $QualResults->Count;
        while ($cnt = $cnt_end or $cnt = sizeof($QualResults)) {
                $pageResults[] = ($QualResults->Item($cnt));
                $cnt += 1;
        }


            $str = '{"totalCount":"' . $qCount . '", "results":"' . json_encode($pageResults) . "}";

            echo $str;


           // ' Return Json(res, JsonRequestBehavior.AllowGet)
        }


    //<CompressFilter()>
    Function getQuestions($start, $limit, $SID) {
//$start As Integer,  $limit As Integer,  $SID = ""
//            Npgsql.NpgsqlConnection.ClearAllPools();

        $res = New getResults();
        $results = $res->getSurveyQuestion($SID);
        $count = sizeof($results);

        $resultsset = New results();

        $resultsset->totalCount = $count;
        $resultsset->questions = json_encode($results);


        $pageResults = array();

        $cnt = $start;
        $cnt_end = $cnt + $limit;

//            Do Until cnt = cnt_end Or cnt = results->Count;
        while ($cnt = $cnt_end or $cnt = sizeof($results)) {
                $pageResults[] = ($results->Item($cnt));
                $cnt += 1;
        }


            $str = '{"totalCount":"' . $count . '", "questions":"' . json_encode($pageResults) . "}";

            echo $str;


        }

}

?>
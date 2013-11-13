<?

class MetaDataController extends Controller {

    Function actiongetResponseOptionsTable() {
        $QID = "";

        //POST for panel submit
        if(isset($_POST['QID'])) {
            $QID = $_POST['QID'];
        }

        $getMeta = New getMetaData();

        $RMetaData = $getMeta->getResponseOptionsTable($QID);

        If( ! $RMetaData == null) {
            echo '{"success": true, "data": ' . json_encode($RMetaData) . '}';
        } Else {

            echo '{"success": false, "message": "Error loading form - please try again"}';
        }
    }

    Function actiongetQMetaDataRecords() {

        $ID = "";

        //POST for panel submit
        if(isset($_POST['ID'])) {
            $ID = $_POST['ID'];
        }

        //GET for API
        if(isset($_GET['ID'])) {
            $ID = $_GET['ID'];
        }

        $getMeta = New getMetaData();

        $QMetaData = $getMeta->getQuestionMetaData($ID);

        If( ! $QMetaData == null) {
            echo '({"success": true, "data": ' . json_encode($QMetaData) . '})';
        } Else {

            echo '({"success": false, "message": "Error loading form - please try again"})';
        }


    }

//        <CompressFilter()>
    Function actiongetSMetaDataRecords( ) {

        $SID = "";

        //POST for panel submit
        if(isset($_POST['SID'])) {
            $SID = $_POST['SID'];
        }

        //GET for API
        if(isset($_GET['SID'])) {
            $SID = $_GET['SID'];
        }

        $getMeta = New getMetaData();


        $SMetaData = $getMeta->getSurveyMetaData($SID);

        If (! $SMetaData == null ) {
            echo '({"success": true, "data": ' . json_encode($SMetaData) . '})';
        } Else {
            echo '({"success": false, "message": "Error loading form - please try again"})';
        }

    }

    Function actiongetRMetaDataRecords(  ) {

        $QID = "";

        //POST for panel submit
        if(isset($_POST['QID'])) {
            $QID = $_POST['QID'];
        }

        //GET for API
        if(isset($_GET['QID'])) {
            $QID = $_GET['QID'];
        }

        $getMeta = New getMetaData();

        $RMetaData = $getMeta->getResponseMetaData($QID);

        If (! $RMetaData == null ) {
            echo '({"success": true, "data": ' . json_encode($RMetaData) . '})';
        } Else {

            echo '({"success": false, "message": "Error loading form - please try again"})';
        }

    }

    Function actiongetDCMetaDataRecords(   ) {

        $SID = "";
        //POST for panel submit
        if(isset($_POST['SID'])) {
            $SID = $_POST['SID'];
        }

        //GET for API
        if(isset($_GET['SID'])) {
            $SID = $_GET['SID'];
        }

        $getMeta = New getMetaData();

        $DCMetaData = $getMeta->getDublinCore($SID);

        If (! $DCMetaData == null ) {
            echo '({"success": true, "data": ' . json_encode($DCMetaData) . '})';
        } Else {
            echo '({"success": false, "message": "Error loading form - please try again"})';
        }

    }

    Function actiongetQDCMetaDataRecords(  ) {

        $SID = "";

        //POST for panel submit
        if(isset($_POST['SID'])) {
            $SID = $_POST['SID'];
        }

        //GET for API
        if(isset($_GET['SID'])) {
            $SID = $_GET['SID'];
        }

        $getMeta = New getMetaData();

        $DCMetaData = $getMeta->getQDublinCore(Trim($SID));

        If (! $DCMetaData == null ) {
            echo '({"success": true, "data": ' . json_encode($DCMetaData) . '})';
        } Else {
            echo '({"success": false, "message": "Error loading form - please try again"})';
        }
    }

    Function actiongetResponseTable(  ) {

        $SID = "";
        $unit = "";
        $start = 0;
        $limit = 0;

        //POST for panel submit
        if(isset($_POST['SID'])) {
            $SID = $_POST['SID'];
        }
        if(isset($_POST['unit'])) {
            $unit = $_POST['unit'];
        }
//        if(isset($_POST['start'])) {
//            $start = $_POST['start'];
//        }
//        if(isset($_POST['limit'])) {
//            $limit = $_POST['limit'];
//        }

        //GET for API
        if(isset($_GET['SID'])) {
            $SID = $_GET['SID'];
        }
        if(isset($_GET['unit'])) {
            $unit = $_GET['unit'];
        }

        $getMeta = New getMetaData();
        $ResponseMetaData = $getMeta->getResponseTable($SID, $unit);

//            $cnt = $start;
//            $cnt_end = $cnt + $limit;
//
//            $pageResults = array();
//            while( $cnt <= $cnt_end || $cnt == sizeof($ResponseMetaData)){
//                $pageResults[] = ($ResponseMetaData[$cnt]);
//                $cnt += 1;
//            }

        If (! $ResponseMetaData == null ) {
            echo '({"success": true, "data": ' . json_encode($ResponseMetaData) . '})';
        } Else {
            echo '({"success": false, "message": "Error loading form - please try again"})';
        }
    }

    Function actiongetPlaces(  ) {

        $ID = "";

        //POST for panel submit
        if(isset($_POST['ID'])) {
            $ID = $_POST['ID'];
        }

        //GET for API
        if(isset($_GET['ID'])) {
            $ID = $_GET['ID'];
        }

        $getMeta = New getMetaData();
        $docPlaces = $getMeta->getPlaces($ID);

        If (! $docPlaces == null ) {
            echo '({"success": true, "data": ' . json_encode($docPlaces) . '})';
        } Else {

            echo '({"success": false, "message": "Error loading form - please try again"})';
        }

    }

    Function actiongetQualWordCounts( ) {

        $ID = "";
        $place1 = "";
        $place2 = "";
        $place3 = "";

        if(isset($_GET['ID'])) {
            $ID = $_GET['ID'];
        }
        if(isset($_GET['place1'])) {
            $place1 = $_GET['place1'];
        }
        if(isset($_GET['place2'])) {
            $place2 = $_GET['place2'];
        }
        if(isset($_GET['place3'])) {
            $place3 = $_GET['place3'];
        }

        $getMeta = New getMetaData();


        $docWords = $getMeta->getQualWords($ID, $place1, $place2, $place3);

        If (! $docWords == null ) {
            echo '({"data": ' . json_encode(array_values( $docWords) ) . '})';
        } Else {
            echo '({"success": false, "message": "Error loading form - please try again"})';
        }
    }


    Public Function actiongetFields( ) {

        $SID = "";
        $unit = "";

        //POST for panel submit
        if(isset($_POST['SID'])) {
            $SID = $_POST['SID'];
        }
        if(isset($_POST['unit'])) {
            $unit = $_POST['unit'];
        }

        //GET for API

        if(isset($_GET['SID'])) {
            $SID = $_GET['SID'];
        }
        if(isset($_GET['unit'])) {
            $unit = $_GET['unit'];
        }

        $MD = New getMetaData();

        $SL = $MD->getFields($SID, $unit);

        echo '{"rows":' . json_encode($SL) . "}";
    }

    Public Function actiongetCloud( ) {

        $ID = "";

        //POST for panel submit
        if(isset($_POST['ID'])) {
            $ID = $_POST['ID'];
        }
        if(isset($_POST['callback'])) {
            $callback = $_POST['callback'];
        }

        //GET for API
        if(isset($_GET['ID'])) {
            $ID = $_GET['ID'];
        }

        $MD = New getMetaData();

        echo $MD->getCloud($ID);
    }

}
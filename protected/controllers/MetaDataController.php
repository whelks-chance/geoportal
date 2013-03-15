<?

//Imports GeoPortal.GeoPortal.Models.Data

//Namespace GeoPortal
    class MetaDataController extends Controller {
//        Inherits System.Web.Mvc.Controller

//        '
//        ' GET: /MetData
//        <CompressFilter()>
    Function actiongetQMetaDataRecords() {

        $ID = "";
        if(isset($_POST['ID'])) {
            $ID = $_POST['ID'];
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
        if(isset($_POST['SID'])) {
            $SID = $_POST['SID'];
        }

        $getMeta = New getMetaData();


        $SMetaData = $getMeta->getSurveyMetaData($SID);

        If (! $SMetaData == null ) {
            echo '({"success": true, "data": ' . json_encode($SMetaData) . '})';
        } Else {

            echo '({"success": false, "message": "Error loading form - please try again"})';
        }

    }




//        <CompressFilter()>
        Function actiongetRMetaDataRecords(  ) {

            $QID = "";
            if(isset($_POST['QID'])) {
                $QID = $_POST['QID'];
            }


            $getMeta = New getMetaData();

            $RMetaData = $getMeta->getResponseMetaData($QID);

            If (! $RMetaData == null ) {
                echo '({"success": true, "data": ' . json_encode($RMetaData) . '})';
            } Else {

                echo '({"success": false, "message": "Error loading form - please try again"})';
            }




        }

//        <CompressFilter()>
        Function actiongetDCMetaDataRecords(   ) {

            $SID = "";
            if(isset($_POST['SID'])) {
                $SID = $_POST['SID'];
            }


            $getMeta = New getMetaData();


            $DCMetaData = $getMeta->getDublinCore($SID);

            If (! $DCMetaData == null ) {
                echo '({"success": true, "data": ' . json_encode($DCMetaData) . '})';
            } Else {

                echo '({"success": false, "message": "Error loading form - please try again"})';
            }

        }

//        <CompressFilter()>
        Function actiongetQDCMetaDataRecords(  ) {

            $SID = "";
            if(isset($_POST['SID'])) {
                $SID = $_POST['SID'];
            }


            $getMeta = New getMetaData();


            $DCMetaData = $getMeta->getQDublinCore(Trim($SID));

            If (! $DCMetaData == null ) {
                echo '({"success": true, "data": ' . json_encode($DCMetaData) . '})';
            } Else {

                echo '({"success": false, "message": "Error loading form - please try again"})';
            }
        }

//        <CompressFilter()>
        Function actiongetResponseTable(  ) {

            $SID = "";
            $unit = "";
            if(isset($_GET['SID'])) {
                $SID = $_GET['SID'];
            }
            if(isset($_GET['unit'])) {
                $unit = $_GET['unit'];
            }

            $getMeta = New getMetaData();

            $ResponseMetaData = $getMeta->getResponseTable($SID, $unit);

            If (! $ResponseMetaData == null ) {
                echo '({"success": true, "data": ' . json_encode($ResponseMetaData) . '})';
            } Else {

                echo '({"success": false, "message": "Error loading form - please try again"})';
            }
        }

//        <CompressFilter()>
        Function actiongetPlaces(  ) {

            $ID = "";
            if(isset($_POST['ID'])) {
                $ID = $_POST['ID'];
            }


            $getMeta = New getMetaData();


            $docPlaces = $getMeta->getPlaces($ID);



            If (! $docPlaces == null ) {
                echo '({"success": true, "data": ' . json_encode($docPlaces) . '})';
            } Else {

                echo '({"success": false, "message": "Error loading form - please try again"})';
            }

        }


//        <CompressFilter()>
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
                echo '({"success": true, "data": ' . json_encode($docWords) . '})';
            } Else {

                echo '({"success": false, "message": "Error loading form - please try again"})';
            }
        }

//        <CompressFilter()>
        Public Function actiongetFields( ) {

            $SID = "";
            $unit = "";
            if(isset($_POST['SID'])) {
                $SID = $_POST['SID'];
            }
            if(isset($_POST['unit'])) {
                $unit = $_POST['unit'];
            }

            $MD = New getMetaData();

            $SL = $MD->getFields($SID, $unit);


            echo '{"rows":' . json_encode($SL) . "}";
        }


//        <CompressFilter()>
        Public Function actiongetCloud( ) {

            $ID = "";
            if(isset($_POST['ID'])) {
                $ID = $_POST['ID'];
            }
            if(isset($_POST['callback'])) {
                $callback = $_POST['callback'];
            }

            $MD = New getMetaData();

            echo $MD->getCloud($ID);


        }



}
<?
//Imports Npgsql
//Imports System.Data
//Imports System.Web
//Imports GeoPortal.GeoPortal.Models.BusinessLogic
//Imports GeoPortal.GeoPortal.Models.Data

class InsertEvalDetails {

    //'function to insert user details into database

    Public Function EvalDetailsByInsert(UserDetails4Evaluation $user) {
//        ByVal user As UserDetails4Evaluation

//        $user = new UserDetails4Evaluation();

       // 'check to see if username and password are correct

//        'define string
        $checkUserString = "SELECT COUNT(username) FROM alphausersdetails where username = '" . $user->username . "' AND password = crypt('" . $user->enteredPassword . "', password);";
//        'get DB connection string
        $dbCheckUser = new getDBConnections();
//        'new DB connection
        $cnnCheckUser  = $dbCheckUser -> getDBConnection("Geoportal"); // As NpgsqlConnection
        $cmdCheckUser = pg_query($cnnCheckUser, $checkUserString);

        $count = pg_num_rows($cmdCheckUser);

        If ($count <> 1) {
            Return False;
        }

//        'if user exists - continue to insert the data
        If ($user->browser = "") {
            $user->browser = "N/A";
        }

        $date = new DateTime();
        $timestamp = $date->format('U = Y-m-d H:i:s');

        $evalInsert = "UPDATE alphausersdetails SET timestamp = '" . $timestamp . "', browser = '" . $user->browser . "', os = '" . $user->os . "', screenres = '" . $user->screenSize . "', browser_ver = '" . $user->versionStr . "', browser_no = '" . $user->versionNo . "' WHERE username = '" . $user->username . "';";
        $dbEval = New getDBConnections();
        $cnnEval = $dbEval -> getDBConnection("Geoportal");
        $cmdEval = pg_query($cnnEval, $evalInsert );

        //Dim cntEval As Integer
        //cnnEval.Open()
        //cntEval = cmdEval.ExecuteNonQuery()
        //cnnEval.Close()

        If (pg_affected_rows($cmdEval) != 1) {
            Return False;
        }Else{
            $user = getDBConnections::getUser($user->username, $user->enteredPassword);

            Log::toFile('user : ' . print_r($user, true));

            Return True;
        }


    }
}
<?
//Imports Npgsql
//Imports System.Data
//Imports System.Web
//Imports GeoPortal.GeoPortal.Models.BusinessLogic
//Imports GeoPortal.GeoPortal.Models.Data

class InsertEvalDetails {

    //'function to insert user details into database

    Public Function EvalDetailsByInsert(UserDetails4Evaluation $user) {

//        $checkUserString = "SELECT COUNT(username) FROM alphausersdetails where username = '" . $user->username . "' AND password = crypt('" . $user->enteredPassword . "', password);";
//        $rows = DataAdapter::DefaultExecuteAndRead($checkUserString);

        $checkUserString = "SELECT COUNT(username) FROM alphausersdetails where username =:username AND password = crypt(:enteredPassword, password);";
        $values = array(":username" => $user->username, ":enteredPassword" => $user->enteredPassword);

        $resultObject = DataAdapter::DefaultPDOExecuteAndRead($checkUserString, $values);

        $row = $resultObject->resultObject[0];

        $count = $row->count;

        if($count != 1){
            Return False;
        }

//        'if user exists - continue to insert the data
        If ($user->browser == "") {
            $user->browser = "N/A";
        }

        $date = new DateTime();
        $timestamp = $date->format('U = Y-m-d H:i:s');

        $evalInsert = "UPDATE alphausersdetails SET timestamp = :lastLogin, browser = :browser, os = :os,
        screenres = :screenSize, browser_ver = :versionStr, browser_no = :browserNo
        WHERE username = :username AND password = crypt(:enteredPassword, password);";

        $values = array(":lastLogin" => $timestamp, ":browser" => $user->browser, ":os" => $user->os,
            ":screenSize" => $user->screenSize, ":versionStr" => $user->versionStr, ":browserNo" => $user->versionNo,
            ":username" => $user->username, ":enteredPassword" => $user->enteredPassword);

        $dbReturn = DataAdapter::DefaultPDOExecuteAndRead($evalInsert, $values);

//        $evalInsert = "UPDATE alphausersdetails SET timestamp = '" . $timestamp . "', browser = '" . $user->browser . "', os = '" . $user->os . "', screenres = '" . $user->screenSize . "', browser_ver = '" . $user->versionStr . "', browser_no = '" . $user->versionNo . "' WHERE username = '" . $user->username . "' AND password = crypt('" . $user->enteredPassword . "', password);";

        If (sizeof($dbReturn->resultObject) != 1) {
            Return False;
        }Else{
            Return True;
        }


    }
}
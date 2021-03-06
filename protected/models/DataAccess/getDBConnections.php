﻿<?

//Imports Npgsql
//Imports System.Data
//Imports System.Web
//Imports GeoPortal.GeoPortal.Models.BusinessLogic


//Namespace GeoPortal.Models.Data

class getDBConnections {

    Private $liveServer = false; // = Boolean
    Private $cnn;//  = new NpgsqlConnection(); // = Npgsql.NpgsqlConnection

    Public function ew(){

        If (HttpContext.Current.Request.IsLocal) {
            $liveServer = True;
        }Else{
            $liveServer = True;
        }
    }

    Public static Function getDBConnection($DBName = "Geoportal"){ // = Npgsql.NpgsqlConnection

        $debug = variables::debug();

        If ($debug == False) {
            //            $connString = "Server=localhost;Port=5433;Database=" . $DBName . ";User Id=postgres;Password=January1981" .
            //          '"Server=localhost;Port=5433;Database=" + DBName + ";User Id=rfry;Password=January1981"';
//            $connString = "host=192.168.56.102 port=7007 dbname=" . $DBName . " user=" . variables::$databaseUsername . " password=" . variables::$databasePassword;
            $connString = "host=" . variables::$databaseAddr . " port=". variables::$databasePort . " dbname=" . $DBName . " user=" . variables::$databaseUsername . " password=" . variables::$databasePassword;

        }Else {
            // 'change this string according to whether target build is for Glam server or Amazon server
//                $connString = "Server=localhost;Port=5433;Database=" . $DBName . ";User Id=postgres;Password=January1981"
            //              'Server=193.63.128.226;Port=5433;Database=" + DBName + ";User Id=rfry;Password=January1981"';
            $connString = "host=" . variables::$databaseAddr . " port=". variables::$databasePort . " dbname=" . $DBName . " user=" . variables::$databaseUsername . " password=" . variables::$databasePassword;
        }
        $cnn = pg_connect($connString);

        Return $cnn;

    }

    Public Function insertUser(regUser $userdetails){

//        $insertString = "INSERT INTO alphausersdetails(id, username, password, firstname, lastname, email)".
//            " VALUES(DEFAULT,'" .
//            $userdetails->UserName . "'," .
//            "crypt ('" . $userdetails->Password . "', gen_salt('bf')),'" .
//            $userdetails->FirstName . "','" .
//            $userdetails->LastName . "','" .
//            $userdetails->Email . "')";

        $insertString = "INSERT INTO alphausersdetails(id, username, password, firstname, lastname, email)
         VALUES(DEFAULT, :UserName, crypt (:Password, gen_salt('bf')), :FirstName, :LastName, :Email)";

        $values = array(":UserName" => $userdetails->UserName, ":Password" => $userdetails->Password,
            ":FirstName" => $userdetails->FirstName, ":LastName" => $userdetails->LastName,
            ":Email" => $userdetails->Email);

        $cmd = DataAdapter::DefaultPDOExecuteAndRead($insertString, $values);

        If ($cmd->resultSuccess ) {
            Return True;
        } Else {
            Return False;
        }

    }

    Public static Function getUser($userName, $Password) {

//        $loginStr = "Select id, username, firstname, lastname, email, r.name as role from alphausersdetails aud, roles r where username = '" . $userName . "' and  password = crypt('" . $Password . "', password) and aud.role = r.roleid";

        $loginStr = "Select id, username, firstname, lastname, email, r.name as role from alphausersdetails aud,
        roles r where username = :userName and  password = crypt(:Password, password) and aud.role = r.roleid";

        $values = array(":userName" => $userName, ":Password" => $Password);
//        $cmd = pg_query($this::getDBConnection(), $loginStr);

//        $DA = new DataAdapter();
//        $DRs = DataAdapter::DefaultExecuteAndRead($loginStr);

        $resultObject = DataAdapter::DefaultPDOExecuteAndRead($loginStr, $values);

//        Log::toFile('Login string : ' . $loginStr . ' Results : ' . print_r($DRs, true));

        If ( sizeof($resultObject->resultObject) > 0) {

            $DR = $resultObject->resultObject[0];

//TODO warning : prints user details
//            Log::toFile('found user : ' . print_r($DR, true) . gettype($DR));

            $user = New UserDetails();

            $user->FirstName = $DR->firstname;
            $user->LastName = $DR->lastname;
            $user->UserName = $DR->username;
            $user->Email = $DR->email;
            $user->UID = $DR->id;
            $user->success = True;
            $user->message = "Successfully Logged in as " . $userName . "!";

            $role = $DR->role;

//            // set session details
//            $auth = Yii::app()->authManager; //initializes the authManager
//            if(!$auth->isAssigned($role, $user->UserName)) //checks if the role for this user has already been assigned and if it is NOT than it returns true and continues with assigning it below
//            {
//                if($auth->assign($role, $user->UserName)) //assigns the role to the user
//                {
//                    Yii::app()->authManager->save();
//                }
//            }

            Yii::app()->session["User"] = $user;
            $identity=new LoggedInUserIdentity($userName, $Password);
            $identity->authenticate();
            Yii::app()->user->login($identity, 31557600);


            Return $user;
        } Else {
            $user = New UserDetails();
            $user->success = False;
            $user->message = "Incorrect Login Details. Please Try Again!";

            Return $user;
        }
    }


    Public Function getMyAccount($user) {
        $myACDetails = New myAccountDetails();

//        $myACDetails->FirstName = $user->FirstName;
//        $myACDetails->LastName = $user->LastName;
//        $myACDetails->Email = $user->Email;
        $myACDetails->UserName = $user->UserName;

//        $myACDetailscStr = "SELECT * FROM alphausersdetails WHERE id = " . $user->UID;
        $myACDetailscStr = "SELECT * FROM alphausersdetails WHERE id=:UID";

        $values = array(":UID" => $user->UID);
//        $cmd = pg_query($this::getDBConnection(), $myACDetailscStr);
//
//        $DA = new DataAdapter();
//        $DRs = $DA->Read($cmd);

        $resultObject = DataAdapter::DefaultPDOExecuteAndRead($myACDetailscStr, $values);
//        $DRs = DataAdapter::DefaultExecuteAndRead($myACDetailscStr);

        If ( sizeof($resultObject->resultObject) > 0) {

            $DR = $resultObject->resultObject[0];

//            Log::toFile(print_r($DR, true));

            $myACDetails->FirstName = $DR->firstname;
            $myACDetails->LastName = $DR->lastname;
            $myACDetails->Email = $DR->email;

            $myACDetails->Bio = $DR->bio;
            $myACDetails->Email2 = $DR->email;
            $myACDetails->Telephone = $DR->telephone;
            $myACDetails->Address =  $DR->address; //$DR->browser . " " . $DR->os;
            $myACDetails->Institution = $DR->institution;

        }
        $myAC = New myAccount();
        $myAC->data = $myACDetails;
        $myAC->success = True;
        //' myAC.message = "User Account for " + user.FirstName + " " + user.LastName

        Return $myAC;
    }


    Public Function updateMyAccount( $user, $myAccount, $UID) {


//        $updateUserStr = "UPDATE alphausersdetails SET username='" . $user->UserName . "', firstname='" . $user->FirstName . "', lastname='" . $user->LastName . "', email='" . $user->Email . "'";
//
//        $updateUserStr .= ", institution='" . $myAccount->Institution . "', bio='" . $myAccount->Bio . "', telephone='" . $myAccount->Telephone . "', address='" . $myAccount->Address . "'";
//
//        $updateUserStr .= " WHERE id='" . $UID . "';";

        $updateUserStr = "UPDATE alphausersdetails SET username=:UserName, firstname=:FirstName, lastname=:LastName,
        email=:Email, institution=:Institution, bio=:Bio, telephone=:Telephone, address=:Address WHERE id=:UID;";

        $values = array(":UserName" => $user->UserName, ":FirstName" => $user->FirstName, ":LastName" => $user->LastName,
            ":Email" => $user->Email, ":Institution" => $myAccount->Institution, ":Bio" => $myAccount->Bio,
            ":Telephone" => $myAccount->Telephone, ":Address" => $myAccount->Address, ":UID" => $UID);

//        $command = New NpgsqlCommand($updateUserStr & $updateBio, $cnn);
//
//
//        $int = $command->ExecuteNonQuery();
        $resultObject = DataAdapter::DefaultPDOExecuteAndRead($updateUserStr, $values, "Geoportal");

//        $int = DataAdapter::DefaultExecuteAndRead($updateUserStr);

//        If ($resultObject->resultObject != '0' ) {

        If ($resultObject->resultSuccess ) {
            Return True;
        } Else {
            Return False;
        }

    }


    Public Function ChangePassword( $UID, $oldPW, $newPW , $CheckOldPW = true) {

        $change = false;
        if($CheckOldPW) {
//            $checkoldPWstr = "Select id from alphausersdetails where id = '" . $UID . "' and  password = crypt('" . $oldPW . "', password)";
//            $message = New jsonMsg();
//            $cmd = pg_query($this::getDBConnection(), $checkoldPWstr);

            $checkoldPWstr = "Select id from alphausersdetails
            where id = :UID and  password = crypt(:oldPW, password)";

            $values = array(":UID" => $UID, ":oldPW" => $oldPW);
            $results = DataAdapter::DefaultPDOExecuteAndRead($checkoldPWstr, $values);


//            If (!pg_num_rows($cmd) == 0) {
            If (sizeof($results->resultObject) != 0){
                $change = true;
            }

        } else {
            $change = true;
        }

        $message = new jsonMsg();

        if ($change) {
//            $updatePWStr = "Update alphausersdetails set password = crypt('" . $newPW . "', gen_salt('bf')) where id = '" . $UID . "'";
//            $cmd = pg_query($this::getDBConnection(), $updatePWStr );

            $updatePWStr = "Update alphausersdetails set password = crypt(:newPW, gen_salt('bf')) where id = :UID";
            $values = array(":UID" => $UID, ":newPW" => $newPW);
            $results = DataAdapter::DefaultPDOExecuteAndRead($updatePWStr, $values);

//            If (pg_affected_rows($cmd) == 1) {
            If (sizeof($results->resultObject) == 1) {
                $message->success = True;
                $message->message = "Sucessfully changed password!";
            } Else {

                $message->success = False;
                $message->message = "Error changing password! Please try again";
            }
        } else {

            $message->success = False;
            $message->message = "Please check your old password and try again!";
        }
        Return $message;

    }

}



<?

class AccountController extends Controller {

//        $debug = True;
//        $Db = new getDBConnections();


    Function actionresetPW() {
        $Email = "";
        if(isset($_POST['txtEmail'])) {
            $Email = $_POST['txtEmail'];
        }
        $Username = "";
        if(isset($_POST['txtUsername'])) {
            $Username = $_POST['txtUsername'];
        }

        if ($Email && $Email != "") {

//            $findUserStr = "Select id, username from alphausersdetails where email='" . $Email . "';";
//            $idSet = DataAdapter::DefaultExecuteAndRead($findUserStr);

            $idResultObject = DataAdapter::DefaultPDOExecuteAndRead(
                "Select id, username from alphausersdetails where email=:email", array(":email" => $Email));

//            Log::toFile('ids ' . print_r($idResultObject->resultObject, true));

            if(sizeof($idResultObject->resultObject) == 1) {

                $ID = $idResultObject->resultObject[0]->id;
                $Username = $idResultObject->resultObject[0]->username;

                $newPW = Users::randomPassword();

                $Db = new getDBConnections();
                $msg = $Db->ChangePassword($ID, "", $newPW, false);

                sendEmail::SendPasswordReset($Email, $Username, $newPW);

                echo json_encode($msg);

            } Else {
                echo json_encode(false);
            }
        }
    }

    Function actionLogOn() {
        $Username = "";
        if(isset($_POST['Username'])) {
            $Username = $_POST['Username'];
        }
        $password = "";
        if(isset($_POST['password'])) {
            $password = $_POST['password'];
        }

        $Db = new getDBConnections();
        $user = $Db->getUser($Username, $password);


        echo json_encode($user);

    }

    Function actiongetMyDetails() {
        $db = new getDBConnections;
        $db->getDBConnection();
//        If (variables::$debug == True) {
//
//            $db->getUser("rfry", "Jan1981");
//
//        };

        If (! Yii::app()->session["User"] == null) {

            $user = Yii::app()->session["User"];

            $myAC = $db->getMyAccount($user);

            echo json_encode($myAC);
        } Else {
            $msg = new jsonMsg();
            $msg->success = False;
            $msg->message = "Not logged In!";

            echo json_encode($msg);
        }

    }

    Function actionUpdateMyDetails() {
        $UserName = "";
        if(isset($_POST['UserName'])) {
            $UserName = $_POST['UserName'];
        }
        $firstName = "";
        if(isset($_POST['FirstName'])) {
            $firstName = $_POST['FirstName'];
        }
        $lastName = "";
        if(isset($_POST['LastName'])) {
            $lastName= $_POST['LastName'];
        }
        $Email= "";
        if(isset($_POST['Email'])) {
            $Email= $_POST['Email'];
        }
        $Email2= "";
        if(isset($_POST['Email2'])) {
            $Email2 = $_POST['Email2'];
        }
        $Bio = "";
        if(isset($_POST['Bio'])) {
            $Bio = $_POST['Bio'];
        }
        $Institution = "";
        if(isset($_POST['Institution'])) {
            $Institution = $_POST['Institution'];
        }
        $Telephone = "";
        if(isset($_POST['Telephone'])) {
            $Telephone = $_POST['Telephone'];
        }
        $Address = "";
        if(isset($_POST['Address'])) {
            $Address = $_POST['Address'];
        }


        $result = new jsonMsg;
        $newUserDetails = new UserDetails();

        $newUserDetails->FirstName = $firstName;
        $newUserDetails->LastName = $lastName;
        $newUserDetails->Email = $Email;
        $newUserDetails->UserName = $UserName;

        $updateBio = new myAccountDetails();

        $updateBio->Bio = $Bio;
        $updateBio->Address = $Address;
        $updateBio->Institution = $Institution;
        $updateBio->Telephone = $Telephone;

        $update = new getDBConnections();
        $update->getDBConnection();
        If ($update->updateMyAccount($newUserDetails, $updateBio, Yii::app()->session["User"]->UID)) {
            $result->message = "Your Account was successfully updated!";
            $result->success = True;

        } Else {
            $result->message = "There was a problem updating your account - please try again!";
            $result->success = False;

        }

        echo json_encode($result);

    }

    Function actionChangePassword() {
        $oldPassword = "";
        if(isset($_POST['oldPassword'])) {
            $oldPassword = $_POST['oldPassword'];
        }
        $newPW1 = "";
        if(isset($_POST['newPW1'])) {
            $newPW1 = $_POST['newPW1'];
        }

        $user = Yii::app()->session["User"];
        $db = new getDBConnections;
        $db->getDBConnection();
        echo json_encode($db->ChangePassword($user->UID, $oldPassword, $newPW1));
    }




}
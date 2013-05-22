<?

//Imports GeoPortal.GeoPortal.Models.BusinessLogic
//Imports GeoPortal.GeoPortal.Models.Data

//Namespace GeoPortal
class RegisterController extends Controller {

    public function actioncheckNameFree() {
        $UserName = "";
        if(isset($_POST['UserName'])) {
            $UserName = $_POST['UserName'];
        }

        $dataAdapter = new DataAdapter();

        $userNameQuery = "Select username From alphausersdetails where username='" . $UserName . "';";

        $surveyNameResults = $dataAdapter->DefaultExecuteAndRead($userNameQuery, "Geoportal");

        $toReturn = array();

        if(sizeof($surveyNameResults) > 0 ){
            $toReturn['success'] = false;
        } else {
            $toReturn['success'] = true;
        }

        echo json_encode($toReturn);

    }

    // GET: /Register
    // <CompressFilter()>
    public function actionRegister() {

        $defaultPassword = 'password';

        $UserName = ""; $firstName = ""; $lastName = ""; $Email = ""; $Email2 = "";
        $Bio = ""; $Institution = ""; $Tel = ""; $Address = "";

        if(isset($_POST['firstName'])) {
            $firstName = $_POST['firstName'];
        }
        if(isset($_POST['lastName'])) {
            $lastName = $_POST['lastName'];
        }
        if(isset($_POST['Bio'])) {
            $Bio = $_POST['Bio'];
        }
        if(isset($_POST['Institution'])) {
            $Institution = $_POST['Institution'];
        }
        if(isset($_POST['Tel'])) {
            $Tel = $_POST['Tel'];
        }
        if(isset($_POST['Address'])) {
            $Address = $_POST['Address'];
        }


        if(isset($_POST['UserName'])) {
            $UserName = $_POST['UserName'];
        }
        if(isset($_POST['password'])) {
            $defaultPassword = $_POST['password'];
        }
        if(isset($_POST['Email'])) {
            $Email = $_POST['Email'];
        }


        $reg = New regUser();

        $reg->UserName = $UserName;
        $reg->FirstName = $firstName;
        $reg->LastName = $lastName;
        $reg->Email = $Email;
        $reg->Password = $defaultPassword;

        $reg->Biography = $Bio;
        $reg->Institution = $Institution;
        $reg->Telephone = $Tel;
        $reg->Address = $Address;

        $DBconn = New getDBConnections();

        $conn = $DBconn->getDBConnection("Geoportal");

        $result = New jsonMsg();

        if ($DBconn->insertUser($reg, $conn)) {
            $result->success = True;

            sendEmail::SendRegisterEmail($UserName, $Email, $defaultPassword);

            $result->message = "Congratulations " . $firstName . "! You have registered to use the WISERD DataPortal!";

            sendEmail::SendRegistrationInfoToSelf($UserName);
        }else{
            $result->success = False;
            $result->message = "Error! Please Try again";
        }


        echo json_encode($result);

    }

//        'variables from the user evaluation log-in form are passed to the function
    public function actionCaptureUserEvalDetails() {
        $browser = "";
        $versionStr = "";
        $versionNo = "";
        $os = "";
        $screensize = "";
        $txtUsername = "";
        $txtPassword = "";

        if(isset($_POST['browser'])) {
            $browser = $_POST['browser'];
        }
        if(isset($_POST['versionStr'])) {
            $versionStr = $_POST['versionStr'];
        }
        if(isset($_POST['versionNo'])) {
            $versionNo = $_POST['versionNo'];
        }
        if(isset($_POST['os'])) {
            $os = $_POST['os'];
        }
        if(isset($_POST['screensize'])) {
            $screensize = $_POST['screensize'];
        }
        if(isset($_POST['txtUsername'])) {
            $txtUsername = $_POST['txtUsername'];
        }
        if(isset($_POST['txtPassword'])) {
            $txtPassword = $_POST['txtPassword'];
        }

//            'using variables passed do something.....
        $msg = New jsonMsg();

//            'sets the variables of the UserDetails4Evaluation class (user's details and browser details)
        $UserEvaluationDetails = New UserDetails4Evaluation();
        $UserEvaluationDetails->browser = $browser;
        $UserEvaluationDetails->os = $os;
        $UserEvaluationDetails->screenSize = $screensize;
        $UserEvaluationDetails->versionStr = $versionStr;
        $UserEvaluationDetails->versionNo = $versionNo;
        $UserEvaluationDetails->enteredPassword = $txtPassword;
        $UserEvaluationDetails->username = $txtUsername;

        $evalDetailsInsert = new InsertEvalDetails();

        If ($txtUsername == "0000") {
            $success = True;
        } else {
            $success = $evalDetailsInsert->EvalDetailsByInsert($UserEvaluationDetails);

            $userDetails = getDBConnections::getUser(
                $UserEvaluationDetails->username,
                $UserEvaluationDetails->enteredPassword
            );
        }

        If ($success) {
//                'if true do something i.e. insert has worked
            $msg->success = True;
            $msg->message = "Hooray!";
        }Else{
//                'insert failed
            $msg->success = False;
            $msg->message = "failed";
        }
//            'TODO: what happens to these results?

//            'once the process has been completed return either success or failure
        echo json_encode($msg);

    }
}
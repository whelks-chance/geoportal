
<?

class MessageController extends Controller {

        Function actionsubmitAbug() {

            $name = 'null';
            $email = 'null';
            $message = 'null';
            $activity = 'null';

            if(isset($_POST['name'])) {
                $name = $_POST['name'];
            }
            if(isset($_POST['email'])) {
                $email = $_POST['email'];
            }
            if(isset($_POST['message'])) {
                $message = $_POST['message'];
            }
            if(isset($_POST['activity'])) {
                $activity = $_POST['activity'];
            }
//            $msg = New sendEmail();
            $result = New jsonMsg;

            $msgbody = "";
            $msgbody .= ("From: " . $name . "<br><br>");
            $msgbody .= ("Email: " . $email . "<br><br>");
            $msgbody .= ("Report:" . "<br>");
            $msgbody .= ($message . "<br><br>");
            $msgbody .= ("Activity:" . "<br>");
            $msgbody .= ($activity . "<br><br>");

            $sent = sendEmail::SendBugEmail($msgbody);

            If ($sent) {
                $result->success = True;
                $result->message = "Thank you " . $name . ", your bug report has been successfully submitted.";

            } Else {
                $result->success = False;
                $result->message = "Error Sending Bug report, please try again!";

            }


            echo json_encode($result);
        }

}

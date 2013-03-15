
<?

class MessageController extends Controller {

        Function actionsubmitAbug($name , $email , $sendDate , $message , $activity ) {

            $msg = New sendEmail();
            $result = New jsonMsg;

            $msgbody = "";
            $msgbody .= ("From: " . $name . "</br>");
            $msgbody .= ("Email: " . $email . "</br>");
            $msgbody .= ("Report:" . "</br>");
            $msgbody .= ($message . "</br>");
            $msgbody .= ("Activity:" . "</br>");
            $msgbody .= ($activity . "</br>");


            If ($msg->SendEmail($email, "WISERD@glam.ac.uk", "", "Bug Report", $msgbody)) {
                $result->success = True;
                $result->message = "Thankyou " . $name . ",your bug report has been sucessfully submited.";

            } Else {
                $result->success = False;
                $result->message = "Error Sending Bug report, please try again!";

            }


            echo json_encode($result);
        }

}

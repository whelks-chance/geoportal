<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 12/03/13
 * Time: 19:04
 * To change this template use File | Settings | File Templates.
 */


class sendEmail{

    Public static Function SendRegisterEmail($Username, $Email, $Password ) {

        $Subject = "WISERD DataPortal Registration Confirmation";

        $content = "<br>Welcome " . $Username . ", and thank you for registering with the WISERD DataPortal.";

        $content .= "<br><br>You may now log in with the username " . $Username . " and password " . $Password . ".";

        $content .= "<br><br>Please do not reply to this automated email, as the address is not monitored.";

        $content .= "<br><br>Any questions or comments are very welcome at noone@nowhere.xyz";

        sendEmail::SendAnEmail($Email, $Username, $Subject, $content);
    }

    Public static Function SendAnEmail( $Email, $Username, $Subject = "", $content = "") {

        Yii::import('application.extensions.phpmailer.JPhpMailer');
//        include('../../extensions/phpmailer/JPhpMailer.php');

        $mail = new JPhpMailer;
        $mail->IsSMTP();
        $mail->Host = variables::$MailHost ;
        $mail->SMTPSecure = variables::$SMTPSecure;
        $mail->SMTPAuth = variables::$SMTPAuth;
        $mail->Username = variables::$MailUsername;
        $mail->Password = variables::$MailPassword;
        $mail->SetFrom(variables::$MailFromEmail, variables::$MailFromShortName);
        $mail->Subject = $Subject;
        $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
        $mail->MsgHTML($content);
        $mail->AddAddress($Email, $Username);
        $mail->Send();

//        require_once "Mail.php";
//
//        $from = "<from.gmail.com>";
//        $to = "<to.yahoo.com>";
//        $subject = "Hi!";
//        $body = "Hi,\n\nHow are you?";
//
//        $host = "ssl://smtp.gmail.com";
//        $port = "465";
//        $username = "myaccount@gmail.com";  //<> give errors
//        $password = "password";
//
//        $headers = array ('From' => $from,
//            'To' => $to,
//            'Subject' => $subject);
//        $smtp = Mail::factory('smtp',
//            array ('host' => $host,
//                'port' => $port,
//                'auth' => true,
//                'username' => $username,
//                'password' => $password));
//
//        $mail = $smtp->send($to, $headers, $body);
//
//        if (PEAR::isError($mail)) {
//            echo("<p>" . $mail->getMessage() . "</p>");
//        } else {
//            echo("<p>Message successfully sent!</p>");
//        }


//            $msg = New MailMessage();
//            $msg->From = New MailAddress($msgFrom);
//            $msg->IsBodyHtml = True;
//            $msg->Priority = $MailPriority->High;
//
//
//            $msg->To[] = New MailAddress($msgTo);
//
//            $msg->Subject = $subject;
//            $msg->Body = $message;
//
//            $mailClient = New SmtpClient();
//            Try {
//                $mailClient->Send($msg);
//                Return True;
//            }Catch (Exception $ex) {
//                Return False;
//            }
    }

}


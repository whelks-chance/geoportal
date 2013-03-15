<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 12/03/13
 * Time: 19:04
 * To change this template use File | Settings | File Templates.
 */


Public Class sendEmail{

    Public Function SendEmail($msgFrom , $msgTo , $cc , $subject , $message ) {

        require_once "Mail.php";

        $from = "<from.gmail.com>";
        $to = "<to.yahoo.com>";
        $subject = "Hi!";
        $body = "Hi,\n\nHow are you?";

        $host = "ssl://smtp.gmail.com";
        $port = "465";
        $username = "myaccount@gmail.com";  //<> give errors
        $password = "password";

        $headers = array ('From' => $from,
            'To' => $to,
            'Subject' => $subject);
        $smtp = Mail::factory('smtp',
            array ('host' => $host,
                'port' => $port,
                'auth' => true,
                'username' => $username,
                'password' => $password));

        $mail = $smtp->send($to, $headers, $body);

        if (PEAR::isError($mail)) {
            echo("<p>" . $mail->getMessage() . "</p>");
        } else {
            echo("<p>Message successfully sent!</p>");
        }


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


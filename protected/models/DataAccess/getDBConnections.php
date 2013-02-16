<?

//Imports Npgsql
//Imports System.Data
//Imports System.Web
//Imports GeoPortal.GeoPortal.Models.BusinessLogic


//Namespace GeoPortal.Models.Data

class getDBConnections {

    Private $liveServer = false; // As Boolean
    Private $cnn;//  = new NpgsqlConnection(); // As Npgsql.NpgsqlConnection

    Public function ew(){

        If (HttpContext.Current.Request.IsLocal) {
            $liveServer = True;
        }Else{
            $liveServer = True;
        }
    }

    Public Function getDBConnection($DBName){ // As Npgsql.NpgsqlConnection

        // Optional ByVal DBName As String = "GeoPortal"

        If ($liveServer = False) {
            //            $connString = "Server=localhost;Port=5433;Database=" . $DBName . ";User Id=postgres;Password=January1981" .
            //          '"Server=localhost;Port=5433;Database=" + DBName + ";User Id=rfry;Password=January1981"';
            $connString = "host=192.168.56.102 port=7007 dbname=" . $DBName . " user=postgres password=postgres";

        }Else {
            // 'change this string according to whether target build is for Glam server or Amazon server
//                $connString = "Server=localhost;Port=5433;Database=" . $DBName . ";User Id=postgres;Password=January1981"
            //              'Server=193.63.128.226;Port=5433;Database=" + DBName + ";User Id=rfry;Password=January1981"';
            $connString = "host=192.168.56.102 port=7007 dbname=" . $DBName . " user=postgres password=postgres";
        }
        $cnn = pg_connect($connString);

        Return $cnn;

    }

    Public Function insertUser(regUser $userdetails,  $conn){

        //ByVal userdetails As regUser, ByVal conn As NpgsqlConnection

        $insertString = "INSERT INTO alphausersdetails(id, username, password, firstname, lastname, email)".
            " VALUES(DEFAULT,'" .
            $userdetails->UserName . "'," .
            "crypt ('" . $userdetails->Password . "', gen_salt('bf')),'" .
            $userdetails->FirstName . "','" .
            $userdetails->LastName . "','" .
            $userdetails->Email . "')";

        $cmd = pg_query($conn, $insertString);
//            Dim cnt As Integer
//            conn.Open()
//            cnt = cmd.ExecuteNonQuery()
//            conn.Close()

        If (pg_num_rows($cmd) <> 0) {
            Return False;
        }Else{
            Return True;
        }

    }

//        Public Function getUser(ByVal userName As String, ByVal Password As String) As UserDetails
//
//            Dim loginStr As String = "Select uid, username, firstname, lastname, email from userdetails where username = '" + userName + "' and  password = crypt('" + Password + "', password)"
//
//            Dim cmd As NpgsqlCommand = New NpgsqlCommand(loginStr, cnn)
//            cnn.Open()
//
//            Dim DR As NpgsqlDataReader = cmd.ExecuteReader
//
//            If DR.Read Then
//                Dim user As New UserDetails
//
//                user.FirstName = DR("firstname")
//                user.LastName = DR("lastname")
//                user.UserName = DR("username")
//                user.Email = DR("email")
//                user.UID = DR("UID")
//                user.success = True
//                user.message = "Sucessfully Logged in as " + userName + "!"
//                cnn.Close()
//
//                HttpContext.Current.Session.Add("User", user)
//
//                Return user
//            Else
//                Dim user As New UserDetails
//                user.success = False
//                user.message = "Incorrect Login Details. Please Try Again!"
//
//                cnn.Close()
//                Return user
//            End If
//
//
//
//        End Function
//
//
//        Public Function getMyAccount(ByVal user As UserDetails) As myAccount
//            Dim myACDetails As New MyAccountDetails()
//
//            myACDetails.FirstName = user.FirstName
//            myACDetails.LastName = user.LastName
//            myACDetails.Email = user.Email
//            myACDetails.UserName = user.UserName
//
//            Dim myACDetailscStr As String = "SELECT * FROM bio WHERE uid = " + user.UID
//
//            Dim cmd As New NpgsqlCommand(myACDetailscStr, cnn)
//            cnn.Open()
//            Dim DR As NpgsqlDataReader = cmd.ExecuteReader()
//
//            If DR.Read Then
//                myACDetails.Bio = DR("biotext").ToString
//                myACDetails.Email2 = DR("email2").ToString
//                myACDetails.Telephone = DR("telephone").ToString
//                myACDetails.Address = DR("Address").ToString
//                myACDetails.Institution = DR("Institution").ToString
//
//            End If
//            cnn.Close()
//
//            Dim myAC As New myAccount
//            myAC.data = myACDetails
//            myAC.success = True
//            //' myAC.message = "User Account for " + user.FirstName + " " + user.LastName
//
//            Return myAC
//        End Function
//
//
//        Public Function updateMyAccount(ByVal user As UserDetails, ByVal myAccount As MyAccountDetails, ByVal UID As Integer)
//
//
//            Dim updateUserStr As String = "UPDATE userdetails SET username ='" & user.UserName & "', firstname ='" & user.FirstName & "', lastname ='" & user.LastName & "', email='" & user.Email & "';"
//
//            Dim updateBio As String = "UPDATE bio SET institution ='" & myAccount.Institution & "', biotext ='" & myAccount.Bio & "', telephone = '" & myAccount.Telephone & "',  address = '" & myAccount.Address & "';"
//
//            Dim command As New NpgsqlCommand(updateUserStr & updateBio, cnn)
//
//            cnn.Open()
//            Dim int As Integer = command.ExecuteNonQuery()
//            cnn.Close()
//            If int <> 0 Then
//                Return True
//            Else
//                Return False
//            End If
//
//        End Function
//
//
//        Public Function ChangePassword(ByVal UID As Integer, ByVal oldPW As String, ByVal newPW As String) As jsonMsg
//
//            Dim checkoldPWstr As String = "Select uid from userdetails where uid = '" + UID.ToString + "' and  password = crypt('" + oldPW + "', password)"
//            Dim message As New jsonMsg
//            Dim cmd As New NpgsqlCommand(checkoldPWstr, cnn)
//            cnn.Open()
//            Dim DR As NpgsqlDataReader = cmd.ExecuteReader
//            If DR.Read Then
//                cnn.Close()
//                Dim updatePWStr As String = "Update userdetails set password = crypt('" + newPW + "', gen_salt('bf'))"
//                cmd = New NpgsqlCommand(updatePWStr, cnn)
//
//                cnn.Open()
//                If cmd.ExecuteNonQuery = 1 Then
//                    cnn.Close()
//                    message.success = True
//                    message.message = "Sucessfully changed password!"
//                Else
//                    cnn.Close()
//                    message.success = False
//                    message.message = "Error changing password! Please try again"
//                End If
//            Else
//                message.success = False
//                message.message = "Please check your old password and try again!"
//            End If
//            cnn.Close()
//            Return message
//
//        End Function

}



Imports GeoPortal.GeoPortal.Models.BusinessLogic
Imports GeoPortal.GeoPortal.Models.Data

Namespace GeoPortal
    Public Class MessageController
        Inherits System.Web.Mvc.Controller

        '
        ' GET: /Message

        Function submitAbug(ByVal name As String, ByVal email As String, ByVal sendDate As String, ByVal message As String, ByVal activity As String) As JsonResult

            Dim msg As New sendEmail()
            Dim result As New jsonMsg

            Dim msgbody As New StringBuilder()
            msgbody.AppendLine("From: " & name & "</br>")
            msgbody.AppendLine("Email: " & email & "</br>")
            msgbody.AppendLine("Report:" & "</br>")
            msgbody.AppendLine(message & "</br>")
            msgbody.AppendLine("Activity:" & "</br>")
            msgbody.AppendLine(activity & "</br>")


            If (msg.SendEmail(email, "WISERD@glam.ac.uk", "", "Bug Report", msgbody.ToString())) Then
                result.success = True
                result.message = "Thankyou " + name + ",your bug report has been sucessfully submited."

            Else
                result.success = False
                result.message = "Error Sending Bug report, please try again!"

            End If


            Return Json(result)
        End Function

    End Class
End Namespace
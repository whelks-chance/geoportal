Imports GeoPortal.GeoPortal.Models.BusinessLogic
Imports GeoPortal.GeoPortal.Models.Data

Namespace GeoPortal
    Public Class AccountController
        Inherits System.Web.Mvc.Controller
        Private debug As Boolean = True
        Private Db As New getDBConnections()
        ' GET: /Account



        Function LogOn(ByVal Username As String, ByVal password As String) As JsonResult


            Db.getDBConnection()
            Dim user As UserDetails = Db.getUser(Username, password)

            Return Me.Json(user)

        End Function

        <CompressFilter()>
        Function getMyDetails() As JsonResult
            If debug = True Then

                Dim db As New getDBConnections
                db.getDBConnection()
                db.getUser("rfry", "Jan1981")

            End If

            If Not Session("User") Is Nothing Then

                Dim user As UserDetails = Session("User")

                Db.getDBConnection()

                Dim myAC As myAccount = Db.getMyAccount(user)

                Return Json(myAC)
            Else
                Dim msg As New jsonMsg()
                msg.success = False
                msg.message = "Not logged In!"

                Return Json(msg)
            End If

        End Function
        <CompressFilter()>
        Function UpdateMyDetails(ByVal UserName As String, ByVal firstName As String, ByVal lastName As String, ByVal Email As String, ByVal Email2 As String, ByVal Bio As String, ByVal Institution As String, ByVal Telephone As String, ByVal Address As String) As JsonResult
            Dim result As New jsonMsg
            Dim newUserDetails As New UserDetails

            newUserDetails.FirstName = firstName
            newUserDetails.LastName = lastName
            newUserDetails.Email = Email
            newUserDetails.UserName = UserName

            Dim updateBio As New MyAccountDetails

            updateBio.Bio = Bio
            updateBio.Address = Address
            updateBio.Institution = Institution
            updateBio.Telephone = Telephone

            Dim update As New getDBConnections
            update.getDBConnection()
            If update.updateMyAccount(newUserDetails, updateBio, Session.Item("User").UID) Then
                result.message = "Your Account was successfully updated!"
                result.success = True

            Else
                result.message = "There was a problem updating your account - please try again!"
                result.success = False

            End If

            Return Json(result)

        End Function

        <CompressFilter()>
        Function ChangePassword(ByVal oldPassword As String, ByVal newPW1 As String) As JsonResult
            Dim user As UserDetails = Session("User")
            Dim db As New getDBConnections
            db.getDBConnection()
            Return Json(db.ChangePassword(user.UID, oldPassword, newPW1))

        End Function

        

   

    End Class
End Namespace
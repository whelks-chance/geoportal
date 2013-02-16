Imports Npgsql
Imports GeoPortal.GeoPortal.Models.BusinessLogic

Namespace GeoPortal.Models.Data
    Public Class fileUpload
        Private cnn As Npgsql.NpgsqlConnection
        Public Sub New()

            Dim DB As New getDBConnections

            cnn = DB.getDBConnection

        End Sub


        Public Function uploadFiletoDB(ByVal bytes As [Byte](), ByVal type As String, ByVal UID As Integer) As jsonMsg



            Dim Update As String = "Update bio SET avatar=@data where UID =" & UID



            Dim sqlcommand As NpgsqlCommand = New NpgsqlCommand(Update, cnn)
            'get image from upload stream - checking that it is an image        

            'create sql parameters to read byte array into DB
            ' sqlcommand.Parameters.AddWithValue("@data", bytes)

            cnn.Open()
            Dim i As Integer = sqlcommand.ExecuteNonQuery()
            cnn.Close()
            Dim result As New jsonMsg
            If i <> 1 Then
                result.message = "Error uploading to DB please try again!"
                result.success = False
            Else
                result.success = True
                result.message = "Upload Sucessful!"

            End If
            Return result
        End Function



    End Class
End Namespace



Imports Npgsql
Imports System.Data

Namespace GeoPortal.Models.Data
    Public Class SearchDB

        Private liveServer As Boolean
        Private cnn As Npgsql.NpgsqlConnection

        Public Sub New()

            If HttpContext.Current.Request.IsLocal Then
                liveServer = False
            Else
                liveServer = True
            End If

        End Sub


        Public Function createDBConnection() As Npgsql.NpgsqlConnection

            cnn = New NpgsqlConnection
            If liveServer = False Then
                cnn.ConnectionString = "Server=localhost;Port=5432;Database=Survey Data;User Id=rfry;Password=January1981"
            ElseIf liveServer = True Then
                cnn.ConnectionString = My.Settings.cnnServer.ToString()
            End If
            Return cnn

        End Function


        Public Function SimpleSearch(ByVal keywords As Dictionary(Of String, String)) As ArrayList




        End Function


    End Class
End Namespace


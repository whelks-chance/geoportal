<%@ WebHandler Language="VB" Class="LoadDBImage" %>
  

Imports System
Imports System.Web
Imports Npgsql
Imports System.IO
Imports GeoPortal

Public Class LoadDBImage : Implements IHttpHandler

    Public Sub ProcessRequest(ByVal context As HttpContext) Implements IHttpHandler.ProcessRequest
        Dim identity As String
        If Not context.Request.QueryString("UID") Is Nothing Then
            identity = context.Request.QueryString("UID")
        Else
            Throw New ArgumentException("No parameter specified")
        End If

        context.Response.ContentType = "image/jpeg"
        Dim strm As Stream = ShowImage(identity)
        Dim buffer As Byte() = New Byte(4095) {}
        Dim byteSeq As Integer = strm.Read(buffer, 0, 4096)

        Do While byteSeq > 0
            context.Response.OutputStream.Write(buffer, 0, byteSeq)
            byteSeq = strm.Read(buffer, 0, 4096)
        Loop
        'context.Response.BinaryWrite(buffer);
    End Sub

    Public Function ShowImage(ByVal identity As String) As Stream
        Dim db As New getDBConnections
        Using myConnection As NpgsqlConnection = db.getDBConnection()
            Dim sel As String = "SELECT avatar FROM bio WHERE uid = " & identity

            Dim myCommand As New NpgsqlCommand(sel, myConnection)
            myConnection.Open()
            'create data reader
            Dim myReader As NpgsqlDataReader = myCommand.ExecuteReader()
            Dim ms As New MemoryStream
            Dim img As Object
            If Not myReader.Read Then
                Dim image As System.Drawing.Image
                image = System.Drawing.Image.FromFile(System.Web.HttpContext.Current.Server.MapPath("~/images/no_avatar.png"))
                Dim BA As Byte()
                image.Save(ms, System.Drawing.Imaging.ImageFormat.Jpeg)
                BA = ms.ToArray
                Return New MemoryStream(BA)
                Exit Function
            End If
            img = myReader("avatar")
            Return New MemoryStream(CType(img, Byte()))


        End Using


    End Function

    Public ReadOnly Property IsReusable() As Boolean Implements IHttpHandler.IsReusable
        Get
            Return False
        End Get
    End Property

End Class
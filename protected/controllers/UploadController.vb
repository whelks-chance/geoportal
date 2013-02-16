'Imports Newtonsoft
Imports System.IO
Imports System.Drawing
Imports GeoPortal.GeoPortal.Models.BusinessLogic
Imports GeoPortal.GeoPortal.Models.Data

Namespace GeoPortal
    Public Class UploadController
        Inherits System.Web.Mvc.Controller

        '
        ' GET: /Upload
        <CompressFilter()>
        Function UploadFile(ByVal file As System.Web.HttpPostedFileBase) As String

            Dim user As UserDetails = Session.Item("User")

            Dim img As New Bitmap(file.InputStream)

            Dim dummyAbort As New System.Drawing.Image.GetThumbnailImageAbort(AddressOf ThumbnailCallback)

            Dim thumbImg As Image = img.GetThumbnailImage(100, 100, dummyAbort, System.IntPtr.Zero)
            Dim MS As New MemoryStream
            Dim BA As Byte()
            thumbImg.Save(MS, System.Drawing.Imaging.ImageFormat.Jpeg)
            BA = MS.ToArray


            Dim FU As New fileUpload

            Dim results As jsonMsg = FU.uploadFiletoDB(BA, "avatar", user.UID)

            Return "test"

            Return Newtonsoft.Json.JsonConvert.SerializeObject(results)
        End Function

        Public Function ThumbnailCallback() As Boolean
            Return False
        End Function
    End Class
End Namespace
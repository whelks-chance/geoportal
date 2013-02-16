Imports GeoPortal.GeoPortal.Models.Data

Namespace GeoPortal
    Public Class MetaDataController
        Inherits System.Web.Mvc.Controller

        '
        ' GET: /MetData
        <CompressFilter()>
        Function getQMetaDataRecords(ByVal ID As String) As String

            Dim getMeta As New getMetaData


            Dim QMetaData As GeoPortal.Models.BusinessLogic.QuestionMetaData = getMeta.getQuestionMetaData(ID)

            If Not QMetaData Is Nothing Then
                Return "({""success"": true, ""data"": " & Newtonsoft.Json.JsonConvert.SerializeObject(QMetaData) & "})"
            Else

                Return "({""success: false, ""message"": ""Error loading form - please try again""})"
            End If




        End Function
        <CompressFilter()>
        Function getSMetaDataRecords(ByVal SID As String) As String

            Dim getMeta As New getMetaData


            Dim SMetaData As GeoPortal.Models.BusinessLogic.SurveyMetaData = getMeta.getSurveyMetaData(SID)

            If Not SMetaData Is Nothing Then
                Return "({""success"": true, ""data"": " & Newtonsoft.Json.JsonConvert.SerializeObject(SMetaData) & "})"
            Else

                Return "({""success: false, ""message"": ""Error loading form - please try again""})"
            End If




        End Function


        <CompressFilter()>
        Function getRMetaDataRecords(ByVal QID As String) As String

            Dim getMeta As New getMetaData


            Dim RMetaData As GeoPortal.Models.BusinessLogic.ResponseMetaData = getMeta.getResponseMetaData(QID)

            If Not RMetaData Is Nothing Then
                Return "({""success"": true, ""data"": " & Newtonsoft.Json.JsonConvert.SerializeObject(RMetaData) & "})"
            Else

                Return "({""success: false, ""message"": ""Error loading form - please try again""})"
            End If




        End Function

        <CompressFilter()>
        Function getDCMetaDataRecords(ByVal SID As String) As String

            Dim getMeta As New getMetaData


            Dim DCMetaData As GeoPortal.Models.BusinessLogic.DublinCore = getMeta.getDublinCore(SID)

            If Not DCMetaData Is Nothing Then
                Return "({""success"": true, ""data"": " & Newtonsoft.Json.JsonConvert.SerializeObject(DCMetaData) & "})"
            Else

                Return "({""success"": false, ""message"": ""Error loading form - please try again""})"
            End If




        End Function

        <CompressFilter()>
        Function getQDCMetaDataRecords(ByVal SID As String) As String

            Dim getMeta As New getMetaData


            Dim DCMetaData As GeoPortal.Models.BusinessLogic.DublinCore = getMeta.getQDublinCore(Trim(SID))

            If Not DCMetaData Is Nothing Then
                Return "({""success"": true, ""data"": " & Newtonsoft.Json.JsonConvert.SerializeObject(DCMetaData) & "})"
            Else

                Return "({""success"": false, ""message"": ""Error loading form - please try again""})"
            End If




        End Function

        <CompressFilter()>
        Function getResponseTable(ByVal SID As String, ByVal unit As String) As String

            Dim getMeta As New getMetaData


            Dim ResponseMetaData As ArrayList = getMeta.getResponseTable(SID, unit)

            If Not ResponseMetaData Is Nothing Then
                Return "({""totalCount"":" & ResponseMetaData.Count & ",""data"":" & Newtonsoft.Json.JsonConvert.SerializeObject(ResponseMetaData) & "})"
            Else

                Return "({""success"": false, ""message"": ""Error loading form - please try again""})"
            End If




        End Function

        <CompressFilter()>
        Function getPlaces(ByVal ID As String) As String

            Dim getMeta As New getMetaData


            Dim docPlaces As ArrayList = getMeta.getPlaces(ID)



            If Not docPlaces Is Nothing Then
                Return "({""data"":" & Newtonsoft.Json.JsonConvert.SerializeObject(docPlaces) & "})"
            Else

                Return "({""success"": false, ""message"": ""Error loading form - please try again""})"
            End If


        End Function


        <CompressFilter()>
        Function getQualWordCounts(ByVal ID As String, ByVal place1 As String, ByVal place2 As String, ByVal place3 As String) As String

            Dim getMeta As New getMetaData


            Dim docWords As ArrayList = getMeta.getQualWords(ID, place1, place2, place3)



            If Not docWords Is Nothing Then
                Return "({""data"":" & Newtonsoft.Json.JsonConvert.SerializeObject(docWords) & "})"
            Else

                Return "({""success"": false, ""message"": ""Error loading form - please try again""})"
            End If


        End Function

        <CompressFilter()>
        Public Function getFields(ByVal SID As String, ByVal unit As String) As String

            Dim MD As New getMetaData

            Dim SL As ArrayList = MD.getFields(SID, unit)


            Return "{""rows"":" & Newtonsoft.Json.JsonConvert.SerializeObject(SL) & "}"
        End Function


        <CompressFilter()>
        Public Function getCloud(ByVal ID As String, ByVal callback As String) As String

            Dim MD As New getMetaData

            Return MD.getCloud(ID)


        End Function



    End Class
End Namespace
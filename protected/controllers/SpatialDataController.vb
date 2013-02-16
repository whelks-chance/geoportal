Imports GeoPortal.GeoPortal.Models.Data
Imports GeoPortal.GeoPortal.Models.BusinessLogic
Imports Newtonsoft.Json.Linq
Imports System.Xml
Imports System.IO
Imports System.Drawing

Namespace GeoPortal
    Public Class SpatialDataController
        Inherits System.Web.Mvc.Controller

        '
        ' GET: /SpatialData
        <CompressFilter()>
        Function getSpatialUnits(ByVal surveyID As String) As String

            Dim SD As New SpatialData

            Dim SU As ArrayList = SD.getAvailableUnits(surveyID)

            Return "{""rows"":" & Newtonsoft.Json.JsonConvert.SerializeObject(SU) & "}"


        End Function
        <CompressFilter()>
        Function getSpatialLabel(ByVal TableName As String) As String

            Dim SD As New SpatialData

            Dim SL As ArrayList = SD.getSpatialLabels(TableName)


            Return "{""rows"":" & Newtonsoft.Json.JsonConvert.SerializeObject(SL) & "}"
        End Function
        <CompressFilter()>
        Function getChoroFields(ByVal TableName As String) As String

            Dim SD As New SpatialData

            Dim SL As ArrayList = SD.getChoroFields(TableName)


            Return "{""rows"":" & Newtonsoft.Json.JsonConvert.SerializeObject(SL) & "}"
        End Function

        <CompressFilter()>
        Function getSpatialSubUnit(ByVal TableName As String) As String


            Dim SD As New SpatialData

            Dim SSU As ArrayList = SD.getSpatialSubUnits(TableName)

            Return "{""rows"":" & Newtonsoft.Json.JsonConvert.SerializeObject(SSU) & "}"


        End Function


        <CompressFilter()>
        Function getSpatialRefSubUnit(ByVal SID As String, ByVal MajorUnit As String) As String


            Dim SD As New SpatialData

            Dim RSSU As ArrayList = SD.getRefSpatialSubUnits(getTableName(SID, MajorUnit))

            Return "{""rows"":" & Newtonsoft.Json.JsonConvert.SerializeObject(RSSU) & "}"


        End Function


        <CompressFilter()>
        Function getRefSpatialIndivudalUnits(ByVal UnitName As String, ByVal MajorUnit As String, ByVal SubUnit As String, ByVal the_geom As String, ByVal SID As String) As String


            Dim SD As New SpatialData

            Dim ISSU As ArrayList = SD.getRefSpatialIndivudalUnits(UnitName, MajorUnit, SubUnit, the_geom, SID)

            Return "{""rows"":" & Newtonsoft.Json.JsonConvert.SerializeObject(ISSU) & "}"


        End Function

        Private Function getTableName(ByVal SID As String, ByVal unit As String) As String


            If unit = "Police Region" Then
                Return "x_" & SID & "_police_"
            ElseIf unit = "Assembly Economic Fora Area" Then
                Return "x_" & SID & "_aefa_"
            ElseIf unit = "Fire Brigade Region" Then
                Return "x_" & SID & "_fire_"
            ElseIf unit = "Lower Super Output Area" Then
                Return "x_" & SID & "_lsoa_"
            ElseIf unit = "Parliamentary Constituencies" Then
                Return "x_" & SID & "_parl_"
            ElseIf unit = "Postcode Sector" Then
                Return "x_" & SID & "_pcode_"
            ElseIf unit = "Unitary Authority" Then
                Return "x_" & SID & "_ua_"
            End If




        End Function


        <CompressFilter()>
        Public Function GenerateSpatialData(ByVal SurveyID As String, ByVal Unit As String, ByVal SubUnit As String, ByVal Outline As String, ByVal Label As String, ByVal fromColour As String, ByVal toColour As String, ByVal Choropleth As String, ByVal ChoroplethField As String, ByVal addLabels As String, ByVal ClassMethod As String, ByVal intervals As Integer) As String

            Dim SD As New SpatialData



            Dim shapes As ArrayList = SD.GenerateSpatialData(SurveyID, Unit, SubUnit, GetBool(Unit), Label, fromColour, toColour, GetBool(Choropleth), ChoroplethField, GetBool(addLabels), ClassMethod, intervals)

            If shapes.Count > 0 Then
                Return "({""success"": true, ""shapes"": " & Newtonsoft.Json.JsonConvert.SerializeObject(shapes) & "})"
            Else
                Dim msg As New GeoPortal.Models.BusinessLogic.jsonMsg
                msg.success = False
                msg.message = "No Spatial Data currently available for this record."
                Return Newtonsoft.Json.JsonConvert.SerializeObject(msg)
            End If




        End Function


        <CompressFilter()>
        Public Function getSpatialDataSets(ByVal data As String)

            Dim dataSet As New Dictionary(Of String, spatialSearchLayers)

            Dim jsons As Newtonsoft.Json.Linq.JArray = Newtonsoft.Json.JsonConvert.DeserializeObject(data)

            For Each json As JToken In jsons

                Dim layer As spatialSearchLayers = Newtonsoft.Json.JsonConvert.DeserializeObject(Of spatialSearchLayers)(json.ToString)
                dataSet.Add(layer.name, layer)
            Next

            Dim spatialSearch As New SpatialData

            Dim full_data As Dictionary(Of String, spatialSearchLayers) = spatialSearch.getChosenLayers(dataSet)

        End Function



        <CompressFilter()>
        Public Function SpatialSearch(ByVal geography As String, ByVal start As Integer, ByVal limit As Integer, ByVal Type As String) As String

            Dim count As Integer

            Dim SD As New SpatialData


            Dim results As ArrayList

            'Return "{""success"": true, ""data"":" & Newtonsoft.Json.JsonConvert.SerializeObject(results) & "}"

            Dim res As GeoPortal.Models.BusinessLogic.SpatialSearch2

            If geography = "" Then
                results = Session("spatialResults")
                Select Case Type
                    Case "Quant"
                        count = Session("quantCount")
                    Case "Qual"
                        count = Session("qualCount")
                    Case "Grey"

                    Case "Admin"

                End Select

                res = results(0)
            Else
                results = Nothing
                If results Is Nothing Then
                    results = SD.SpatialSearch(geography)

                    res = results(0)
                    Select Case Type
                        Case "Quant"
                            count = res.quantCount
                            Session.Add("quantCount", count)
                        Case "Qual"
                            count = res.qualCount
                            Session.Add("qualCount", count)
                        Case "Grey"

                        Case "Admin"

                    End Select

                    'Return "{""quantData"":" & Newtonsoft.Json.JsonConvert.SerializeObject(res.quantData) & "}"

                    Session.Add("spatialResults", results)

                End If
            End If


            'Dim resultsset As New GeoPortal.Models.BusinessLogic.spatialResults

            'resultsset.totalCount = count
            'resultsset.questions = Newtonsoft.Json.JsonConvert.SerializeObject(results(0).quantData)


            Dim pageResults As New ArrayList

            Dim cnt As Integer = start
            Dim cnt_end As Integer = cnt + limit

            Dim Str As String = ""

            Select Case Type
                Case "Quant"

                    Do Until cnt = cnt_end Or cnt = res.quantCount

                        pageResults.Add(res.quantData(res.quantData.Keys(cnt)))
                        cnt += 1
                    Loop

                    Str = "{""totalCount"":" & count.ToString & ",""quantData"":" & Newtonsoft.Json.JsonConvert.SerializeObject(pageResults) & "}"


                Case "Qual"
                    Do Until cnt = cnt_end Or cnt = res.qualCount

                        pageResults.Add(res.qualData(res.qualData.Keys(cnt)))
                        cnt += 1
                    Loop

                    Str = "{""totalCount"":" & count.ToString & ",""qualData"":" & Newtonsoft.Json.JsonConvert.SerializeObject(pageResults) & "}"
                Case "Grey"

                Case "Admin"

            End Select

            Return Str

        End Function


        <CompressFilter()>
        Public Function VerifySpatialSearch(ByVal coords As String, ByVal type As String, ByVal dist As Integer) As String

            Dim SD As New SpatialData
            Dim msg As New jsonMsg
            If SD.VerifySpatialSearch(coords, type, dist) Then
                msg.success = True
                msg.message = "Results Found"
            Else
                msg.success = False
                msg.message = "No Results found within search distance"

            End If



            Return Newtonsoft.Json.JsonConvert.SerializeObject(msg)


        End Function

        <CompressFilter()>
        Public Function GenerateQualSpatialData(ByVal ID As String) As String

            Dim SD As New SpatialData
            Dim shapes As ArrayList = SD.generateQualSpatialData("red", ID)

            If shapes.Count > 0 Then
                Return "({""success"": true, ""shapes"": " & Newtonsoft.Json.JsonConvert.SerializeObject(shapes) & "})"
            Else
                Dim msg As New GeoPortal.Models.BusinessLogic.jsonMsg
                msg.success = False
                msg.message = "No Spatial Data currently available for this record."
                Return Newtonsoft.Json.JsonConvert.SerializeObject(msg)
            End If

        End Function


        Private Function GetBool(ByVal Status As String) As Boolean

            If Status = "on" Then
                Return True
            Else
                Return False
            End If

        End Function


        Public Function getMinMax(ByVal TableName As String, ByVal ColName As String) As String

            Dim getMin As String = "Select MIN(" & ColName & ") from " & TableName
            Dim getMax As String = "Select MAX(" & ColName & ") from " & TableName

            Dim db As New getDBConnections()

            Dim dBCnn As Npgsql.NpgsqlConnection = db.getDBConnection("Survey_Data")
            Dim cmd As New Npgsql.NpgsqlCommand(getMin, dBCnn)

            dBCnn.Open()

            Dim min As Integer = cmd.ExecuteScalar()

            cmd.CommandText = getMax

            Dim max As Integer = cmd.ExecuteScalar()

            dBCnn.Close()


            Dim minMax(1) As Integer
            minMax(0) = min
            minMax(1) = max

            Return Newtonsoft.Json.JsonConvert.SerializeObject(minMax).ToString

        End Function

        <CompressFilter()>
        Public Function DynamicSLD2(ByVal type As String, ByVal fromColour As String, ByVal toColour As String, ByVal fieldName As String, ByVal min As Integer, ByVal max As Integer, ByVal classes As Integer, ByVal layer As String, ByVal labelName As String) As String

            Dim SLD As New SLD

            Dim xmlDoc As XmlDocument = SLD.DynamicSLD2(type, fromColour, toColour, fieldName, min, max, classes, layer, labelName)

            Dim sw As New StringWriter()
            Dim txml As New XmlTextWriter(sw)
            xmlDoc.WriteTo(txml)


            Return sw.ToString
        End Function




        <CompressFilter()>
        Public Function getFeatureInfo(ByVal lat As Double, lon As Double, tableIDs As String) As String

            ' create a list of tables from the JSON 
            Dim tables As List(Of IdentifyFeatures) = Newtonsoft.Json.JsonConvert.DeserializeObject(Of List(Of IdentifyFeatures))(tableIDs)
            Dim SD As New SpatialData
            Dim results As ArrayList = SD.getFeatureInfoTable(lat, lon, tables)


            Return Newtonsoft.Json.JsonConvert.SerializeObject(results)


        End Function

    End Class
End Namespace
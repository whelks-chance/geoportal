Imports GeoPortal.GeoPortal.Models.BusinessLogic
Imports System.Drawing
Imports System.IO

Namespace GeoPortal.Models.Data

    Public Class SpatialData

        Public Function getAvailableUnits(ByVal SurveyID As String) As ArrayList

            Dim dc As New getDBConnections



            Dim cnn As Npgsql.NpgsqlConnection = dc.getDBConnection("Survey_Data")

            Dim selectStr As String = "Select * from survey_spatial_link where surveyid = '" & SurveyID.ToLower & "';"

            Dim results As New ArrayList

            Dim cmd As New Npgsql.NpgsqlCommand(selectStr, cnn)

            cnn.Open()

            Dim DR As Npgsql.NpgsqlDataReader = cmd.ExecuteReader


            Do While DR.Read
                Dim units As New AvailableSpatialUnits
                units.spatial_id = Trim(DR.Item("spatial_id"))
                units.long_start = Trim(DR.Item("long_start").ToString)
                units.long_finish = Trim(DR.Item("long_finish").ToString)
                units.Name = getFullName(DR.Item("spatial_id"))
                units.short_name = DR.Item("spatial_id").Split("_")(3).ToString.ToLower

                results.Add(units)
            Loop

            cnn.Close()

            Return results

        End Function


        Public Function getSpatialLabels(ByVal TableName As String) As ArrayList

            Dim dc As New getDBConnections

            Dim cnn As Npgsql.NpgsqlConnection = dc.getDBConnection("Survey_Data")

            Dim selectStr As String = "Select column_name as name from information_schema.columns where table_name ='" & TableName & "';"


            Dim results As New ArrayList

            Dim cmd As New Npgsql.NpgsqlCommand(selectStr, cnn)

            cnn.Open()

            Dim DR As Npgsql.NpgsqlDataReader = cmd.ExecuteReader


            Do While DR.Read
                Dim label As New SpatialLabels
                label.Name = Trim(DR.Item("Name").ToString)
                results.Add(label)
            Loop

            cnn.Close()

            Return results

        End Function

        Public Function getSpatialSubUnits(ByVal TableName As String) As ArrayList

            Dim dc As New getDBConnections

            Dim cnn As Npgsql.NpgsqlConnection = dc.getDBConnection("Survey_Data")

            Dim selectStr As String = "Select * from " & TableName & ";"


            Dim results As New ArrayList

            Dim cmd As New Npgsql.NpgsqlCommand(selectStr, cnn)

            cnn.Open()

            Dim DR As Npgsql.NpgsqlDataReader = cmd.ExecuteReader

            Dim spatialSUAll As New SpatialSubUnits
            spatialSUAll.Name = "All"
            results.Add(spatialSUAll)

            Do While DR.Read
                Dim spatialSU As New SpatialSubUnits
                spatialSU.Name = Trim(DR.Item(1).ToString)
                results.Add(spatialSU)
            Loop

            cnn.Close()

            Return results

        End Function


        Public Function getRefSpatialSubUnits(ByVal TableName As String) As ArrayList

            Dim dc As New getDBConnections

            Dim cnn As Npgsql.NpgsqlConnection = dc.getDBConnection("Survey_Data")

            Dim selectStr As String = "Select * from " & TableName & ";"


            Dim results As New ArrayList

            Dim cmd As New Npgsql.NpgsqlCommand(selectStr, cnn)

            cnn.Open()

            Dim DR As Npgsql.NpgsqlDataReader = cmd.ExecuteReader


            Dim spatialSUMap As New SpatialSubUnits
            spatialSUMap.Name = "Current Map Extent"
            results.Add(spatialSUMap)


            Do While DR.Read
                Dim spatialSU As New SpatialSubUnits
                spatialSU.Name = Trim(DR.Item(1).ToString)
                results.Add(spatialSU)
            Loop

            cnn.Close()

            Return results

        End Function

        Public Function getRefSpatialIndivudalUnits(ByVal UnitName As String, ByVal MajorUnit As String, ByVal SubUnit As String, ByVal the_geom As String, ByVal SID As String) As ArrayList

            Dim dc As New getDBConnections

            Dim cnn As Npgsql.NpgsqlConnection = dc.getDBConnection("Survey_Data")
            Dim big_geom As String
            Dim suffix As String = ""

            If the_geom = "N/A" Then

                suffix = getTableName(SID, MajorUnit)

                big_geom = "(SELECT the_geom from spatialdata." & suffix & " WHERE area_name ='" & SubUnit & "')"
            Else
                big_geom = the_geom

            End If


            Dim selectStr As String = "SELECT * FROM public." & UnitName & " WHERE ST_Intersects('" & big_geom & "', the_geom);"


            Dim results As New ArrayList

            Dim cmd As New Npgsql.NpgsqlCommand(selectStr, cnn)

            cnn.Open()

            Dim DR As Npgsql.NpgsqlDataReader = cmd.ExecuteReader

            Dim spatialSUAll As New SpatialSubUnits
            spatialSUAll.Name = "All"
            results.Add(spatialSUAll)

            Do While DR.Read
                Dim spatialSU As New SpatialSubUnits
                spatialSU.Name = Trim(DR.Item(1).ToString)
                results.Add(spatialSU)
            Loop

            cnn.Close()

            Return results

        End Function


        Public Function getChoroFields(ByVal TableName As String) As ArrayList

            Dim dc As New getDBConnections

            Dim cnn As Npgsql.NpgsqlConnection = dc.getDBConnection("Survey_Data")

            Dim selectStr As String = "Select column_name as name from information_schema.columns where table_name ='" & TableName & "';"


            Dim results As New ArrayList

            Dim cmd As New Npgsql.NpgsqlCommand(selectStr, cnn)

            cnn.Open()

            Dim DR As Npgsql.NpgsqlDataReader = cmd.ExecuteReader

            'Advance reader through the first two records as not applicable for choropleth mapping
            DR.Read()
            DR.Read()


            Do While DR.Read

                Dim label As New SpatialLabels
                label.Name = Trim(DR.Item("Name").ToString)
                results.Add(label)
            Loop

            cnn.Close()

            Return results

        End Function


        Public Function getFullName(ByVal spatial_id As String) As String
            Dim Name As String = ""

            Dim postFix As String = spatial_id.Split("_")(3).ToString.ToLower

            If postFix = "aefa" Then
                Name = "Assembly Economic Fora Area"
            ElseIf postFix = "fire" Then
                Name = "Fire Brigade Region"
            ElseIf postFix = "lsoa" Then
                Name = "Lower Super Output Area"
            ElseIf postFix = "parl" Then
                Name = "Parliamentary Constituencies"
            ElseIf postFix = "pcode" Then
                Name = "Postcode Sector"
            ElseIf postFix = "police" Then
                Name = "Police Region"
            ElseIf postFix = "ua" Then
                Name = "Unitary Authority"
            End If

            Return Name

        End Function


        Public Function GenerateSpatialData(ByVal SurveyID As String, ByVal Unit As String, ByVal SubUnit As String, ByVal Outline As Boolean, ByVal Label As String, ByVal fromColour As String, ByVal toColour As String, ByVal Choropleth As Boolean, ByVal ChoroplethField As String, ByVal addLabels As Boolean, ByVal ClassMethod As String, ByVal Interval As Integer) As ArrayList

            Dim results As New ArrayList

            Dim db As New getDBConnections

            Dim cnn As Npgsql.NpgsqlConnection = db.getDBConnection("Survey_Data")

            Dim DT As New DataTable

            Dim Link_ID As String = ""

            Link_ID = getTableName(SurveyID, Unit)


            If SubUnit = "All" Then
                Dim DA As New Npgsql.NpgsqlDataAdapter("SELECT area_name, total, successful, refused, no_contact, ineligible, other, response_rate, adjusted_rr, ST_AsEWKT(st_simplifypreservetopology(the_geom, 0.001)) as the_geom FROM " & Link_ID.ToLower & ";", cnn)
                DA.Fill(DT)
            Else

                Dim DA As New Npgsql.NpgsqlDataAdapter("SELECT area_name, total, successful, refused, no_contact, ineligible, other, response_rate, adjusted_rr, ST_AsEWKT(st_simplifypreservetopology(the_geom, 0.001)) as the_geom FROM " & Link_ID.ToLower & " WHERE area_name='" & SubUnit & "';", cnn)
                DA.Fill(DT)
            End If




            Dim cnt As Integer = DT.Rows.Count()


            If cnt > 0 Then
                'calculate choropleth stats

                'If Interval > DT.Rows.Count Then
                '    Interval = DT.Rows.Count
                'End If


                For Each row As DataRow In DT.Rows
                    Dim total As Integer = row.Item("total") 'DT.Compute("Sum(" & ChoroplethField & ")", Nothing)

                    Dim colorList As Dictionary(Of Integer, Color) = generateColourRange(fromColour, toColour, generateEqualInterval(total, Interval), Interval)

                    If IsDBNull(row.Item("the_geom")) Then

                    Else
                        Dim SU As New ResponseSpatialUnits
                        SU.TotalResp = row.Item("total")
                        SU.ChoroField = ChoroplethField
                        SU.ChoroValue = row.Item(ChoroplethField)
                        SU.LabelField = row.Item(Label)
                        SU.Name = row.Item("area_name")
                        SU.WKT = row.Item("the_geom").ToString.Split(";")(1)
                        SU.Colour = getColour(colorList, row.Item(ChoroplethField))

                        results.Add(SU)
                    End If
                Next
            End If

            Return results

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



        Public Function generateEqualInterval(ByVal Total As Integer, ByVal intervals As Integer)

            ' Dim 

            Dim intervalRange As Double = Math.Round(Total / intervals, System.MidpointRounding.AwayFromZero)



            Return intervalRange
        End Function


        Public Function generateColourRange(ByVal fromColour As String, ByVal ToColour As String, ByVal intervalRange As Integer, ByVal intervalCount As Double) As Dictionary(Of Integer, Color)


            Dim classInterval As Integer = intervalRange

            Dim startColor As Color = ColorTranslator.FromHtml(fromColour)
            Dim endColor As Color = ColorTranslator.FromHtml(ToColour)

            Dim colourList As New Dictionary(Of Integer, Color)
            Dim i As Integer = 0


            Dim rMax As Integer = endColor.R
            Dim rMin As Integer = startColor.R
            Dim gMax As Integer = endColor.G
            Dim gMin As Integer = startColor.G
            Dim bMax As Integer = endColor.B
            Dim bMin As Integer = startColor.B



            Do While i < intervalCount

                Dim rAverage As Integer = rMin + CInt((rMax - rMin) * i / intervalCount)
                Dim gAverage As Integer = gMin + CInt((gMax - gMin) * i / intervalCount)
                Dim bAverage As Integer = bMin + CInt((bMax - bMin) * i / intervalCount)

                colourList.Add(classInterval, Color.FromArgb(rAverage, gAverage, bAverage))
                classInterval = (classInterval + intervalRange) + 1
                i += 1
            Loop



            Return colourList


        End Function

        Public Function getColour(ByVal Colours As Dictionary(Of Integer, Color), ByVal value As Integer) As String

            For Each item In Colours
                If value.CompareTo(item.Key) < 0 Then
                    Return ColorTranslator.ToHtml(item.Value)
                End If
            Next
        End Function


        Public Function VerifySpatialSearch(ByVal coords As String, ByVal type As String, ByVal dist As Integer) As Boolean


            Dim getTables As String = "SELECT * FROM geometry_columns where f_table_schema = 'public';"

            Dim DB As New getDBConnections
            Dim cnn As Npgsql.NpgsqlConnection = DB.getDBConnection("Survey_Data")

            Dim DA As Npgsql.NpgsqlDataAdapter = New Npgsql.NpgsqlDataAdapter(getTables, cnn)

            Dim DT As New DataTable

            DA.Fill(DT)


            For Each row As DataRow In DT.Rows

                Dim tableName As String = row.Item("f_table_name")
                Dim geom_col As String = row.Item("f_geometry_column")

                Dim selStr As New StringBuilder
                selStr.Append("SELECT * from  ")
                selStr.Append(tableName & " ")
                selStr.Append("WHERE ST_DWithin(ST_Transform(ST_SetSRID(ST_MakePoint(")
                selStr.Append(coords)
                selStr.Append("), 4326), 4326) ,ST_Transform(")
                selStr.Append(tableName & "." & geom_col & ",")
                selStr.Append(" 4326)," & dist & ") LIMIT 1;")

                Dim DR As Npgsql.NpgsqlDataReader

                Dim cmd As New Npgsql.NpgsqlCommand(selStr.ToString, cnn)

                cnn.Open()
                DR = cmd.ExecuteReader()

                If DR.Read Then
                    cnn.Close()
                    Return True
                End If

                cnn.Close()



            Next


            Return False



        End Function


        Public Function SpatialSearch(ByVal geography As String) As ArrayList

            Dim results As New ArrayList
            Dim getTables As String = "SELECT * FROM geometry_columns where f_table_schema = 'public';"

            Dim DB As New getDBConnections
            Dim cnn As Npgsql.NpgsqlConnection = DB.getDBConnection("Survey_Data")

            Dim DA As Npgsql.NpgsqlDataAdapter = New Npgsql.NpgsqlDataAdapter(getTables, cnn)

            Dim DT As New DataTable

            DA.Fill(DT)
            Dim SS As New SpatialSearch2

            Dim tableMinMax As New Dictionary(Of String, Integer())

            For Each row As DataRow In DT.Rows
                Dim selStr As New StringBuilder
                Dim tableName As String = row.Item("f_table_name")
                Dim geom_col As String = row.Item("f_geometry_column")


                selStr.Append("SELECT area_name from " & tableName)
                selStr.Append(" WHERE ST_Intersects(ST_Transform(ST_GeometryFromText('" & geography & "', 27700), 4326)," & geom_col & ");")


                Dim DataAdapter As New Npgsql.NpgsqlDataAdapter(selStr.ToString, cnn)
                Dim resultsTable As New DataTable
                DataAdapter.Fill(resultsTable)

                Dim surveyDetails As Dictionary(Of String, String) = getSurveyNameYear(tableName)


                For Each datarow As DataRow In resultsTable.Rows
                    Dim quantsData As New quantDataRecord2

                    If surveyDetails.Count = 0 Then
                        quantsData.sName = tableName
                        quantsData.sYear = 9999
                    Else
                        quantsData.sName = surveyDetails.Item("surveyName")
                        quantsData.sYear = surveyDetails.Item("year")
                    End If


                    'Convert text column to integer value and get min and max values
                    Dim min As String = "Select min(cast(successful as int)) from " & tableName
                    Dim max As String = "Select max(cast(successful as int)) from " & tableName


                    quantsData.geography = tableName.ToString.Split("_")(3).ToUpper
                    quantsData.tName = tableName
                    quantsData.sID = tableName.Split("_")(1) & "_" & tableName.Split("_")(2)

                    If Not tableMinMax.ContainsKey(tableName) Then
                        Dim cmd As New Npgsql.NpgsqlCommand(min, cnn)
                        If cnn.State = ConnectionState.Closed Then
                            cnn.Open()

                        End If
                        Dim minMax(1) As Integer

                        quantsData.min = cmd.ExecuteScalar()

                        cmd.CommandText = max

                        quantsData.max = cmd.ExecuteScalar()

                        cnn.Close()

                        minMax(0) = quantsData.min
                        minMax(1) = quantsData.max

                        tableMinMax.Add(tableName, minMax)

                    Else


                        quantsData.min = tableMinMax.Item(tableName)(0)
                        quantsData.max = tableMinMax.Item(tableName)(1)
                    End If



                    quantsData.gName = datarow.Item("area_name")

                    If SS.quantData.ContainsKey(tableName) Then
                        SS.quantData(tableName).gName += "; " & quantsData.gName

                    Else

                        SS.quantData.Add(tableName, quantsData)
                        SS.quantCount += 1
                    End If


                Next


            Next
            ' Qual(Data)

            Dim qcnn As Npgsql.NpgsqlConnection = DB.getDBConnection("Qual_Data")

            Dim QselStr As New StringBuilder


            QselStr.Append("SELECT * FROM qualdata.dc_info ")
            QselStr.Append(" WHERE ST_Intersects(ST_Transform(ST_GeometryFromText('" & geography & "', 27700), 4326), qualdata.dc_info.the_geom);")


            Dim QDataAdapter As New Npgsql.NpgsqlDataAdapter(QselStr.ToString, qcnn)
            Dim QresultsTable As New DataTable
            QDataAdapter.Fill(QresultsTable)


            For Each Qdatarow As DataRow In QresultsTable.Rows

                Dim coverage As String = Qdatarow.Item("coverage")

                Dim items() As String = coverage.Split(";")

                Dim locDetails As String = ""
                Dim word_stats As String = ""

                For Each place As String In items
                    If Not place = "" Then


                        locDetails = Regex.Split(place, "wordStats")(0)
                        word_stats = Regex.Split(place, "wordStats")(1)

                        word_stats = word_stats.Remove(0, word_stats.IndexOf("["))

                        word_stats = word_stats.Remove((word_stats.Length - 3), 3)

                        locDetails += "wordsStats"":" & word_stats & "}"

                        Dim places As unlockDetails = Newtonsoft.Json.JsonConvert.DeserializeObject(Of unlockDetails)(locDetails)

                        If Not places Is Nothing Then

                            Dim qualData As New qualDataRecordGroup
                            qualData.sName = Trim(Qdatarow.Item("identifier"))

                            Dim coord As New qualCoords
                            coord.lat = places.lat
                            coord.lon = places.lon
                            coord.name = places.Name
                            coord.counts = places.Occurences
                            qualData.gName.Add(coord)
                            qualData.name = Trim(Qdatarow.Item("title"))
                            qualData.creator = Trim(Qdatarow.Item("creator"))
                            qualData.thematic = Trim(Qdatarow.Item("thematic_group"))
                            qualData.recorddate = Trim(Qdatarow.Item("created"))

                            If SS.qualData.ContainsKey(Qdatarow.Item("identifier")) Then
                                SS.qualData.Item(Qdatarow.Item("identifier")).gName.Add(coord)
                            Else
                                SS.qualData.Add(Qdatarow.Item("identifier"), qualData)
                                SS.qualCount += 1
                            End If
                        End If

                    End If

                Next


            Next



            results.Add(SS)

            Return results


        End Function



            'Public Function SpatialSearch(ByVal shape As String, ByVal type As String, ByVal dist As Integer) As ArrayList

            'Dim results As New ArrayList
            'Dim getTables As String = "SELECT * FROM geometry_columns where f_table_schema = 'public';"

            'Dim DB As New getDBConnections
            'Dim cnn As Npgsql.NpgsqlConnection = DB.getDBConnection("Survey_Data")

            'Dim DA As Npgsql.NpgsqlDataAdapter = New Npgsql.NpgsqlDataAdapter(getTables, cnn)

            'Dim DT As New DataTable

            'DA.Fill(DT)

            'Survey Data

            'Dim SS As New SpatialSearch
            'Dim oneDegree As Double = 110570

            'Dim approx_deg_dist As Double = (dist * 1000) / oneDegree

            'For Each row As DataRow In DT.Rows

            '    Dim tableName As String = row.Item("f_table_name")
            '    Dim geom_col As String = row.Item("f_geometry_column")




            '    Dim selStr As New StringBuilder

            '    selStr.Append("SELECT area_name, ST_AsEWKT(st_simplifypreservetopology(the_geom, 0.000225)) as the_geom from  ")
            '    selStr.Append(tableName & " ")
            '    selStr.Append("WHERE ST_Intersects(ST_Buffer(ST_SetSRID(ST_MakePoint(")
            '    selStr.Append(coords)
            '    selStr.Append("), 4326), 0.045 ")
            '    selStr.Append(tableName & "." & geom_col)
            '    selStr.Append("," & approx_deg_dist & ")")


            '    selStr.Append("SELECT area_name, ST_AsEWKT(st_simplifypreservetopology(the_geom, 0.000225)) as the_geom from  ")
            '    selStr.Append(tableName & " ")
            '    selStr.Append("WHERE ST_DWithin(ST_SetSRID(ST_MakePoint(")
            '    selStr.Append(coords)
            '    selStr.Append("), 4326),")
            '    selStr.Append(tableName & "." & geom_col)
            '    selStr.Append("," & approx_deg_dist & ")")


            '    ###With reprojection of data - more accurate but takes far longer to run....###
            '    selStr.Append("SELECT area_name, ST_AsEWKT(st_simplifypreservetopology(the_geom, 0.000225)) as the_geom from  ")
            '    selStr.Append(tableName & " ")
            '    selStr.Append("WHERE ST_DWithin(ST_Transform(ST_SetSRID(ST_MakePoint(")
            '    selStr.Append(coords)
            '    selStr.Append("), 4326), 27700), ST_Transform( ")
            '    selStr.Append(tableName & "." & geom_col)
            '    selStr.Append(",27700)," & (dist * 1000) & ")")



            '    Dim DataAdapter As New Npgsql.NpgsqlDataAdapter(selStr.ToString, cnn)
            '    Dim resultsTable As New DataTable
            '    DataAdapter.Fill(resultsTable)

            '    Dim surveyDetails As Dictionary(Of String, String) = getSurveyNameYear(tableName)


            '    For Each datarow As DataRow In resultsTable.Rows
            '        Dim quantsData As New quantDataRecord

            '        If surveyDetails.Count = 0 Then
            '            quantsData.surveyName = tableName
            '            quantsData.year = 9999
            '        Else
            '            quantsData.surveyName = surveyDetails.Item("surveyName")
            '            quantsData.year = surveyDetails.Item("year")
            '        End If

            '        quantsData.unit = tableName.ToString.Split("_")(3)

            '        quantsData.survey_id = tableName.Split("_")(1) & "_" & tableName.Split("_")(2)
            '        Dim geom As New geom
            '        geom.Name = datarow.Item("area_name")
            '        geom.geom = datarow.Item("the_geom").ToString.Split(";")(1)

            '        If SS.quantData.ContainsKey(tableName) Then


            '            SS.quantData.Item(tableName).the_geom.Add(datarow.Item("area_name"), geom)

            '        Else

            '            quantsData.the_geom.Add(datarow.Item("area_name"), geom)

            '            SS.quantData.Add(tableName, quantsData)
            '            SS.quantCount += 1
            '        End If


            '    Next




            'Next
            ''Qual Data

            'Dim qcnn As Npgsql.NpgsqlConnection = DB.getDBConnection("Qual_Data")

            'Dim QselStr As New StringBuilder
            'QselStr.Append("SELECT * from ")
            'QselStr.Append("qualdata.dc_info ")
            'QselStr.Append("WHERE ST_DWithin(ST_SetSRID(ST_MakePoint(")
            'QselStr.Append(coords)
            'QselStr.Append("), 4326),")
            'QselStr.Append("qualdata.dc_info.the_geom")
            'QselStr.Append("," & approx_deg_dist & ")")


            'Dim QDataAdapter As New Npgsql.NpgsqlDataAdapter(QselStr.ToString, qcnn)
            'Dim QresultsTable As New DataTable
            'QDataAdapter.Fill(QresultsTable)


            'For Each Qdatarow As DataRow In QresultsTable.Rows

            '    Dim coverage As String = Qdatarow.Item("coverage")

            '    Dim items() As String = coverage.Split(";")

            '    Dim locDetails As String = ""
            '    Dim word_stats As String = ""

            '    For Each place As String In items
            '        If Not place = "" Then


            '            locDetails = Regex.Split(place, "wordStats")(0)
            '            word_stats = Regex.Split(place, "wordStats")(1)

            '            word_stats = word_stats.Remove(0, word_stats.IndexOf("["))

            '            word_stats = word_stats.Remove((word_stats.Length - 3), 3)

            '            locDetails += "wordsStats"":" & word_stats & "}"

            '            Dim places As unlockDetails = Newtonsoft.Json.JsonConvert.DeserializeObject(Of unlockDetails)(locDetails)

            '            If Not places Is Nothing Then

            '                Dim qualData As New qualDataRecordGroup
            '                qualData.identifier = Trim(Qdatarow.Item("identifier"))

            '                Dim coord As New qualCoords
            '                coord.lat = places.lat
            '                coord.lon = places.lon
            '                coord.name = places.Name
            '                coord.counts = places.Occurences
            '                qualData.coords.Add(coord)
            '                qualData.title = Trim(Qdatarow.Item("title"))


            '                If SS.qualData.ContainsKey(Qdatarow.Item("identifier")) Then
            '                    SS.qualData.Item(Qdatarow.Item("identifier")).coords.Add(coord)
            '                Else
            '                    SS.qualData.Add(Qdatarow.Item("identifier"), qualData)
            '                    SS.qualCount += 1
            '                End If
            '            End If

            '        End If

            '    Next


            'Next

            'results.Add(SS)

            'Return results


        Private Function getSurveyNameYear(ByVal tableName As String) As Dictionary(Of String, String)

            Dim details As New Dictionary(Of String, String)

            Dim db As New getDBConnections
            Dim cnn As Npgsql.NpgsqlConnection = db.getDBConnection("Survey_Data")


            Dim selSurveyStr As String = "Select surveyid from survey_spatial_link where lower(spatial_id) ='" & Trim(tableName.ToLower) & "'"
            Dim DR1 As Npgsql.NpgsqlDataReader

            Dim cmd1 As New Npgsql.NpgsqlCommand(selSurveyStr, cnn)

            cnn.Open()


            DR1 = cmd1.ExecuteReader



            Dim SID As String = ""
            If DR1.Read Then
                SID = DR1.Item("surveyid")
            End If

            cnn.Close()




            Dim selstr As String = "Select short_title, collectionenddate from survey WHERE lower(surveyid) ='" & Trim(SID.ToLower) & "'"
            Dim DR As Npgsql.NpgsqlDataReader

            Dim cmd As New Npgsql.NpgsqlCommand(selstr, cnn)

            cnn.Open()

            DR = cmd.ExecuteReader

            If DR.Read Then
                Dim sName As String = DR.Item("short_title")

                Dim survey_date As Date = DR.Item("collectionenddate")

                Dim year As Integer = survey_date.Year
                details.Add("surveyName", sName)
                details.Add("year", year)
                cnn.Close()

            End If

            Return details



        End Function


        Public Function generateQualSpatialData(ByVal colour As String, ByVal ID As String) As ArrayList

            Dim results As New ArrayList

            Dim db As New getDBConnections

            Dim selStr As String = "Select coverage from qualdata.dc_info WHERE identifier ='" & ID & "'"

            Dim cnn As Npgsql.NpgsqlConnection = db.getDBConnection("Qual_Data")

            Dim coverage As String = ""



            Dim cmd As New Npgsql.NpgsqlCommand(selStr, cnn)
            cnn.Open()

            Dim DR As Npgsql.NpgsqlDataReader = cmd.ExecuteReader

            If DR.Read Then
                coverage = DR.Item("coverage").ToString

            End If

            cnn.Close()

            Dim items() As String = coverage.Split(";")

            Dim locDetails As String = ""
            Dim word_stats As String = ""

            For Each place As String In items
                If Not place = "" Then


                    locDetails = Regex.Split(place, "wordStats")(0)
                    word_stats = Regex.Split(place, "wordStats")(1)

                    word_stats = word_stats.Remove(0, word_stats.IndexOf("["))

                    word_stats = word_stats.Remove((word_stats.Length - 3), 3)

                    locDetails += "wordsStats"":" & word_stats & "}"

                    Dim places As unlockDetails = Newtonsoft.Json.JsonConvert.DeserializeObject(Of unlockDetails)(locDetails)

                    If Not places Is Nothing Then

                        Dim qualData As New qualDataRecord
                        qualData.identifier = ID
                        qualData.lat = places.lat
                        qualData.lon = places.lon
                        qualData.title = places.Name
                        qualData.counts = places.Occurences


                        results.Add(qualData)
                    End If

                End If

            Next




            Return results


        End Function

        Public Function getChosenLayers(ByVal layers As Dictionary(Of String, spatialSearchLayers)) As Dictionary(Of String, spatialSearchLayers)

            Dim DB As New getDBConnections
            Dim cnn As Npgsql.NpgsqlConnection = DB.getDBConnection("Survey_Data")

            For Each layer As KeyValuePair(Of String, spatialSearchLayers) In layers

                Dim geographies() As String = layer.Value.geographies.Split(";")

                Dim selStr As New StringBuilder
                selStr.Append("SELECT area_name, ST_AsEWKT(st_simplifypreservetopology(the_geom, 0.000225)) as the_geom from " & layer.Value.id)
                selStr.Append(" WHERE ")

                For Each geog As String In geographies

                    selStr.Append("area_name = '" & geog & "' OR ")


                Next


                selStr.Remove((selStr.Length - 3), 3)

                Dim DataAdapter As New Npgsql.NpgsqlDataAdapter(selStr.ToString, cnn)
                Dim resultsTable As New DataTable
                DataAdapter.Fill(resultsTable)

                For Each row As DataRow In resultsTable.Rows
                    Dim the_geom As New geom

                    the_geom.geom = row.Item("the_geom")
                    layer.Value.geometry.Add(row.Item("area_name"), the_geom)


                Next




            Next


            Return layers


        End Function


        Public Function getFeatureInfoTable(ByVal lat As Double, lon As Double, tables As List(Of IdentifyFeatures)) As ArrayList

            Dim htmlOutputs As New ArrayList

            For Each Table As IdentifyFeatures In tables


                Dim selStr As String = "SELECT area_name, total, successful, refused, no_contact, ineligible, other from " & Table.tableID
                selStr += " WHERE ST_Within(st_transform(ST_GeomFromText('POINT(" & lon & " " & lat & ")',27700), 4326), " & Table.tableID & ".the_geom);"


                Dim db As New getDBConnections
                Dim cnn As Npgsql.NpgsqlConnection = db.getDBConnection("Survey_Data")


                Dim results As New DataTable

                Dim DA As New Npgsql.NpgsqlDataAdapter(selStr, cnn)

                DA.Fill(results)

                Dim htmlOut As String = ConvertToHtmlFile(results)

                Dim identResults As New IdentifyResults
                identResults.tableName = Table.tableName
                identResults.Html = htmlOut

                htmlOutputs.Add(identResults)
            Next

            Return htmlOutputs

        End Function


        ''' <summary>
        ''' This is a simple way to convert a DataTable to an HTML file.
        ''' </summary>
        ''' <param name="targetTable">This the table to convert.</param>
        ''' <returns>This is the HTML output, which can saved as a file.</returns>
        Public Shared Function ConvertToHtmlFile(targetTable As DataTable) As String
            Dim myHtmlFile As String = ""



            If targetTable Is Nothing Then
                Throw New System.ArgumentNullException("targetTable")
                'Continue.
            Else
            End If



            'Get a worker object.
            Dim myBuilder As New StringBuilder()



            'Open tags and write the top portion.
            'myBuilder.Append("<html xmlns='http://www.w3.org/1999/xhtml'>")
            'myBuilder.Append("<head>")
            'myBuilder.Append("<title>")
            'myBuilder.Append("Page-")
            'myBuilder.Append(Guid.NewGuid().ToString())
            'myBuilder.Append("</title>")
            'myBuilder.Append("</head>")
            'myBuilder.Append("<body>")
            myBuilder.Append("<table border='1px' cellpadding='5' cellspacing='0' ")
            myBuilder.Append("style='border: solid 1px Silver; font-size: x-small;'>")



            'Add the headings row.



            myBuilder.Append("<tr align='left' valign='top'>")



            For Each myColumn As DataColumn In targetTable.Columns
                myBuilder.Append("<td align='left' valign='top'>")
                myBuilder.Append(myColumn.ColumnName)
                myBuilder.Append("</td>")
            Next



            myBuilder.Append("</tr>")



            'Add the data rows.
            For Each myRow As DataRow In targetTable.Rows
                myBuilder.Append("<tr align='left' valign='top'>")



                For Each myColumn As DataColumn In targetTable.Columns
                    myBuilder.Append("<td align='left' valign='top'>")
                    myBuilder.Append(myRow(myColumn.ColumnName).ToString())
                    myBuilder.Append("</td>")
                Next



                myBuilder.Append("</tr>")
            Next



            'Close tags.
            myBuilder.Append("</table>")
            'myBuilder.Append("</body>")
            'myBuilder.Append("</html>")



            'Get the string for return.
            myHtmlFile = myBuilder.ToString()



            Return myHtmlFile
        End Function



    End Class
End Namespace



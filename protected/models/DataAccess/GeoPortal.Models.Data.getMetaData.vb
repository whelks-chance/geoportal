Imports Npgsql
Imports GeoPortal.GeoPortal.Models.BusinessLogic

Namespace GeoPortal.Models.Data
    Public Class getMetaData

        Public Function getQuestionMetaData(ByVal Id As String) As GeoPortal.Models.BusinessLogic.QuestionMetaData


            Dim db As New getDBConnections()

            Dim cnn As NpgsqlConnection = db.getDBConnection("Survey_Data")

            Dim DR As NpgsqlDataReader

            Dim selectStr As String = "SELECT * FROM questions where qid ='" & Id & "';"

            Dim cmd As New NpgsqlCommand(selectStr, cnn)

            cnn.Open()

            DR = cmd.ExecuteReader()

            If DR.Read Then

                Dim qMetaData As New GeoPortal.Models.BusinessLogic.QuestionMetaData

                qMetaData.QuestionID = DR("qid")
                qMetaData.QuestionLinkedFrom = DR.Item("link_from")
                qMetaData.QuestionNumber = DR.Item("questionnumber")
                qMetaData.QuestionNotesPrompts = DR.Item("notes")
                qMetaData.QuestionSubOf = DR.Item("subof")
                qMetaData.QuestionText = DR.Item("literal_question_text")
                qMetaData.QuestionThematicGroups = DR.Item("thematic_groups")
                qMetaData.QuestionThematicTags = DR.Item("thematic_tags")
                qMetaData.QuestionType = DR.Item("type")
                qMetaData.QuestionVariable = DR.Item("variableid")

                Return qMetaData

            Else

                Return Nothing

            End If



        End Function


        Public Function getSurveyMetaData(ByVal SID As String) As GeoPortal.Models.BusinessLogic.SurveyMetaData

            Dim db As New getDBConnections()

            Dim cnn As NpgsqlConnection = db.getDBConnection("Survey_Data")

            Dim DR As NpgsqlDataReader

            Dim selectStr As String = "SELECT * FROM survey where surveyid ='" & SID & "';"

            Dim cmd As New NpgsqlCommand(selectStr, cnn)

            cnn.Open()

            DR = cmd.ExecuteReader()

            If DR.Read Then

                Dim sMetaData As New GeoPortal.Models.BusinessLogic.SurveyMetaData

                sMetaData.surveyID = Trim(DR.Item("surveyid"))
                sMetaData.surveyWeighting = Trim(DR.Item("des_weighting"))
                sMetaData.surveyURL = Trim(DR.Item("link"))
                sMetaData.surveyTitle = Trim(DR.Item("survey_title"))
                sMetaData.surveyStart = Trim(DR.Item("collectionstartdate"))
                sMetaData.surveyEnd = Trim(DR.Item("collectionenddate"))
                sMetaData.surveySeries = Trim(DR.Item("long"))
                sMetaData.surveySamplingProcedure = Trim(DR.Item("samp_procedure"))
                sMetaData.surveySamplingError = Trim(DR.Item("descriptionofsamplingerror"))
                sMetaData.surveySampleSize = Trim(DR.Item("samplesize"))
                sMetaData.surveyResponseRate = Trim(DR.Item("responserate"))
                sMetaData.surveyNotes = Trim(DR.Item("notes"))
                sMetaData.surveyLocation = Trim(DR.Item("location"))
                sMetaData.surveyFrequency = Trim(DR.Item("surveyfrequency"))
                sMetaData.surveyDataCollectionMethod = Trim(DR.Item("moc_description"))
                sMetaData.surveyCollector = Trim(DR.Item("datacollector").ToString)
                sMetaData.surveyCollectionSituation = Trim(DR.Item("collectionsituation"))



                Return sMetaData

            Else

                Return Nothing

            End If



        End Function


        Public Function getResponseMetaData(ByVal QID As String) As GeoPortal.Models.BusinessLogic.ResponseMetaData


            Dim db As New getDBConnections()

            Dim cnn As NpgsqlConnection = db.getDBConnection("Survey_Data")

            Dim DR As NpgsqlDataReader

            Dim selectStr As String = "SELECT * FROM questions_responses_link where qid ='" & QID & "';"

            Dim cmd As New NpgsqlCommand(selectStr, cnn)

            cnn.Open()
            DR = cmd.ExecuteReader

            If DR.Read Then
                Dim responseID As String = Trim(DR.Item("responseid"))


                cnn.Close()

                cmd.CommandText = "SELECT * FROM responses WHERE responseid = '" & responseID & "';"

                cnn.Open()
                DR = cmd.ExecuteReader()

                If DR.Read Then

                    Dim rMetaData As New GeoPortal.Models.BusinessLogic.ResponseMetaData

                    rMetaData.questionID = QID
                    rMetaData.responseID = responseID
                    rMetaData.responseText = DR.Item("responsetext")
                    rMetaData.responseType = DR.Item("response_type") & "; " & DR.Item("routetype")
                    rMetaData.responseRouting = DR.Item("route_notes")
                    rMetaData.responseVariables = DR.Item("computed_var")
                    rMetaData.responseChecks = DR.Item("checks")


                    Return rMetaData


                End If
                cnn.Close()
            Else
                Dim rMetaData As New GeoPortal.Models.BusinessLogic.ResponseMetaData

                rMetaData.questionID = QID
                rMetaData.responseID = "N/A"
                rMetaData.responseText = "N/A"
                rMetaData.responseType = "N/A"
                rMetaData.responseRouting = "N/A"
                rMetaData.responseVariables = "N/A"
                rMetaData.responseChecks = "N/A"
                Return rMetaData

            End If



        End Function


        Public Function getDublinCore(ByVal SID As String) As GeoPortal.Models.BusinessLogic.DublinCore


            Dim db As New getDBConnections()

            Dim cnn As NpgsqlConnection = db.getDBConnection("Survey_Data")

            Dim DR As NpgsqlDataReader

            Dim selStr As String = "Select * from dc_info WHERE identifier ='wi" & SID & "';"

            Dim cmd As New NpgsqlCommand(selStr, cnn)


            cnn.Open()
            DR = cmd.ExecuteReader

            If DR.Read Then

                Dim dcMeta As New GeoPortal.Models.BusinessLogic.DublinCore

                dcMeta.dcContributor = Trim(DR.Item("contributor"))
                dcMeta.dcCoverage = Trim(DR.Item("coverage"))
                dcMeta.dcCreator = Trim(DR.Item("creator"))
                dcMeta.dcDate = Trim(DR.Item("date"))
                dcMeta.dcDescription = Trim(DR.Item("description"))
                dcMeta.dcFormat = Trim(DR.Item("format"))
                dcMeta.dcLanguage = Trim(DR.Item("language"))
                dcMeta.dcPublisher = Trim(DR.Item("publisher"))
                dcMeta.dcRelation = Trim(DR.Item("relation"))
                dcMeta.dcRights = Trim(DR.Item("rights"))
                dcMeta.dcSource = Trim(DR.Item("source"))
                dcMeta.dcSubject = Trim(DR.Item("subject"))
                dcMeta.dcTitle = Trim(DR.Item("title"))
                dcMeta.dcType = Trim(DR.Item("type"))
                dcMeta.dcWiserdID = Trim(DR.Item("identifier"))
                cnn.Close()
                Return dcMeta



            Else
                cnn.Close()
                Return Nothing
            End If




        End Function

        Public Function getResponseTable(ByVal SID As String, ByVal unit As String) As ArrayList

            Dim db As New getDBConnections()

            Dim cnn As NpgsqlConnection = db.getDBConnection("Survey_Data")

            Dim tablename As String = getTableName(SID, unit)


            Dim selStr As String = "Select * from " & tablename & ";"

            Dim DA As New NpgsqlDataAdapter(selStr, cnn)

            Dim responses As New ArrayList

            Dim DT As New DataTable

            DA.Fill(DT)


            For Each DR As DataRow In DT.Rows
                Dim rMeta As New GeoPortal.Models.BusinessLogic.ResponseTable

                rMeta.name = DR.Item("area_name")
                rMeta.total = DR.Item("total")
                rMeta.successful = DR.Item("successful")
                rMeta.responseRate = DR.Item("response_rate")
                rMeta.refused = DR.Item("refused")
                rMeta.other = DR.Item("other")
                rMeta.noContact = DR.Item("no_contact")
                rMeta.ineligible = DR.Item("ineligible")
                rMeta.adjustedRRate = DR.Item("adjusted_rr")

                responses.Add(rMeta)

            Next

            Return responses

        End Function

        Public Function getFields(ByVal SID As String, ByVal unit As String) As ArrayList
            Dim dc As New getDBConnections

            Dim cnn As Npgsql.NpgsqlConnection = dc.getDBConnection("Survey_Data")


            Dim TableName As String = getTableName(SID, unit)


            Dim selectStr As String = "Select column_name as name from information_schema.columns where table_name ='" & TableName & "';"


            Dim results As New ArrayList

            Dim cmd As New Npgsql.NpgsqlCommand(selectStr, cnn)

            cnn.Open()

            Dim DR As Npgsql.NpgsqlDataReader = cmd.ExecuteReader

            'Advance reader through the first two records as not applicable for choropleth mapping
            DR.Read()
            DR.Read()


            Do While DR.Read

                Dim label As New GeoPortal.Models.BusinessLogic.Fields
                label.Name = Trim(DR.Item("Name").ToString)
                results.Add(label)
            Loop

            cnn.Close()

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



        Public Function getQDublinCore(ByVal SID As String) As GeoPortal.Models.BusinessLogic.DublinCore


            Dim db As New getDBConnections()

            Dim cnn As NpgsqlConnection = db.getDBConnection("Qual_Data")

            Dim DR As NpgsqlDataReader

            Dim selStr As String = "Select * from qualdata.dc_info WHERE identifier ='" & SID & "';"

            Dim cmd As New NpgsqlCommand(selStr, cnn)


            cnn.Open()
            DR = cmd.ExecuteReader

            If DR.Read Then

                Dim dcMeta As New GeoPortal.Models.BusinessLogic.DublinCore


                Dim coverage As String = Trim(DR.Item("coverage"))

                Dim placeNames As String = ""

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
                            placeNames += places.Name & ";"
                        End If

                    End If

                Next



                dcMeta.dcContributor = Trim(DR.Item("contributor"))
                dcMeta.dcCoverage = placeNames
                dcMeta.dcCreator = Trim(DR.Item("creator"))
                dcMeta.dcDate = Trim(DR.Item("date"))
                dcMeta.dcDescription = Trim(DR.Item("description"))
                dcMeta.dcFormat = Trim(DR.Item("format"))
                dcMeta.dcLanguage = Trim(DR.Item("language"))
                dcMeta.dcPublisher = Trim(DR.Item("publisher"))
                dcMeta.dcRelation = Trim(DR.Item("relation"))
                dcMeta.dcRights = Trim(DR.Item("rights"))
                dcMeta.dcSource = Trim(DR.Item("source"))
                dcMeta.dcSubject = Trim(DR.Item("subject"))
                dcMeta.dcTitle = Trim(DR.Item("title"))
                dcMeta.dcType = Trim(DR.Item("type"))
                dcMeta.dcWiserdID = Trim(DR.Item("identifier"))
                cnn.Close()
                Return dcMeta



            Else
                cnn.Close()
                Return Nothing
            End If




        End Function


        Public Function getQualWords(ByVal ID As String, ByVal place1 As String, ByVal Place2 As String, ByVal Place3 As String) As ArrayList
            Dim wordStats As New ArrayList
            Dim wordCol As New ArrayList
            Dim pageCol As New Dictionary(Of String, pageCollection)
            Dim docwords As New ArrayList

            Dim db As New getDBConnections

            Dim cnn As NpgsqlConnection = db.getDBConnection("Qual_Data")


            Dim selStr As String = "Select coverage from qualdata.dc_info WHERE identifier ='" & ID & "';"

            Dim DR As NpgsqlDataReader
            Dim cmd As New NpgsqlCommand(selStr, cnn)

            cnn.Open()
            DR = cmd.ExecuteReader

            If DR.Read Then



                Dim coverage As String = Trim(DR.Item("coverage"))

                Dim placeNames As String = ""

                Dim items() As String = coverage.Split(";")

                Dim locDetails As String = ""
                Dim word_stats As String = ""

                For Each place As String In items
                    If Not place = "" Then

                        Dim w As New ArrayList


                        locDetails = Regex.Split(place, "wordStats")(0)
                        word_stats = Regex.Split(place, "wordStats")(1)

                        word_stats = word_stats.Remove(0, word_stats.IndexOf("["))

                        word_stats = word_stats.Remove((word_stats.Length - 3), 3)

                        locDetails += "wordsStats"":" & word_stats & "}"

                        Dim places As unlockDetails = Newtonsoft.Json.JsonConvert.DeserializeObject(Of unlockDetails)(locDetails)

                        If place1 = places.Name Or Place2 = places.Name Or Place3 = places.Name Then

                            For Each stat As words In places.wordsStats
                                Dim wordcountPos As New qualWords

                                wordcountPos.count = stat.count
                                wordcountPos.page = stat.page
                                wordcountPos.name = places.Name

                                w.Add(wordcountPos)

                            Next

                            docwords.Add(w)
                        End If
                    End If

                    wordCol.Add(docwords)

                Next

            End If



            For index = 0 To wordCol.Count - 1
                For Each placeCollection As ArrayList In wordCol(index)

                    For Each wrd As qualWords In placeCollection

                        If pageCol.ContainsKey(wrd.page) Then

                            Dim page As pageCollection = pageCol.Item(wrd.page)

                            If page.place2 Is Nothing Then
                                page.place2 = wrd.name
                                page.place2Count = wrd.count
                            Else : page.place3 = wrd.name
                                page.place3Count = wrd.count

                            End If

                        Else
                            Dim page As New pageCollection
                            page.place1 = wrd.name
                            page.place1Count = wrd.count
                            page.page = wrd.page
                            pageCol.Add(wrd.page, page)



                        End If


                    Next
                    index += 1
                Next

            Next

            For Each obj As KeyValuePair(Of String, pageCollection) In pageCol
                wordStats.Add(obj.Value)

            Next

            Return wordStats


        End Function

        Public Function getPlaces(ByVal ID As String) As ArrayList

            Dim placeNames As New ArrayList

            Dim db As New getDBConnections

            Dim cnn As NpgsqlConnection = db.getDBConnection("Qual_Data")


            Dim selStr As String = "Select coverage from qualdata.dc_info WHERE identifier ='" & ID & "';"

            Dim DR As NpgsqlDataReader
            Dim cmd As New NpgsqlCommand(selStr, cnn)

            cnn.Open()
            DR = cmd.ExecuteReader

            If DR.Read Then


                Dim coverage As String = Trim(DR.Item("coverage"))

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

                        Dim pl As New place
                        pl.place = places.Name
                        placeNames.Add(pl)


                    End If

                Next

            End If

            Return placeNames

        End Function

        Public Function getCloud(ByVal ID As String) As String

            Dim Tags As New ArrayList

            Dim db As New getDBConnections

            Dim cnn As NpgsqlConnection = db.getDBConnection("Qual_Data")


            Dim selStr As String = "Select calais from qualdata.dc_info WHERE identifier ='" & ID & "';"

            Dim DR As NpgsqlDataReader
            Dim cmd As New NpgsqlCommand(selStr, cnn)

            cnn.Open()
            DR = cmd.ExecuteReader



            If DR.Read Then

                Dim json As String = DR.Item("calais")

                json = json.TrimEnd(","" & vbCrLf & """)


                Dim jsons() As String = json.Split("},")

                Dim tagsDetails As String = ""

                For Each item As String In jsons

                    If Not item = " " Then
                        item = item.TrimStart(",")

                        Dim subItems() As String = item.Split(",")

                        If subItems.Length = 6 Then

                            Dim dict As Dictionary(Of String, String) = subItems.ToDictionary(Function(value As String)
                                                                                                  Return value.Split(":")(0).ToString
                                                                                              End Function,
                                                  Function(value As String)
                                                      Return value.Split(":")(1).ToString
                                                  End Function)

                            Dim word As String = dict.Item("""Value""").ToString.Replace(Char.ConvertFromUtf32(34), String.Empty)
                            Dim cnt As String = dict.Item("""Count""").ToString.Replace(Char.ConvertFromUtf32(34), String.Empty)

                            tagsDetails += "{""word"":""" & word & """,""count"":" & CInt(cnt) & "},"
                        End If
                    End If
                Next

                ' Dim obj As List(Of Calais) = Newtonsoft.Json.JsonConvert.DeserializeObject(Of List(Of Calais))(json)

                'Newtonsoft.Json.JsonConvert.DeserializeObject(DR.Item("calais"))

                Return "{""tags"":[" & tagsDetails & "]}"

            End If




        End Function

    End Class
End Namespace



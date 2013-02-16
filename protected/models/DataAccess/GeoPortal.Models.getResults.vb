Imports GeoPortal.GeoPortal.Models.Data
Imports GeoPortal.GeoPortal.Models.BusinessLogic

Public Class getReults

    Private dt As DataTable = New DataTable
    Public count As Integer


    Public Function getQuestionnaireData(ByVal start As Integer, ByVal limit As Integer, ByVal keywords As String, ByVal verify As Boolean, ByVal mappable As Boolean) As ArrayList

        Dim keywordsArray() As String = keywords.Split(",")


        Dim SSearch As StringBuilder = New StringBuilder
        'keywords = "wales"

        If keywordsArray.Length > 1 Then

            Dim multiKeyword As String = ""

            For Each keyword As String In keywordsArray
                multiKeyword += keyword & " &"

            Next

            multiKeyword = multiKeyword.TrimEnd("&")
            SSearch.Append("SELECT qid, questionnumber as qnumber, link_from, thematic_groups, thematic_tags, ts_headline('english',literal_question_text, plainto_tsquery('english','" & multiKeyword & "')) as original_text, notes as q_notes, subof as subof, type as q_type, link_from as parent_q, ts_rank_cd(to_tsvector(literal_question_text), plainto_tsquery('english','" & multiKeyword & "'),0) AS rank")
            SSearch.Append(" FROM questions ")
            SSearch.Append("WHERE qtext_index @@ to_tsquery('english','" & multiKeyword & "')")
        Else
            SSearch.Append("SELECT qid, questionnumber as qnumber, link_from, thematic_groups, thematic_tags, ts_headline('english',literal_question_text, plainto_tsquery('english','" & keywords & "')) as original_text, notes as q_notes, subof as subof, type as q_type, link_from as parent_q, ts_rank_cd(to_tsvector(literal_question_text), plainto_tsquery('english','" & keywords & "'),0) AS rank")
            SSearch.Append(" FROM questions ")
            SSearch.Append("WHERE qtext_index @@ to_tsquery('english','" & keywords & "')")
        End If



        'SSearch.Append(" WHERE query @@ to_tsvector(literal_question_text)")
        SSearch.Append("ORDER BY rank DESC")

        If verify = True Then
            SSearch.Append(" LIMIT 1 ")
        Else
            SSearch.Append(" LIMIT 1000 ")

        End If


        Dim db As New getDBConnections()
        Dim cnn As Npgsql.NpgsqlConnection = db.getDBConnection("Survey_Data")



        Dim cmd As New Npgsql.NpgsqlCommand(SSearch.ToString.ToLower(), cnn)

        Dim DA As New Npgsql.NpgsqlDataAdapter(SSearch.ToString.ToLower(), cnn)

        Dim id As Integer = 1
        cnn.Open()
        DA.Fill(dt)
        cnn.Close()



        Dim results As New SortedDictionary(Of String, Object)



        Dim qtype As String = ""

        For Each row As DataRow In dt.Rows


            qtype = Trim(row.Item("Q_type"))

            If qtype = "ROOT Question" Then
                Dim rootQ As New rootQuestionDetails
                rootQ.QuestionID = Trim(row.Item("qid"))
                rootQ.QuestionNumber = Trim(row.Item("qNumber"))
                rootQ.QuestionText = Trim(row.Item("original_text"))
                rootQ.QuestionNotes = Trim(row.Item("q_notes"))
                rootQ.QuestionThematicGroup = Trim(row.Item("thematic_groups"))
                rootQ.QuestionThematicTag = Trim(row.Item("thematic_tags"))
                rootQ.QuestionType = "ROOT Question"
                rootQ.Rank = row.Item("rank")
                rootQ.DataSource = "WISERD DB"
                rootQ.RecordID = id

                Dim survey_ID As String = Trim(row.Item("link_from"))

                Dim survey_details As String = "Select * from Survey WHERE surveyid = (Select surveyid as query from survey_questions_link WHERE qid ='" & survey_ID & "');"

                Dim surveycmd As New Npgsql.NpgsqlCommand(survey_details, cnn)

                Dim surDRdr As Npgsql.NpgsqlDataReader

                cnn.Open()
                surDRdr = surveycmd.ExecuteReader

                If surDRdr.Read() Then
                    rootQ.SurveyID = Trim(surDRdr.Item("surveyid"))
                    rootQ.DataSource = getDataSourceType(surDRdr.Item("surveyid"))
                    rootQ.SurveyName = Trim(surDRdr.Item("survey_title"))
                    rootQ.SurveyCollectionFrequency = Trim(surDRdr.Item("surveyfrequency"))
                    rootQ.spatial = surDRdr.Item("spatialdata")
                End If

                cnn.Close()

                If Not results.ContainsKey(Trim(row.Item("qid"))) Then
                    results.Add(Trim(row.Item("qid")), rootQ)
                End If


                'Exit For
            ElseIf qtype = "SINGLE Question" Then

                Dim singleQ As New SingleQuestion
                singleQ.QuestionID = Trim(row.Item("qid"))
                singleQ.QuestionNumber = Trim(row.Item("qNumber"))
                singleQ.QuestionText = Trim(row.Item("original_text"))
                singleQ.QuestionNotes = Trim(row.Item("q_notes"))
                singleQ.QuestionThematicGroup = Trim(row.Item("thematic_groups"))
                singleQ.QuestionThematicTag = Trim(row.Item("thematic_tags"))
                singleQ.QuestionType = "Single Question"
                singleQ.Rank = row.Item("rank")
                singleQ.DataSource = "WISERD DB"
                singleQ.RecordID = id


                Dim survey_ID As String = Trim(row.Item("link_from"))

                Dim survey_details As String = "Select * from Survey WHERE surveyid = (Select surveyid as query from survey_questions_link WHERE qid ='" & survey_ID & "');"

                Dim surveycmd As New Npgsql.NpgsqlCommand(survey_details, cnn)

                Dim surDRdr As Npgsql.NpgsqlDataReader

                cnn.Open()
                surDRdr = surveycmd.ExecuteReader

                If surDRdr.Read() Then
                    singleQ.SurveyID = Trim(surDRdr.Item("surveyid"))
                    singleQ.DataSource = getDataSourceType(surDRdr.Item("surveyid"))
                    singleQ.SurveyName = Trim(surDRdr.Item("survey_title"))
                    singleQ.SurveyCollectionFrequency = Trim(surDRdr.Item("surveyfrequency"))
                    singleQ.spatial = surDRdr.Item("spatialdata")
                End If
                cnn.Close()
                If Not results.ContainsKey(Trim(row.Item("qid"))) Then
                    results.Add(Trim(row.Item("qid")), singleQ)
                End If

            ElseIf qtype = "SUB Question" Then

                Dim subQ As New subQuestionDetails
                subQ.QuestionID = Trim(row.Item("qid"))
                subQ.QuestionNumber = Trim(row.Item("qNumber"))
                subQ.QuestionText = Trim(row.Item("original_text"))
                subQ.QuestionNotes = Trim(row.Item("q_notes"))
                subQ.QuestionThematicGroup = Trim(row.Item("thematic_groups"))
                subQ.QuestionThematicTag = Trim(row.Item("thematic_tags"))
                subQ.QuestionType = "Sub Question Question"
                subQ.Rank = row.Item("rank")
                subQ.DataSource = "WISERD DB"
                subQ.RecordID = id
                subQ.RootQuestion = Trim(row.Item("subof"))

                Dim survey_ID As String = Trim(row.Item("link_from"))

                Dim survey_details As String = "Select * from Survey WHERE lower(surveyid) = lower((Select distinct(surveyid) from survey_questions_link WHERE qid = lower('" & survey_ID & "')));"

                Dim surveycmd As New Npgsql.NpgsqlCommand(survey_details, cnn)

                Dim surDRdr As Npgsql.NpgsqlDataReader

                cnn.Open()
                surDRdr = surveycmd.ExecuteReader

                If surDRdr.Read() Then
                    subQ.SurveyID = Trim(surDRdr.Item("surveyid"))
                    subQ.DataSource = getDataSourceType(surDRdr.Item("surveyid"))
                    subQ.SurveyName = Trim(surDRdr.Item("survey_title"))
                    subQ.SurveyCollectionFrequency = Trim(surDRdr.Item("surveyfrequency"))
                    subQ.spatial = surDRdr.Item("spatialdata")
                Else

                End If


                cnn.Close()
                If Not results.ContainsKey(Trim(row.Item("qid"))) Then
                    results.Add(Trim(row.Item("qid")), subQ)
                End If

            ElseIf qtype = "COMPOUND Question" Then

                Dim compoundQ As New compoundQuestionDetails
                compoundQ.QuestionID = Trim(row.Item("qid"))
                compoundQ.QuestionNumber = Trim(row.Item("qNumber"))
                compoundQ.QuestionText = Trim(row.Item("original_text"))
                compoundQ.QuestionNotes = Trim(row.Item("q_notes"))
                compoundQ.QuestionThematicGroup = Trim(row.Item("thematic_groups"))
                compoundQ.QuestionThematicTag = Trim(row.Item("thematic_tags"))
                compoundQ.QuestionType = "Compound Question"
                compoundQ.Rank = row.Item("rank")
                compoundQ.DataSource = "WISERD DB"
                compoundQ.RecordID = id

                Dim survey_ID As String = Trim(row.Item("link_from"))

                Dim survey_details As String = "Select * from Survey WHERE surveyid = (Select surveyid as query from survey_questions_link WHERE qid ='" & survey_ID & "');"

                Dim surveycmd As New Npgsql.NpgsqlCommand(survey_details, cnn)

                Dim surDRdr As Npgsql.NpgsqlDataReader

                cnn.Open()
                surDRdr = surveycmd.ExecuteReader

                If surDRdr.Read() Then
                    compoundQ.SurveyID = Trim(surDRdr.Item("surveyid"))
                    compoundQ.DataSource = getDataSourceType(surDRdr.Item("surveyid"))
                    compoundQ.SurveyName = Trim(surDRdr.Item("survey_title"))
                    compoundQ.SurveyCollectionFrequency = Trim(surDRdr.Item("surveyfrequency"))
                    compoundQ.spatial = surDRdr.Item("spatialdata")
                End If
                cnn.Close()
                If Not results.ContainsKey(Trim(row.Item("qid"))) Then
                    results.Add(Trim(row.Item("qid")), compoundQ)
                End If

            ElseIf qtype = "SUB of SUB Question" Then

                Dim subsubQ As New subQuestionDetails
                subsubQ.QuestionID = Trim(row.Item("qid"))
                subsubQ.QuestionNumber = Trim(row.Item("qNumber"))
                subsubQ.QuestionText = Trim(row.Item("original_text"))
                subsubQ.QuestionNotes = Trim(row.Item("q_notes"))
                subsubQ.QuestionThematicGroup = Trim(row.Item("thematic_groups"))
                subsubQ.QuestionThematicTag = Trim(row.Item("thematic_tags"))
                subsubQ.QuestionType = "Sub of A Sub Question"
                subsubQ.Rank = row.Item("rank")
                subsubQ.RecordID = id
                subsubQ.RootQuestion = Trim(row.Item("subof"))

                Dim survey_ID As String = Trim(row.Item("link_from"))

                Dim survey_details As String = "Select * from Survey WHERE surveyid = (Select surveyid as query from survey_questions_link WHERE qid ='" & survey_ID & "');"

                Dim surveycmd As New Npgsql.NpgsqlCommand(survey_details, cnn)

                Dim surDRdr As Npgsql.NpgsqlDataReader

                cnn.Open()
                surDRdr = surveycmd.ExecuteReader

                If surDRdr.Read() Then
                    subsubQ.SurveyID = Trim(surDRdr.Item("surveyid"))
                    subsubQ.DataSource = getDataSourceType(surDRdr.Item("surveyid"))
                    subsubQ.SurveyName = Trim(surDRdr.Item("survey_title"))
                    subsubQ.SurveyCollectionFrequency = Trim(surDRdr.Item("surveyfrequency"))
                    subsubQ.spatial = surDRdr.Item("spatialdata")
                End If
                cnn.Close()
                If Not results.ContainsKey(Trim(row.Item("qid"))) Then
                    results.Add(Trim(row.Item("qid")), subsubQ)
                End If

            End If

            id += 1
        Next

        cnn.Dispose()
        cnn = Nothing
        db = Nothing
        GC.Collect()

        Dim finalResults As New ArrayList

        count = results.Count
        If mappable = True Then
            For Each result As KeyValuePair(Of String, Object) In results
                If result.Value.spatial = True Then
                    finalResults.Add(result.Value)
                End If
            Next

        Else
            For Each result As KeyValuePair(Of String, Object) In results

                finalResults.Add(result.Value)

            Next

        End If


        Return finalResults



    End Function


    Public Function getDataSourceType(ByVal ID As String) As String

        Dim DataSourceType As String = ""

        Dim prefix As String = ID.Split("_")(0).ToString


        If prefix = "sid" Then
            DataSourceType = "Survey Data"

        End If



        Return DataSourceType
    End Function

    Public Function getSurveyQuestion(ByVal SID As String) As ArrayList

        Dim Qs As New ArrayList

        Dim selStr As String = "SELECT * from survey_questions_link WHERE surveyid ='" & Trim(SID) & "';"

        Dim db As New getDBConnections

        Dim cnn As Npgsql.NpgsqlConnection = db.getDBConnection("Survey_Data")

        Dim DT As New DataTable

        Dim DA As New Npgsql.NpgsqlDataAdapter(selStr, cnn)

        DA.Fill(DT)

        For Each row As DataRow In DT.Rows
            Dim qid As String = row.Item("qid").ToString

            If Not qid = "" Then
                Dim DR As Npgsql.NpgsqlDataReader

                Dim selQ As String = "Select questionnumber, literal_question_text, thematic_groups, thematic_tags FROM questions WHERE qid = '" & Trim(qid) & "';"

                Dim cmd As New Npgsql.NpgsqlCommand(selQ, cnn)
                cnn.Open()
                DR = cmd.ExecuteReader
                DR.Read()

                Dim question As New allQuestions
                question.qid = qid
                question.questionNumber = Trim(DR.Item("questionnumber").ToString)
                question.questionText = Trim(DR.Item("literal_question_text").ToString)
                question.group = Trim(DR.Item("thematic_groups").ToString)
                question.tag = Trim(DR.Item("thematic_tags").ToString)

                cnn.Close()


                Qs.Add(question)

            End If


        Next

        db = Nothing
        DA = Nothing
        GC.Collect()
        Return Qs

    End Function

    Public Function getQualData(ByVal keywords As String) As ArrayList

        Dim keywordsArray() As String = keywords.Split(",")

        Dim SSearch As StringBuilder = New StringBuilder
        SSearch.Append("SELECT DISTINCT(id), stats, pages FROM qualdata.transcript_data ")

        If keywordsArray.Length > 1 Then

            Dim multiKeyword As String = ""

            For Each keyword As String In keywordsArray
                multiKeyword += keyword & " &"

            Next

            multiKeyword = multiKeyword.TrimEnd("&")

            SSearch.Append(" WHERE text_index @@ to_tsquery('english','" & multiKeyword & "')")
        Else
            SSearch.Append(" WHERE text_index @@ to_tsquery('english','" & keywords & "')")
        End If



        'keywords = "wales"
        'SSearch.Append("SELECT DISTINCT(id), stats, pages ,ts_headline('english',rawtext, query) FROM qualdata.transcript_data, plainto_tsquery('english', '" & keywords & "') query WHERE query @@ to_tsvector(rawtext)")


        Dim db As New getDBConnections()
        Dim cnn As Npgsql.NpgsqlConnection = db.getDBConnection("Qual_Data")
        Npgsql.NpgsqlConnection.ClearAllPools()
        Dim qDT As New DataTable

        Dim DA As New Npgsql.NpgsqlDataAdapter(SSearch.ToString.ToLower(), cnn)


        DA.Fill(qDT)


        Dim results As New ArrayList

        For Each row As DataRow In qDT.Rows

            Dim id As String = Trim(row.Item("id").ToString)

            Dim DCStr As String = "SELECT * FROM qualdata.dc_info WHERE identifier = '" & id & "';"

            Dim DR As Npgsql.NpgsqlDataReader
            cnn = db.getDBConnection("Qual_Data")


            Dim cmd As New Npgsql.NpgsqlCommand(DCStr, cnn)
            If cnn.State = ConnectionState.Closed Then
                cnn.Open()
            Else

                cnn.Close()
                cnn.Open()
            End If

            DR = cmd.ExecuteReader()


            If Not DR.Read() Then

            Else



                Dim qData As New QualData
                qData.id = id
                qData.creator = DR.Item("creator").ToString
                qData.pages = row.Item("pages").ToString
                qData.thematicgroup = DR.Item("thematic_group").ToString
                qData.qdate = DR.Item("date").ToString()
                qData.title = DR.Item("title").ToString

                results.Add(qData)

            End If

            cnn.Close()
        Next


        cnn.Dispose()

        DA = Nothing
        cnn = Nothing
        db = Nothing
        GC.Collect()

        Return results


    End Function



End Class



Imports GeoPortal.GeoPortal.Models.Data
Imports GeoPortal.GeoPortal.Models.BusinessLogic

Namespace GeoPortal
    Public Class SearchController
        Inherits System.Web.Mvc.Controller

        '
        ' GET: /Search

        Private results As ArrayList
        Private QualResults As ArrayList
        Private count As Integer
        Private qCount As Integer

        <CompressFilter()>
        Function verifySearch(ByVal Keywords As String, ByVal mappable As Boolean) As String

            Dim res As New getReults()
            Dim resultsExsist As ArrayList = res.getQuestionnaireData(0, 1, Keywords, True, Mappable)
            Dim qualResults As ArrayList = res.getQualData(Keywords)
            Dim strVerify As String = ""
            If resultsExsist.Count = 0 And qualResults.Count = 0 Then
                strVerify = "{""failure"": ""true""}"

            Else
                strVerify = "{""success"": ""true""}"

            End If

            Return strVerify
        End Function
        <CompressFilter()>
        Function SimpleSearch(ByVal Keywords As String, ByVal start As Integer, ByVal limit As Integer, ByVal Mappable As Boolean) As String
            Npgsql.NpgsqlConnection.ClearAllPools()
            If Keywords = "" Then
                results = Session("results")
                count = Session("resCount")

            Else
                If results Is Nothing Then
                    Dim res As New getReults()
                    results = res.getQuestionnaireData(start, limit, Keywords, False, Mappable)
                    count = res.count

                    Session.Add("results", results)
                    Session.Add("resCount", count)
                End If
            End If


            Dim resultsset As New GeoPortal.Models.BusinessLogic.results

            resultsset.totalCount = count
            resultsset.questions = Newtonsoft.Json.JsonConvert.SerializeObject(results)



            Dim pageResults As New ArrayList

            Dim cnt As Integer = start
            Dim cnt_end As Integer = cnt + limit

            Do Until cnt = cnt_end Or cnt = results.Count
                pageResults.Add(results.Item(cnt))
                cnt += 1
            Loop



            Dim str As String = "{""totalCount"":" & count.ToString & ", ""results"":" + Newtonsoft.Json.JsonConvert.SerializeObject(pageResults) + "}"

            Return str


            ' Return Json(res, JsonRequestBehavior.AllowGet)
        End Function


        <CompressFilter()>
        Function QualSimpleSearch(ByVal start As Integer, ByVal limit As Integer, ByVal keywords As String) As String
            Npgsql.NpgsqlConnection.ClearAllPools()
            If keywords = "" Then
                QualResults = Session("QualResults")
                qCount = Session("QualresCount")
            Else
                Dim res As New getReults()
                QualResults = res.getQualData(keywords)
                qCount = QualResults.Count

                Session.Add("QualResults", QualResults)
                Session.Add("QualresCount", qCount)

            End If


            Dim resultsset As New GeoPortal.Models.BusinessLogic.results

            resultsset.totalCount = qCount
            resultsset.questions = Newtonsoft.Json.JsonConvert.SerializeObject(QualResults)


            Dim pageResults As New ArrayList

            Dim cnt As Integer = start
            Dim cnt_end As Integer = cnt + limit

            Do Until cnt = cnt_end Or cnt = QualResults.Count
                pageResults.Add(QualResults.Item(cnt))
                cnt += 1
            Loop


            Dim str As String = "{""totalCount"":" & qCount.ToString & ", ""results"":" + Newtonsoft.Json.JsonConvert.SerializeObject(pageResults) + "}"

            Return str


            ' Return Json(res, JsonRequestBehavior.AllowGet)
        End Function


        <CompressFilter()>
        Function getQuestions(ByVal start As Integer, ByVal limit As Integer, ByVal SID As String) As String

            Npgsql.NpgsqlConnection.ClearAllPools()

            Dim res As New getReults()
            results = res.getSurveyQuestion(SID)
            count = results.Count

            Dim resultsset As New GeoPortal.Models.BusinessLogic.results

            resultsset.totalCount = count
            resultsset.questions = Newtonsoft.Json.JsonConvert.SerializeObject(results)


            Dim pageResults As New ArrayList

            Dim cnt As Integer = start
            Dim cnt_end As Integer = cnt + limit

            Do Until cnt = cnt_end Or cnt = results.Count
                pageResults.Add(results.Item(cnt))
                cnt += 1
            Loop


            Dim str As String = "{""totalCount"":" & count.ToString & ", ""questions"":" + Newtonsoft.Json.JsonConvert.SerializeObject(pageResults) + "}"

            Return str


        End Function

    End Class
End Namespace
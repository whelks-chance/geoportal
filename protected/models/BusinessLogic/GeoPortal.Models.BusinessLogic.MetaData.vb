
Namespace GeoPortal.Models.BusinessLogic
    Public Class QuestionMetaData

        Private QID As String
        Private QNo As String
        Private QText As String
        Private QNotes As String
        Private QVariable As String
        Private QTGroups As String
        Private QSThemes As String
        Private QRType As String
        Private QFollowsQID As String
        Private QSubOf As String


       
        Public Property QuestionID() As String
            Get
                Return QID
            End Get
            Set(ByVal value As String)
                QID = value
            End Set
        End Property

        Public Property QuestionNumber() As String
            Get
                Return QNo
            End Get
            Set(ByVal value As String)
                QNo = value
            End Set
        End Property

        Public Property QuestionText() As String
            Get
                Return QText
            End Get
            Set(ByVal value As String)
                QText = value
            End Set
        End Property

        Public Property QuestionNotesPrompts() As String
            Get
                Return QNotes
            End Get
            Set(ByVal value As String)
                QNotes = value
            End Set
        End Property

        Public Property QuestionVariable() As String
            Get
                Return QVariable
            End Get
            Set(ByVal value As String)
                QVariable = value
            End Set
        End Property

        Public Property QuestionThematicGroups() As String
            Get
                Return QTGroups
            End Get
            Set(ByVal value As String)
                QTGroups = value
            End Set
        End Property

        Public Property QuestionThematicTags() As String
            Get
                Return QSThemes
            End Get
            Set(ByVal value As String)
                QSThemes = value
            End Set
        End Property

        Public Property QuestionType() As String
            Get
                Return QRType
            End Get
            Set(ByVal value As String)
                QRType = value
            End Set
        End Property

        Public Property QuestionLinkedFrom() As String
            Get
                Return QFollowsQID
            End Get
            Set(ByVal value As String)
                QFollowsQID = value
            End Set
        End Property

        Public Property QuestionSubOf() As String
            Get
                Return QSubOf
            End Get
            Set(ByVal value As String)
                QSubOf = value
            End Set
        End Property

    End Class


    Public Class SurveyMetaData

        Private SID As String
        Private sTitle As String
        Private sCollector As String
        Private sStart As String
        Private sEnd As String
        Private sFrequency As String
        Private sSeries As String
        Private sNotes As String
        Private sLocation As String
        Private sURL As String
        Private sDataCollectionMethod As String
        Private sCollectionSituation As String
        Private sSamplingProcedure As String
        Private sSamplingError As String
        Private sSampleSize As String
        Private sResponseRate As String
        Private sWeighting As String

        Public Property surveyWeighting() As String
            Get
                Return sWeighting
            End Get
            Set(ByVal value As String)
                sWeighting = value
            End Set
        End Property


        Public Property surveyResponseRate() As String
            Get
                Return sResponseRate
            End Get
            Set(ByVal value As String)
                sResponseRate = value
            End Set
        End Property


        Public Property surveySampleSize() As String
            Get
                Return sSampleSize
            End Get
            Set(ByVal value As String)
                sSampleSize = value
            End Set
        End Property


        Public Property surveySamplingError() As String
            Get
                Return sSamplingError
            End Get
            Set(ByVal value As String)
                sSamplingError = value
            End Set
        End Property


        Public Property surveySamplingProcedure() As String
            Get
                Return sSamplingProcedure
            End Get
            Set(ByVal value As String)
                sSamplingProcedure = value
            End Set
        End Property



        Public Property surveyCollectionSituation() As String
            Get
                Return sCollectionSituation
            End Get
            Set(ByVal value As String)
                sCollectionSituation = value
            End Set
        End Property



        Public Property surveyDataCollectionMethod() As String
            Get
                Return sDataCollectionMethod
            End Get
            Set(ByVal value As String)
                sDataCollectionMethod = value
            End Set
        End Property


        Public Property surveyURL() As String
            Get
                Return sURL
            End Get
            Set(ByVal value As String)
                sURL = value
            End Set
        End Property
        Public Property surveyLocation() As String
            Get
                Return sLocation
            End Get
            Set(ByVal value As String)
                sLocation = value
            End Set
        End Property

        Public Property surveyNotes() As String
            Get
                Return sNotes
            End Get
            Set(ByVal value As String)
                sNotes = value
            End Set
        End Property


        Public Property surveySeries() As String
            Get
                Return sSeries
            End Get
            Set(ByVal value As String)
                sSeries = value
            End Set
        End Property

        Public Property surveyFrequency() As String
            Get
                Return sFrequency
            End Get
            Set(ByVal value As String)
                sFrequency = value
            End Set
        End Property

        Public Property surveyEnd() As String
            Get
                Return sEnd
            End Get
            Set(ByVal value As String)
                sEnd = value
            End Set
        End Property

        Public Property surveyStart() As String
            Get
                Return sStart
            End Get
            Set(ByVal value As String)
                sStart = value
            End Set
        End Property

        Public Property surveyCollector() As String
            Get
                Return sCollector
            End Get
            Set(ByVal value As String)
                sCollector = value
            End Set
        End Property

        Public Property surveyTitle() As String
            Get
                Return sTitle
            End Get
            Set(ByVal value As String)
                sTitle = value
            End Set
        End Property

        Public Property surveyID() As String
            Get
                Return SID
            End Get
            Set(ByVal value As String)
                SID = value
            End Set
        End Property








    End Class

    Public Class ResponseMetaData

        Private QID As String
        Private rID As String
        Private rType As String
        Private rText As String
        Private rTableID As String
        Private rChecks As String
        Private rVariables As String
        Private rRouting As String


        Public Property responseRouting() As String
            Get
                Return rRouting
            End Get
            Set(ByVal value As String)
                rRouting = value
            End Set
        End Property


        Public Property responseVariables() As String
            Get
                Return rVariables
            End Get
            Set(ByVal value As String)
                rVariables = value
            End Set
        End Property


        Public Property responseChecks() As String
            Get
                Return rChecks
            End Get
            Set(ByVal value As String)
                rChecks = value
            End Set
        End Property


        Public Property responseTableID() As String
            Get
                Return rTableID
            End Get
            Set(ByVal value As String)
                rTableID = value
            End Set
        End Property



        Public Property responseText() As String
            Get
                Return rText
            End Get
            Set(ByVal value As String)
                rText = value
            End Set
        End Property

        Public Property responseType() As String
            Get
                Return rType
            End Get
            Set(ByVal value As String)
                rType = value
            End Set
        End Property



        Public Property responseID() As String
            Get
                Return rID
            End Get
            Set(ByVal value As String)
                rID = value
            End Set
        End Property




        Public Property questionID() As String
            Get
                Return QID
            End Get
            Set(ByVal value As String)
                QID = value
            End Set
        End Property






    End Class

    Public Class DublinCore

        Private dWiserdID As String
        Private dTitle As String
        Private dSubject As String
        Private dDescription As String
        Private dDate As String
        Private dType As String
        Private dFormat As String
        Private dCreator As String
        Private dPublisher As String
        Private dContributor As String
        Private dSource As String
        Private dRights As String
        Private dCoverage As String
        Private dLanguage As String
        Private dRelation As String


        Public Property dcRelation() As String
            Get
                Return dRelation
            End Get
            Set(ByVal value As String)
                dRelation = value
            End Set
        End Property


        Public Property dcLanguage() As String
            Get
                Return dLanguage
            End Get
            Set(ByVal value As String)
                dLanguage = value
            End Set
        End Property



        Public Property dcCoverage() As String
            Get
                Return dCoverage
            End Get
            Set(ByVal value As String)
                dCoverage = value
            End Set
        End Property


        Public Property dcRights() As String
            Get
                Return dRights
            End Get
            Set(ByVal value As String)
                dRights = value
            End Set
        End Property


        Public Property dcSource() As String
            Get
                Return dSource
            End Get
            Set(ByVal value As String)
                dSource = value
            End Set
        End Property


        Public Property dcContributor() As String
            Get
                Return dContributor
            End Get
            Set(ByVal value As String)
                dContributor = value
            End Set
        End Property


        Public Property dcPublisher() As String
            Get
                Return dPublisher
            End Get
            Set(ByVal value As String)
                dPublisher = value
            End Set
        End Property



        Public Property dcCreator() As String
            Get
                Return dCreator
            End Get
            Set(ByVal value As String)
                dCreator = value
            End Set
        End Property



        Public Property dcFormat() As String
            Get
                Return dFormat
            End Get
            Set(ByVal value As String)
                dFormat = value
            End Set
        End Property


        Public Property dcType() As String
            Get
                Return dType
            End Get
            Set(ByVal value As String)
                dType = value
            End Set
        End Property

        Public Property dcDate() As String
            Get
                Return dDate
            End Get
            Set(ByVal value As String)
                dDate = value
            End Set
        End Property

        Public Property dcDescription() As String
            Get
                Return dDescription
            End Get
            Set(ByVal value As String)
                dDescription = value
            End Set
        End Property


        Public Property dcSubject() As String
            Get
                Return dSubject
            End Get
            Set(ByVal value As String)
                dSubject = value
            End Set
        End Property



        Public Property dcTitle() As String
            Get
                Return dTitle
            End Get
            Set(ByVal value As String)
                dTitle = value
            End Set
        End Property



        Public Property dcWiserdID() As String
            Get
                Return dWiserdID
            End Get
            Set(ByVal value As String)
                dWiserdID = value
            End Set
        End Property





    End Class

    Public Class ResponseTable


        Private rName As String
        Private rTotal As Integer
        Private rSuccessful As Integer
        Private rRefused As String
        Private rnoContact As String
        Private rIneligible As String
        Private rOther As String
        Private rRRate As String
        Private radjustedRR As String

        Public Property adjustedRRate() As String
            Get
                Return radjustedRR
            End Get
            Set(ByVal value As String)
                radjustedRR = value
            End Set
        End Property

        Public Property responseRate() As String
            Get
                Return rRRate
            End Get
            Set(ByVal value As String)
                rRRate = value
            End Set
        End Property


        Public Property other() As String
            Get
                Return rOther
            End Get
            Set(ByVal value As String)
                rOther = value
            End Set
        End Property


        Public Property ineligible() As String
            Get
                Return rIneligible
            End Get
            Set(ByVal value As String)
                rIneligible = value
            End Set
        End Property


        Public Property noContact() As String
            Get
                Return rnoContact
            End Get
            Set(ByVal value As String)
                rnoContact = value
            End Set
        End Property


        Public Property refused() As String
            Get
                Return rRefused
            End Get
            Set(ByVal value As String)
                rRefused = value
            End Set
        End Property


        Public Property successful() As Integer
            Get
                Return rSuccessful
            End Get
            Set(ByVal value As Integer)
                rSuccessful = value
            End Set
        End Property

        Public Property total() As Integer
            Get
                Return rTotal
            End Get
            Set(ByVal value As Integer)
                rTotal = value
            End Set
        End Property


        Public Property name() As String
            Get
                Return rName
            End Get
            Set(ByVal value As String)
                rName = value
            End Set
        End Property


    End Class

    Public Class place


        Public place As String


    End Class

    Public Class Fields
        Public Name As String
    End Class
End Namespace

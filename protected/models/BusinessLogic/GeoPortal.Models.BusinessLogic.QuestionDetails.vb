Namespace GeoPortal.Models.BusinessLogic
    Public Class subQuestionDetails

        Private QID As String
        Private qNo As String
        Private qText As String
        Private qThematicGroup As String
        Private qThematicTag As String
        Private qType As String
        Private qNotes As String
        Private sName As String
        Private sID As String
        Private DSrc As String
        Private SurCol As String
        Private score As Double
        Private id As Integer
        Private qRoot As String = "N/A"
        Public min As Integer
        Public max As Integer
        Public spatial As Boolean



        Public Property RootQuestion As String
            Get
                Return qRoot
            End Get
            Set(value As String)
                qRoot = value
            End Set
        End Property

        Public Property RecordID As Integer
            Get
                Return id
            End Get
            Set(value As Integer)
                id = value
            End Set
        End Property


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
                Return qNo
            End Get
            Set(ByVal value As String)
                qNo = value
            End Set
        End Property
        Public Property QuestionText() As String
            Get
                Return qText
            End Get
            Set(ByVal value As String)
                qText = value
            End Set
        End Property
        Public Property QuestionThematicGroup() As String
            Get
                Return qThematicGroup
            End Get
            Set(ByVal value As String)
                qThematicGroup = value
            End Set
        End Property

        Public Property QuestionThematicTag() As String
            Get
                Return qThematicTag
            End Get
            Set(ByVal value As String)
                qThematicTag = value
            End Set
        End Property

        Public Property QuestionType() As String
            Get
                Return qType
            End Get
            Set(ByVal value As String)
                qType = value
            End Set
        End Property

        Public Property QuestionNotes() As String
            Get
                Return qNotes
            End Get
            Set(ByVal value As String)
                qNotes = value
            End Set
        End Property

        Public Property SurveyName() As String
            Get
                Return sName
            End Get
            Set(ByVal value As String)
                sName = value
            End Set
        End Property

        Public Property SurveyID() As String
            Get
                Return sID
            End Get
            Set(ByVal value As String)
                sID = value
            End Set
        End Property

        Public Property DataSource() As String
            Get
                Return DSrc
            End Get
            Set(value As String)
                DSrc = value
            End Set
        End Property

        Public Property SurveyCollectionFrequency() As String
            Get
                Return SurCol
            End Get
            Set(ByVal value As String)
                SurCol = value
            End Set
        End Property

        Public Property Rank() As Double
            Get
                Return score
            End Get
            Set(ByVal value As Double)
                score = value
            End Set
        End Property


    End Class

    Public Class rootQuestionDetails

        Private QID As String
        Private qNo As String
        Private qText As String
        Private qThematicGroup As String
        Private qThematicTag As String
        Private qType As String
        Private qNotes As String
        Private sName As String
        Private sID As String
        Private DSrc As String
        Private SurCol As String
        Private score As Double
        Private id As Integer
        Private qRoot As String = "N/A"
        Public spatial As Boolean


        Public Property RootQuestion As String
            Get
                Return qRoot
            End Get
            Set(value As String)
                qRoot = value
            End Set
        End Property

        Public Property RecordID As Integer
            Get
                Return id
            End Get
            Set(value As Integer)
                id = value
            End Set
        End Property


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
                Return qNo
            End Get
            Set(ByVal value As String)
                qNo = value
            End Set
        End Property
        Public Property QuestionText() As String
            Get
                Return qText
            End Get
            Set(ByVal value As String)
                qText = value
            End Set
        End Property
        Public Property QuestionThematicGroup() As String
            Get
                Return qThematicGroup
            End Get
            Set(ByVal value As String)
                qThematicGroup = value
            End Set
        End Property

        Public Property QuestionThematicTag() As String
            Get
                Return qThematicTag
            End Get
            Set(ByVal value As String)
                qThematicTag = value
            End Set
        End Property

        Public Property QuestionType() As String
            Get
                Return qType
            End Get
            Set(ByVal value As String)
                qType = value
            End Set
        End Property

        Public Property QuestionNotes() As String
            Get
                Return qNotes
            End Get
            Set(ByVal value As String)
                qNotes = value
            End Set
        End Property

        Public Property SurveyName() As String
            Get
                Return sName
            End Get
            Set(ByVal value As String)
                sName = value
            End Set
        End Property

        Public Property SurveyID() As String
            Get
                Return sID
            End Get
            Set(ByVal value As String)
                sID = value
            End Set
        End Property

        Public Property DataSource() As String
            Get
                Return DSrc
            End Get
            Set(value As String)
                DSrc = value
            End Set
        End Property

        Public Property SurveyCollectionFrequency() As String
            Get
                Return SurCol
            End Get
            Set(ByVal value As String)
                SurCol = value
            End Set
        End Property

        Public Property Rank() As Double
            Get
                Return score
            End Get
            Set(ByVal value As Double)
                score = value
            End Set
        End Property



    End Class

    Public Class compoundQuestionDetails
        Private QID As String
        Private qNo As String
        Private qText As String
        Private qThematicGroup As String
        Private qThematicTag As String
        Private qType As String
        Private qNotes As String
        Private sName As String
        Private sID As String
        Private DSrc As String
        Private SurCol As String
        Private score As Double
        Private id As Integer
        Private qRoot As String = "N/A"
        Public spatial As Boolean


        Public Property RootQuestion As String
            Get
                Return qRoot
            End Get
            Set(value As String)
                qRoot = value
            End Set
        End Property

        Public Property RecordID As Integer
            Get
                Return id
            End Get
            Set(value As Integer)
                id = value
            End Set
        End Property


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
                Return qNo
            End Get
            Set(ByVal value As String)
                qNo = value
            End Set
        End Property
        Public Property QuestionText() As String
            Get
                Return qText
            End Get
            Set(ByVal value As String)
                qText = value
            End Set
        End Property
        Public Property QuestionThematicGroup() As String
            Get
                Return qThematicGroup
            End Get
            Set(ByVal value As String)
                qThematicGroup = value
            End Set
        End Property

        Public Property QuestionThematicTag() As String
            Get
                Return qThematicTag
            End Get
            Set(ByVal value As String)
                qThematicTag = value
            End Set
        End Property

        Public Property QuestionType() As String
            Get
                Return qType
            End Get
            Set(ByVal value As String)
                qType = value
            End Set
        End Property

        Public Property QuestionNotes() As String
            Get
                Return qNotes
            End Get
            Set(ByVal value As String)
                qNotes = value
            End Set
        End Property

        Public Property SurveyName() As String
            Get
                Return sName
            End Get
            Set(ByVal value As String)
                sName = value
            End Set
        End Property

        Public Property SurveyID() As String
            Get
                Return sID
            End Get
            Set(ByVal value As String)
                sID = value
            End Set
        End Property

        Public Property DataSource() As String
            Get
                Return DSrc
            End Get
            Set(value As String)
                DSrc = value
            End Set
        End Property

        Public Property SurveyCollectionFrequency() As String
            Get
                Return SurCol
            End Get
            Set(ByVal value As String)
                SurCol = value
            End Set
        End Property

        Public Property Rank() As Double
            Get
                Return score
            End Get
            Set(ByVal value As Double)
                score = value
            End Set
        End Property

    End Class

    Public Class SingleQuestion

        Private QID As String
        Private qNo As String
        Private qText As String
        Private qThematicGroup As String
        Private qThematicTag As String
        Private qType As String
        Private qNotes As String
        Private sName As String
        Private sID As String
        Private DSrc As String
        Private SurCol As String
        Private score As Double
        Private id As Integer
        Private qRoot As String = "N/A"
        Public spatial As Boolean


        Public Property RootQuestion As String
            Get
                Return qRoot
            End Get
            Set(value As String)
                qRoot = value
            End Set
        End Property

        Public Property RecordID As Integer
            Get
                Return id
            End Get
            Set(value As Integer)
                id = value
            End Set
        End Property


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
                Return qNo
            End Get
            Set(ByVal value As String)
                qNo = value
            End Set
        End Property
        Public Property QuestionText() As String
            Get
                Return qText
            End Get
            Set(ByVal value As String)
                qText = value
            End Set
        End Property
        Public Property QuestionThematicGroup() As String
            Get
                Return qThematicGroup
            End Get
            Set(ByVal value As String)
                qThematicGroup = value
            End Set
        End Property

        Public Property QuestionThematicTag() As String
            Get
                Return qThematicTag
            End Get
            Set(ByVal value As String)
                qThematicTag = value
            End Set
        End Property

        Public Property QuestionType() As String
            Get
                Return qType
            End Get
            Set(ByVal value As String)
                qType = value
            End Set
        End Property

        Public Property QuestionNotes() As String
            Get
                Return qNotes
            End Get
            Set(ByVal value As String)
                qNotes = value
            End Set
        End Property

        Public Property SurveyName() As String
            Get
                Return sName
            End Get
            Set(ByVal value As String)
                sName = value
            End Set
        End Property

        Public Property SurveyID() As String
            Get
                Return sID
            End Get
            Set(ByVal value As String)
                sID = value
            End Set
        End Property

        Public Property DataSource() As String
            Get
                Return DSrc
            End Get
            Set(value As String)
                DSrc = value
            End Set
        End Property

        Public Property SurveyCollectionFrequency() As String
            Get
                Return SurCol
            End Get
            Set(ByVal value As String)
                SurCol = value
            End Set
        End Property

        Public Property Rank() As Double
            Get
                Return score
            End Get
            Set(ByVal value As Double)
                score = value
            End Set
        End Property


    End Class

    Public Class allQuestions

        Public qid As String
        Public questionNumber As String
        Public questionText As String
        Public group As String
        Public tag As String



    End Class

End Namespace



Namespace GeoPortal.Models.BusinessLogic
    Public Class results


        Private res As String
        Private resourceCount As Integer


        'Relevance
        'totalCount
        Public Property totalCount As Integer
            Get
                Return resourceCount
            End Get
            Set(value As Integer)
                resourceCount = value
            End Set
        End Property

        Public Property questions() As String
            Get
                Return res
            End Get
            Set(ByVal value As String)
                res = value
            End Set
        End Property


    End Class


    Public Class spatialResults


        Private res As String
        Private resourceCount As Integer


        'Relevance
        'totalCount
        Public Property totalCount As Integer
            Get
                Return resourceCount
            End Get
            Set(value As Integer)
                resourceCount = value
            End Set
        End Property

        Public Property questions() As String
            Get
                Return res
            End Get
            Set(ByVal value As String)
                res = value
            End Set
        End Property


    End Class

End Namespace
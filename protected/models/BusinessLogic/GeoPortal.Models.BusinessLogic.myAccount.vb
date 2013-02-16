Namespace GeoPortal.Models.BusinessLogic

    Public Class myAccount


        Private successful As Boolean
        'Private newMessage As String
        Private newData As Object

        Public Property success() As Boolean
            Get
                Return successful
            End Get
            Set(ByVal value As Boolean)
                successful = value
            End Set
        End Property

        'Public Property message() As String
        '    Get
        '        Return newMessage
        '    End Get
        '    Set(ByVal value As String)
        '        newMessage = value
        '    End Set
        'End Property

        Public Property data() As Object
            Get
                Return newData
            End Get
            Set(ByVal value As Object)
                newData = value
            End Set
        End Property


    End Class

    Public Class MyAccountDetails
        Private uName As String
        Private fName As String
        Private lName As String
        Private eml As String
        Private eml2 As String
        Private Biography As String
        Private inst As String
        Private ContactNo As String
        Private add As String



        Public Property Address() As String
            Get
                Return add
            End Get
            Set(ByVal value As String)
                add = value
            End Set
        End Property

        Public Property Telephone() As String
            Get
                Return ContactNo
            End Get
            Set(ByVal value As String)
                ContactNo = value
            End Set
        End Property

        Public Property Bio() As String
            Get
                Return Biography
            End Get
            Set(ByVal value As String)
                Biography = value
            End Set
        End Property


        Public Property Institution() As String
            Get
                Return inst
            End Get
            Set(ByVal value As String)
                inst = value
            End Set
        End Property


        Public Property Email2() As String
            Get
                Return eml2
            End Get
            Set(ByVal value As String)
                eml2 = value
            End Set
        End Property

        Public Property Email() As String
            Get
                Return eml
            End Get
            Set(ByVal value As String)
                eml = value
            End Set
        End Property


        Public Property FirstName() As String
            Get
                Return fName
            End Get
            Set(ByVal value As String)
                fName = value
            End Set
        End Property


        Public Property LastName() As String
            Get
                Return lName
            End Get
            Set(ByVal value As String)
                lName = value
            End Set
        End Property


        Public Property UserName() As String
            Get
                Return uName
            End Get
            Set(ByVal value As String)
                uName = value
            End Set
        End Property



    End Class

End Namespace
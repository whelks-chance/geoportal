

Namespace GeoPortal.Models.BusinessLogic


    Public Class QualData


        Public id As String
        Public title As String
        Public thematicgroup As String
        Public creator As String
        Public pages As Integer
        Public qdate As Date



    End Class


    Public Class QualDataDC

        Public wiserdid As String
        Public title As String
        Public subject As String
        Public description As String
        Public dc_date As String
        Public type As String
        Public format As String
        Public creator As String
        Public publisher As String
        Public contributor As String
        Public source As String
        Public rights As String
        Public coverage As String
        Public language As String
        Public relation As String
        Public tier As String





    End Class
   
    Public Class pageCollection
        Public page As String
        Public place1 As String
        Public place1Count As Integer
        Public place2 As String
        Public place2Count As Integer
        Public place3 As String
        Public place3Count As Integer



    End Class


    Public Class qualWords

        Public name As String
        Public page As String
        Public count As Integer

    End Class


End Namespace


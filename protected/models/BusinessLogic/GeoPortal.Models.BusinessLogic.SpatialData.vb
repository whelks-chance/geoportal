
Namespace GeoPortal.Models.BusinessLogic

    Public Class quantDataRecord2
        Public tName As String
        Public sName As String
        Public sID As String
        Public sYear As String
        Public geography As String
        Public gName As String
        Public min As Integer
        Public max As Integer

    End Class



    Public Class geom

        Public Name As String
        Public geom As String


    End Class

    Public Class qualDataRecord

        Public identifier As String
        Public title As String
        Public lat As Double
        Public lon As Double
        Public counts As Integer
        Public Name As String



    End Class

    Public Class qualDataRecordGroup

        Public sName As String
        Public name As String
        Public gName As New List(Of qualCoords)
        Public recorddate As String
        Public creator As String
        Public pages As Double
        Public thematic As String



    End Class

    Public Class qualCoords
        Public name As String
        Public lat As Double
        Public lon As Double
        Public counts As Integer

    End Class


    Public Class adminDataRecord
        Public area_name As String
        Public the_geom As String
        Public year As String
        Public ID As String

    End Class


    Public Class SpatialLabels

        Public Name As String

    End Class

    Public Class ChoroFields

        Public Name As String

    End Class

    Public Class SpatialSubUnits

        Public Name As String

    End Class

    Public Class SpatialSearch

        Public quantData As New Dictionary(Of String, quantDataRecord)
        Public quantCount As Integer
        Public qualData As New Dictionary(Of String, qualDataRecordGroup)
        Public qualCount As Integer
        Public adminData As New Dictionary(Of String, adminDataRecord)
        Public adminCount As Integer

    End Class


    Public Class SpatialSearch2

        Public quantData As New Dictionary(Of String, quantDataRecord2)
        Public quantCount As Integer
        Public qualData As New Dictionary(Of String, qualDataRecordGroup)
        Public qualCount As Integer
        Public adminData As New Dictionary(Of String, adminDataRecord)
        Public adminCount As Integer

    End Class


    Public Class unLockPlaces

        'Public Name As String
        Public Name As New List(Of unlockDetails)


    End Class

    Public Class unlockDetails

        Public Name As String
        Public lat As String
        Public lon As String
        Public rank As Integer
        Public score As Double
        Public country As String
        Public Gazetteer As String
        Public Occurences As Integer
        Public wordsStats As List(Of words)



    End Class


    Public Class unlockGeom

        Public type As String
        Public coordinates As String

    End Class

    Public Class words

        Public page As String
        Public count As Integer

    End Class


    Public Class spatialSearchLayers

        Public id As String
        Public name As String
        Public colour As String
        Public geometry As New Dictionary(Of String, geom)
        Public year As Integer
        Public unit As String
        Public geographies As String


    End Class

    Public Class IdentifyFeatures

        Public tableName As String
        Public tableID As String
    End Class

    Public Class IdentifyResults

        Public tableName As String
        Public Html As String

    End Class

End Namespace



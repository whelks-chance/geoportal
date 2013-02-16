Imports System.Web.Services
Imports System.Web.Services.Protocols
Imports System.ComponentModel
Imports System.Xml
Imports System.Drawing
Imports System.IO
Imports GeoPortal.GeoPortal.Models.Data
Imports System.Web.Script.Services

' To allow this Web Service to be called from script, using ASP.NET AJAX, uncomment the following line.
<System.Web.Script.Services.ScriptService()> _
<System.Web.Services.WebService(Namespace:="http://tempuri.org/")> _
<System.Web.Services.WebServiceBinding(ConformsTo:=WsiProfiles.BasicProfile1_1)> _
<ToolboxItem(False)> _
Public Class SLDGenerator
    Inherits System.Web.Services.WebService

    <WebMethod()> _
    Public Function DynamicSLD(ByVal type As String, ByVal fromColour As String, ByVal toColour As String, ByVal fieldName As String, ByVal min As Integer, ByVal max As Integer, ByVal classes As Integer, ByVal layer As String) As XmlDocument
        Dim xmlSettings As New XmlWriterSettings
        xmlSettings.CloseOutput = False
        xmlSettings.Indent = True
        Dim ns As String = "http://www.opengis.net/sld"
        Dim ogc As String = "http://www.opengis.net/ogc"
        Dim colorList As Dictionary(Of Integer, Color) = generateColourRange(fromColour, toColour, generateEqualInterval((max - min), classes), classes)



        Dim stream As IO.MemoryStream = New MemoryStream()

        Using writer As XmlWriter = XmlWriter.Create(stream, xmlSettings)

            writer.WriteStartDocument()

            writer.WriteStartElement("StyledLayerDescriptor", ns)
            writer.WriteAttributeString("version", "1.0.0")
            writer.WriteAttributeString("xmlns", ns)
            writer.WriteAttributeString("xmlns", "sld", Nothing, ns)
            writer.WriteAttributeString("xmlns", "ogc", Nothing, "http://www.opengis.net/ogc")
            writer.WriteAttributeString("xmlns", "gml", Nothing, "http://www.opengis.net/gml")

            writer.WriteStartElement("sld", "NamedLayer", ns)

            'write sld name
            writer.WriteStartElement("sld", "Name", ns)
            writer.WriteString(layer)
            writer.WriteEndElement()

            writer.WriteStartElement("sld", "UserStyle", ns)
            'write featuretype
            writer.WriteStartElement("sld", "FeatureTypeStyle", ns)

            'write sld rule tag  for polygons
            writer.WriteStartElement("sld", "Rule", ns)
            'write sld polygon tag 
            writer.WriteStartElement("sld", "PolygonSymbolizer", ns)

            'write fill tag
            writer.WriteStartElement("sld", "Fill", ns)

            'write fill param
            writer.WriteStartElement("CssParameter")
            writer.WriteAttributeString("name", "fill")

            'write ogc function
            writer.WriteStartElement("ogc", "Function", ogc) ' "Function name=""categorize""")
            writer.WriteAttributeString(Nothing, "name", Nothing, "categorize")



            'write property name
            writer.WriteStartElement("ogc", "PropertyName", ogc)
            writer.WriteString(fieldName)
            writer.WriteEndElement()

            writer.WriteStartElement("ogc", "Literal", ogc)
            writer.WriteString(fromColour)
            writer.WriteEndElement()

            writer.WriteStartElement("ogc", "Literal", ogc)
            writer.WriteValue(min)
            writer.WriteEndElement()

            For Each Colour As KeyValuePair(Of Integer, Color) In colorList
                writer.WriteStartElement("ogc", "Literal", ogc)
                writer.WriteString("#" & Hex(Colour.Value.R) & Hex(Colour.Value.G) & Hex(Colour.Value.B))
                writer.WriteEndElement()


                writer.WriteStartElement("ogc", "Literal", ogc)
                writer.WriteValue(Colour.Key)
                writer.WriteEndElement()


            Next

            writer.WriteStartElement("ogc", "Literal", ogc)
            writer.WriteString(toColour)
            writer.WriteEndElement()
            'write end ogc function
            writer.WriteEndElement()

            'write end fill tag
            writer.WriteEndElement()

            'write css fill opacity
            writer.WriteStartElement("sld", "CssParameter", ns) '
            writer.WriteAttributeString(Nothing, "name", Nothing, "fill-opacity")
            writer.WriteValue(0.85)
            writer.WriteEndElement()

            'write fill end tag
            writer.WriteEndElement()

            'end sld rule for polygon
            writer.WriteEndElement()

            ' end featuretypestyle
            writer.WriteEndElement()
            ' end  user style
            writer.WriteEndElement()
            writer.WriteEndElement()
            writer.WriteEndDocument()

            writer.Flush()
            writer.Close()
        End Using


        Dim output As String
        stream.Position = 0
        Dim sr As New StreamReader(stream)

        Dim xD As New XmlDocument()
        xD.LoadXml(sr.ReadToEnd())


        Return xD
    End Function


    Private Function generateColourRange(ByVal fromColour As String, ByVal ToColour As String, ByVal intervalRange As Integer, ByVal intervalCount As Double) As Dictionary(Of Integer, Color)


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



        Do While i <= intervalCount

            Dim rAverage As Integer = rMin + CInt((rMax - rMin) * i / intervalCount)
            Dim gAverage As Integer = gMin + CInt((gMax - gMin) * i / intervalCount)
            Dim bAverage As Integer = bMin + CInt((bMax - bMin) * i / intervalCount)

            colourList.Add(classInterval, Color.FromArgb(rAverage, gAverage, bAverage))
            classInterval = (classInterval + intervalRange)
            i += 1
        Loop



        Return colourList


    End Function


    Private Function generateEqualInterval(ByVal Total As Integer, ByVal intervals As Integer)

        ' Dim 

        Dim intervalRange As Double = Math.Round(Total / intervals, System.MidpointRounding.ToEven)



        Return intervalRange
    End Function


    <WebMethod()> _
    Public Function DynamicSLD2(ByVal type As String, ByVal fromColour As String, ByVal toColour As String, ByVal fieldName As String, ByVal min As Integer, ByVal max As Integer, ByVal classes As Integer, ByVal layer As String, ByVal labelName As String) As XmlDocument
        Dim xmlSettings As New XmlWriterSettings
        xmlSettings.CloseOutput = False
        xmlSettings.Indent = True


        Dim ns As String = "http://www.opengis.net/sld"
        Dim ogc As String = "http://www.opengis.net/ogc"
        Dim colorList As Dictionary(Of Integer, Color) = generateColourRange(fromColour, toColour, generateEqualInterval((max - min), classes), classes - 1)



        Dim stream As IO.MemoryStream = New MemoryStream()

        Using writer As XmlWriter = XmlWriter.Create(stream, xmlSettings)

            'writer.WriteStartDocument()

            writer.WriteStartElement("StyledLayerDescriptor", ns)
            writer.WriteAttributeString("version", "1.0.0")
            writer.WriteAttributeString("xmlns", ns)
            writer.WriteAttributeString("xmlns", "sld", Nothing, ns)
            writer.WriteAttributeString("xmlns", "ogc", Nothing, "http://www.opengis.net/ogc")
            writer.WriteAttributeString("xmlns", "gml", Nothing, "http://www.opengis.net/gml")

            writer.WriteStartElement("sld", "NamedLayer", ns)

            'write sld name
            writer.WriteStartElement("sld", "Name", ns)
            writer.WriteString(layer)
            writer.WriteEndElement()

            writer.WriteStartElement("sld", "UserStyle", ns)
            'write featuretype
            writer.WriteStartElement("sld", "FeatureTypeStyle", ns)

            Dim previous As Integer = min

            For Each Colour As KeyValuePair(Of Integer, Color) In colorList

                'write sld rule tag  for polygons
                writer.WriteStartElement("sld", "Rule", ns)

                'write sld Rule Title - adds label to Legend
                writer.WriteStartElement("sld", "Title", ns)
                writer.WriteString(Colour.Key.ToString)
                writer.WriteEndElement()

                writer.WriteStartElement("ogc", "Filter", ogc)

                writer.WriteStartElement("ogc", "PropertyIsBetween", ogc) ' "Function name=""categorize""")
                'write property name
                writer.WriteStartElement("ogc", "PropertyName", ogc)
                writer.WriteString(fieldName)
                writer.WriteEndElement()
                'Lower Boundary
                writer.WriteStartElement("ogc", "LowerBoundary", ogc)
                writer.WriteStartElement("ogc", "Literal", ogc)
                writer.WriteValue(previous)
                writer.WriteEndElement()
                'end lower boundary
                writer.WriteEndElement()

                'upper boundary
                writer.WriteStartElement("ogc", "UpperBoundary", ogc)
                'write value


                writer.WriteStartElement("ogc", "Literal", ogc)
                writer.WriteValue(Colour.Key)
                writer.WriteEndElement()

                'end upper Boundary
                writer.WriteEndElement()

                'end property
                writer.WriteEndElement()
                'write end ogc filter
                writer.WriteEndElement()

                'write sld polygon tag 
                writer.WriteStartElement("sld", "PolygonSymbolizer", ns)

                'write fill tag
                writer.WriteStartElement("sld", "Fill", ns)

                'write fill param
                writer.WriteStartElement("CssParameter")
                writer.WriteAttributeString("name", "fill")

                'write fill colour
                writer.WriteString("#" & Hex(Colour.Value.R) & Hex(Colour.Value.G) & Hex(Colour.Value.B))
                writer.WriteEndElement()


                'write css fill opacity
                writer.WriteStartElement("sld", "CssParameter", ns) '
                writer.WriteAttributeString(Nothing, "name", Nothing, "fill-opacity")
                writer.WriteValue(0.85)
                writer.WriteEndElement()
                'end fill
                writer.WriteEndElement()

                'write end ogc Symbolizer
                writer.WriteEndElement()

                'write end fill tag
                writer.WriteEndElement()

                previous = Colour.Key

            Next


            'write label rules
            writer.WriteStartElement("sld", "Rule", ns)

            'write rule name
            writer.WriteStartElement("sld", "Name", ns)
            writer.WriteString("Default")
            writer.WriteEndElement()

            'write scale denominator
            writer.WriteStartElement("sld", "MaxScaleDenominator", ns)
            writer.WriteString(175000)
            writer.WriteEndElement()
            'text symoblizer
            writer.WriteStartElement("sld", "TextSymbolizer", ns)

            'write geom
            writer.WriteStartElement("sld", "Geometry", ns)
            'write property name
            writer.WriteStartElement("ogc", "PropertyName", ogc)
            writer.WriteString("the_geom")
            writer.WriteEndElement()
            'end geom
            writer.WriteEndElement()

            'write label field
            writer.WriteStartElement("sld", "Label", ns)

            writer.WriteStartElement("ogc", "PropertyName", ogc)
            writer.WriteString(labelName)
            writer.WriteEndElement()
            'end label
            writer.WriteEndElement()

            'write font properties   
            writer.WriteStartElement("sld", "Font", ns)

            'Font Name
            writer.WriteStartElement("sld", "CssParameter", ns)
            writer.WriteAttributeString(Nothing, "name", Nothing, "font-family")
            writer.WriteString("Times New Roman")
            writer.WriteEndElement()

            'Font Size
            writer.WriteStartElement("sld", "CssParameter", ns)
            writer.WriteAttributeString(Nothing, "name", Nothing, "font-size")
            writer.WriteString(10.5)
            writer.WriteEndElement()


            'font style
            writer.WriteStartElement("sld", "CssParameter", ns)
            writer.WriteAttributeString(Nothing, "name", Nothing, "font-style")
            writer.WriteString("italic")
            writer.WriteEndElement()

            'font weight    
            writer.WriteStartElement("sld", "CssParameter", ns)
            writer.WriteAttributeString(Nothing, "name", Nothing, "font-weight")
            writer.WriteString("bold")
            writer.WriteEndElement()

            'write end font
            writer.WriteEndElement()

            'label PLacement
            writer.WriteStartElement("sld", "LabelPlacement", ns)
            'point placement
            writer.WriteStartElement("sld", "PointPlacement", ns)
            'anchor Point
            writer.WriteStartElement("sld", "AnchorPoint", ns)
            'X
            writer.WriteStartElement("sld", "AnchorPointX", ns)
            writer.WriteString(0.2)
            writer.WriteEndElement()
            'Y

            writer.WriteStartElement("sld", "AnchorPointY", ns)
            writer.WriteString(0.2)
            writer.WriteEndElement()

            'end anchor point
            writer.WriteEndElement()
            'end point placement
            writer.WriteEndElement()
            'end label placement
            writer.WriteEndElement()

            'write Halo             
            writer.WriteStartElement("sld", "Halo", ns)
            'radius
            writer.WriteStartElement("sld", "Radius", ns)
            writer.WriteString(1.0)
            writer.WriteEndElement()
            'fill colour
            writer.WriteStartElement("sld", "Fill", ns)

            writer.WriteStartElement("CssParameter")
            writer.WriteAttributeString("name", "fill")

            'write fill colour
            writer.WriteString("#FFFFFF")
            writer.WriteEndElement()


            'write css fill opacity
            writer.WriteStartElement("sld", "CssParameter", ns) '
            writer.WriteAttributeString(Nothing, "name", Nothing, "fill-opacity")
            writer.WriteValue(0.5)
            writer.WriteEndElement()



            'End fill
            writer.WriteEndElement()

            'end halo               
            writer.WriteEndElement()

            'Font Colour
            writer.WriteStartElement("sld", "CssParameter", ns)
            writer.WriteAttributeString("name", "fill")

            'write fill colour
            writer.WriteString("#696969")
            writer.WriteEndElement()

            'write placement options

            writer.WriteStartElement("VendorOption")
            writer.WriteAttributeString("name", "polygonAlign")
            writer.WriteString("mbr")
            writer.WriteEndElement()

            writer.WriteStartElement("VendorOption")
            writer.WriteAttributeString("name", "maxDisplacement")
            writer.WriteString(20)
            writer.WriteEndElement()


            writer.WriteStartElement("VendorOption")
            writer.WriteAttributeString("name", "goodnessOfFit")
            writer.WriteString(1.0)
            writer.WriteEndElement()


            'end symbolizer
            writer.WriteEndElement()
            'end rule
            writer.WriteEndElement()


            ' end featuretypestyle
            writer.WriteEndElement()
            ' end  user style
            writer.WriteEndElement()
            writer.WriteEndElement()
            'writer.WriteEndDocument()

            writer.Flush()
            writer.Close()
        End Using


        Dim output As String
        stream.Position = 0
        Dim sr As New StreamReader(stream)

        Dim xD As New XmlDocument()
        xD.LoadXml(sr.ReadToEnd())


        Return xD
    End Function




End Class


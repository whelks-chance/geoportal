Imports System.Web
Imports System.Web.Services
Imports System.Xml
Imports System.IO
Imports System.Drawing



Public Class SLDProvider
    Implements System.Web.IHttpHandler

    Sub ProcessRequest(ByVal context As HttpContext) Implements IHttpHandler.ProcessRequest
        '{"fromColour":"#FFFFFF" , "toColour" : "#FF0000" , "fieldName" : "successful" , "min": 0, "max" : 21, "classes": 5, "layer": "x_sid_liw2007_lsoa"}
        Dim json As String = context.Request.QueryString("json")

        json = HttpContext.Current.Server.UrlDecode(json)


        Dim variables As SLDVariables = Newtonsoft.Json.JsonConvert.DeserializeObject(Of SLDVariables)(json)

        Dim xmlSettings As New XmlWriterSettings
        xmlSettings.CloseOutput = False
        xmlSettings.Indent = True


        Dim ns As String = "http://www.opengis.net/sld"
        Dim ogc As String = "http://www.opengis.net/ogc"
        Dim colorList As Dictionary(Of Integer, Color) = generateColourRange(variables.fromColour, variables.toColour, generateEqualInterval((variables.max - variables.min), variables.classes), variables.classes - 1, variables.min)



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
            writer.WriteString(variables.table)
            writer.WriteEndElement()

            writer.WriteStartElement("sld", "UserStyle", ns)
            'write featuretype
            writer.WriteStartElement("sld", "FeatureTypeStyle", ns)


            'write 0 rule

            writer.WriteStartElement("sld", "Rule", ns)

            'write sld Rule Title - adds label to Legend
            writer.WriteStartElement("sld", "Title", ns)
            writer.WriteString(0)
            writer.WriteEndElement()

            writer.WriteStartElement("ogc", "Filter", ogc)

            writer.WriteStartElement("ogc", "PropertyIsEqualTo", ogc)
            'write property name
            writer.WriteStartElement("ogc", "PropertyName", ogc)
            writer.WriteString(variables.fieldName)
            writer.WriteEndElement()
            'write literal
            writer.WriteStartElement("ogc", "Literal", ogc)
            writer.WriteValue(0)
            writer.WriteEndElement()


            'end equal to
            writer.WriteEndElement()


            'end filter
            writer.WriteEndElement()

            writer.WriteStartElement("sld", "PolygonSymbolizer", ns)

            'write fill tag
            writer.WriteStartElement("sld", "Fill", ns)

            'write fill param
            writer.WriteStartElement("CssParameter")
            writer.WriteAttributeString("name", "fill")

            'write fill colour
            writer.WriteString("#FFFFFF")
            writer.WriteEndElement()


            'write css fill opacity
            writer.WriteStartElement("CssParameter") '
            writer.WriteAttributeString(Nothing, "name", Nothing, "fill-opacity")
            writer.WriteValue(0.0)
            writer.WriteEndElement()
            'end symbolizer
            writer.WriteEndElement()
            'end fill
            writer.WriteEndElement()


            'end rule
            writer.WriteEndElement()


            Dim previous As Integer = 0

            For Each Colour As KeyValuePair(Of Integer, Color) In colorList


                'write sld rule tag  for polygons
                writer.WriteStartElement("sld", "Rule", ns)

                'write sld Rule Title - adds label to Legend
                writer.WriteStartElement("sld", "Title", ns)
                writer.WriteString((previous + 1) & " - " & Colour.Key.ToString)
                writer.WriteEndElement()

                writer.WriteStartElement("ogc", "Filter", ogc)

                writer.WriteStartElement("ogc", "PropertyIsBetween", ogc) ' "Function name=""categorize""")
                'write property name
                writer.WriteStartElement("ogc", "PropertyName", ogc)
                writer.WriteString(variables.fieldName)
                writer.WriteEndElement()
                'Lower Boundary
                writer.WriteStartElement("ogc", "LowerBoundary", ogc)
                writer.WriteStartElement("ogc", "Literal", ogc)
                writer.WriteValue(previous + 1)
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
                writer.WriteStartElement("CssParameter")  '
                writer.WriteAttributeString(Nothing, "name", Nothing, "fill-opacity")
                writer.WriteValue(0.85)
                writer.WriteEndElement()
                'end fill
                writer.WriteEndElement()
                'start stroke
                writer.WriteStartElement("sld", "Stroke", ns)
                'write cssparam for outline
                writer.WriteStartElement("CssParameter")
                writer.WriteAttributeString(Nothing, "name", Nothing, "stroke")
                writer.WriteValue("#C0C0C0")
                writer.WriteEndElement()
                'width
                writer.WriteStartElement("CssParameter")
                writer.WriteAttributeString(Nothing, "name", Nothing, "stroke-width")
                writer.WriteValue(0.5)
                writer.WriteEndElement()


                'end stroke
                writer.WriteEndElement()

                'write end ogc Symbolizer
                writer.WriteEndElement()

                'write end fill tag
                writer.WriteEndElement()

                previous = Colour.Key

            Next


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


        context.Response.ContentType = "text/xml"

        context.Response.ContentEncoding = System.Text.Encoding.UTF8

        'context.Response.AddHeader("content-disposition", "attachment; filename=" + Server.HtmlEncode(myFilename));

        xD.Save(context.Response.Output)

        context.Response.End()



    End Sub

    ReadOnly Property IsReusable() As Boolean Implements IHttpHandler.IsReusable
        Get
            Return False
        End Get
    End Property

    Private Function generateColourRange(ByVal fromColour As String, ByVal ToColour As String, ByVal intervalRange As Integer, ByVal intervalCount As Double, min As Integer) As Dictionary(Of Integer, Color)


        Dim classInterval As Integer = min

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
            classInterval = (classInterval + intervalRange) + 1
            i += 1
        Loop



        Return colourList


    End Function


    Private Function generateEqualInterval(ByVal Total As Integer, ByVal intervals As Integer)

        ' Dim 

        Dim intervalRange As Double = Math.Round(Total / intervals, System.MidpointRounding.AwayFromZero)



        Return intervalRange
    End Function


End Class


Public Class SLDVariables


    Public fromColour As String
    Public toColour As String
    Public fieldName As String
    Public min As Integer
    Public max As Integer
    Public classes As Integer
    Public table As String
    Public labelName As String
    Public Layer As String


End Class
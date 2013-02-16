function addLabel(px, offsetx, offsety, text,backgroundColour, textColour, pointSize, fontSize) {
    var lonlat = map.getLonLatFromViewPortPx(px);  
        var labelPoint = new OpenLayers.Geometry.Point(lonlat.lon,lonlat.lat);
        var newLabel = new OpenLayers.Feature.Vector(labelPoint);
        newLabel.attributes = {
        pointSize: pointSize,
        textColour:'#' + textColour,
        markerColour: '#' + backgroundColour,
        text: text,
        align: "cm",
        fontSize: fontSize + "pt",
        xOffset: offsetx,
        yOffset: offsety
    };
    anno.addFeatures([newLabel]);
}

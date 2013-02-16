var areaNode;
var linemeasureStyles = {
    "Point": {
        pointRadius: 4,
        graphicName: "square",
        fillColor: "white",
        fillOpacity: 1,
        strokeWidth: 1,
        strokeOpacity: 1,
        strokeColor: "#333333"
    },
    "Line": {
        strokeColor: "#FF0000",
        strokeOpacity: 0.3,
        strokeWidth: 3,
        strokeLinecap: "square"
    }
};
var lineStyle = new OpenLayers.Style();
lineStyle.addRules([
new OpenLayers.Rule({
    symbolizer: linemeasureStyles
})]);
var linemeasureStyleMap = new OpenLayers.StyleMap({
    "default": lineStyle
});
var length = new OpenLayers.Control.Measure(OpenLayers.Handler.Path, {
    displaySystem: 'english',
    geodesic: true,
    persist: true,
    measureComplete: finishMeasure,
    handlerOptions: {
        layerOptions: {
            styleMap: linemeasureStyleMap
        }
    },
    textNodes: null,
    callbacks: {
        create: function () {
            this.textNodes = [];
            layerRuler.removeFeatures(layerRuler.features);
            mouseMovements = 0;
        },
        modify: function (point, line) {
            if (mouseMovements++ < 5) {
                return;
            }
            var len = line.geometry.components.length;
            var from = line.geometry.components[len - 2];
            var to = line.geometry.components[len - 1];
            var ls = new OpenLayers.Geometry.LineString([from, to]);
            var dist = this.getBestLength(ls);
            if (!dist[0]) {
                return;
            }
            var total = this.getBestLength(line.geometry);
            var label = dist[0].toFixed(2) + " " + dist[1];
            var textNode = this.textNodes[len - 2] || null;
            if (textNode && !textNode.layer) {
                this.textNodes.pop();
                textNode = null;
            }
            if (!textNode) {
                var c = ls.getCentroid();
                textNode = new OpenLayers.Feature.Vector(
new OpenLayers.Geometry.Point(c.x, c.y), {}, {
    label: '',
    fontColor: "#FF0000",
    fontSize: "10px",
    fontFamily: "Arial",
    fontWeight: "bold",
    labelAlign: "cm"
});
                this.textNodes.push(textNode);
                layerRuler.addFeatures([textNode]);
            }
            textNode.geometry.x = (from.x + to.x) / 2;
            textNode.geometry.y = (from.y + to.y) / 2;
            textNode.style.label = label;
            textNode.layer.drawFeature(textNode);
            this.events.triggerEvent('measuredynamic', {
                measure: dist[0],
                total: total[0],
                units: dist[1],
                order: 1,
                geometry: ls
            });
        }
    }
});
length.events.on({
    "measure": handleMeasurements,
    "measurepartial": handleMeasurements
});
var areameasureStyles = {
    "Point": {
        pointRadius: 4,
        graphicName: "square",
        fillColor: "white",
        fillOpacity: 1,
        strokeWidth: 1,
        strokeOpacity: 1,
        strokeColor: "#333333"
    },
    "Polygon": {
        strokeWidth: 1.5,
        strokeOpacity: 1,
        strokeColor: "red",
        fillColor: "red",
        fillOpacity: 0.3
    }
};
var areaStyle = new OpenLayers.Style();
areaStyle.addRules([
new OpenLayers.Rule({
    symbolizer: areameasureStyles
})]);
var areaStyleMap = new OpenLayers.StyleMap({
    "default": areaStyle
});
var area = new OpenLayers.Control.Measure(OpenLayers.Handler.Polygon, {
    displaySystem: 'english',
    geodesic: true,
    persist: true,
    measureComplete:finishMeasure,
    handlerOptions: {
        layerOptions: {
            styleMap: areaStyleMap
        }
    }
});
area.events.on({
    "measure": handleMeasurements,
    "measurepartial": handleMeasurements
});
map.addControl(length);
map.addControl(area);



function handleMeasurements(event) {
    var geometry = event.geometry;
    var units = event.units;
    var order = event.order;
    var measure = event.measure;
    var element = document.getElementById('txtStatusMessage');
    var acres;
    var out = "";
    if (order == 1) {
        out += measure.toFixed(2) + " " + units;
    } else if (order == 2 && units === "ft" && measure >= 43560) {
        acres = measure / 43560;
        out += acres.toFixed(2) + " acres";
    } else {
        out += measure.toFixed(2) + " " + units 
    }
    if (geometry.CLASS_NAME == "OpenLayers.Geometry.Polygon"){
        element.innerHTML = "&nbsp;&nbsp; Area:" + out + '<sup>2</sup>';
        if (areaNode != null) {
            
            layerRuler.destroyFeatures([areaNode]);
        }
        areaPnt = geometry.getCentroid();
        if (areaPnt != null) {
            areaNode = new OpenLayers.Feature.Vector(
        new OpenLayers.Geometry.Point(areaPnt.x, areaPnt.y), {}, {
            id: 'areaLabel',
            label: out + ' ' + 'square',
            fontColor: "#FF0000",
            fontSize: "10px",
            fontFamily: "Arial",
            fontWeight: "bold",
            labelAlign: "cm"
        });
            layerRuler.addFeatures([areaNode]);
        }    
    }else {
        element.innerHTML = "&nbsp;&nbsp; Total Distance:" + out;
    }
};

function finishMeasure(geom) {
    areaNode = new OpenLayers.Feature.Vector(geom, areaStyleMap);
    layerRuler.addFeatures([areaNode])

    area.deactivate();
    length.deactivate();
    Ext.getCmp('mappanel').body.applyStyles('cursor:default');
}


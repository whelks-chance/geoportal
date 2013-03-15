var resultLayer;
function buildMapLayer(name, type, surveyName, surveyYear, unit, SID) {
    var scale = map.getScale();
    var LayerType = type;
    var attr = surveyName + "_" + surveyYear + "_" + unit;
    var LayerName = name + " - " + unit;
    resultLayer = new OpenLayers.Layer.Vector(LayerName, {
        displayInLayerSwitcher: true, isBaseLayer: false, attribution: attr,
        styleMap: new OpenLayers.StyleMap({
            fillOpacity: 0.75,
            fillColor: "${colour}",
            //label: "${label}",
            fontColor: "black",
            fontFamily: "sans-serif",
            labelAlign: "c",
            strokeWidth: 1,
            strokeColor: 'gray'
        }, {
            rules: [
                new OpenLayers.Rule({
                    minScaleDenominator: 1000000,
                    symbolizer: {
                        fontSize: "6px",
                        fontWeight: "bold"
                    }
                }),
                new OpenLayers.Rule({
                    maxScaleDenominator: 50000,
                    minScaleDenominator: 1000,
                    symbolizer: {
                        fontSize: "36px"
                    }
                }),
                new OpenLayers.Rule({
                    maxScaleDenominator: 1000,
                    symbolizer: {
                        fontSize: "25px"
                    }
                })
            ]
        })
    });




    resultLayer.events.on({
        'featureselected': function (a) {
            title = surveyName;
            html = '<table class="attributes" border="1" cellpadding="2" cellspacing="2"><tbody><tr><td class="field">NAME:</td><td class="value">' + a.feature.attributes.attributes.surveyName + '</td></tr><tr><td class="field">Survey ID:</td><td class="value">' + a.feature.attributes.attributes.survey_id + '</td></tr></tbody></table>';
            createPopup("surveyResults", a.feature, title, html, 300, "auto");
        },
        'featureunselected': function (a) {
            popup.destroy();
        }
    });






    var id = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for (var z = 0; z < 5; z++)
        id += possible.charAt(Math.floor(Math.random() * possible.length));



    resultLayer.id = id;

    var mapPanel = Ext.getCmp('mappanel');

    var nodes = Ext.getCmp('TOCTP').root.childNodes;

    for (var a = 0; a < nodes.length; a++) {

        if (nodes[a].text == 'Search Results') {
            var subNodes = nodes[a];
            for (var i = 0; i < subNodes.childNodes.length; i++) {
                if (subNodes.childNodes[i].text == type) {
                    var index = -1;
                    for (var j = 0; j < subNodes.childNodes[i].childNodes.length; j++) {
                        var NodesurveyName = subNodes.childNodes[i].childNodes[j].text;
                        if (NodesurveyName == surveyName) {
                            index = j;

                        }
                    }
                    if (index == -1) {

                        var newSurvey = new Ext.tree.TreeNode({ rootVisible: false, leaf: false, icon: " images/silk/table_multiple.png", text: surveyName, expanded: false, lines: true, id: 'nd_' + surveyName });
                        var newYear = new Ext.tree.TreeNode({ rootVisible: false, leaf: false, icon: " images/silk/table_multiple.png", text: surveyYear,nodeType: "node", expanded: false, lines: true, id: 'nd_' + surveyName + surveyYear });
                        var newUnit = new GeoExt.tree.LayerNode({
                            text: unit,
                            layer: resultLayer,
                            lines: true,
                            icon: "images/silk/table.png",
                            leaf: true,
                            SID: SID,
                            LayerID: id
                        });

                        newYear.appendChild(newUnit);
                        newSurvey.appendChild(newYear);
                        subNodes.childNodes[i].appendChild(newSurvey);

                    } else {

                        var surveyNode = subNodes.childNodes[i].childNodes[index];

                        var yearIndex = -1;

                        for (var k = 0; k < surveyNode.childNodes.length; k++) {
                            if (surveyNode.childNodes[k].text == surveyYear) {
                                yearIndex = k;
                            }
                            if (yearIndex == -1) {

                                var newYear = new Ext.tree.TreeNode({ rootVisible: false, leaf: true, icon: " images/silk/table_multiple.png", text: surveyYear, nodeType: "node", expanded: false, lines: true, id: 'nd_' + surveyName + surveyYear });
                                var newUnit = new GeoExt.tree.LayerNode({
                                    text: unit,
                                    SID: SID,
                                    LayerID: id,
                                    lines: true,
                                    layer: resultLayer,
                                    icon: "images/silk/table.png",
                                    leaf: true,
                                    expanded: true
                                });

                                newYear.appendChild(newUnit);
                                surveyNode.appendChild(newYear);



                            } else {


                                var yearNode = surveyNode.childNodes[yearIndex];

                                var newUnit = new GeoExt.tree.LayerNode({
                                    text: unit,
                                    SID: SID,
                                    layer: resultLayer,
                                    LayerID: id,
                                    lines: true,
                                    icon: "images/silk/table.png",
                                    leaf: true,
                                    expanded: true
                                });

                                yearNode.appendChild(newUnit);


                            }

                        }

                    }
                }
            }
        }


    };

    resultLayer.setVisibility(false);
    map.addLayer(resultLayer);
    //map.zoomToExtent(resultLayer.getDataExtent());
    Ext.getCmp('TOCTP').doLayout();



    resultsLayers.push(resultLayer);

    return id;

};


function createPopup(a, b, c, d, e, f) {
    popupState = "kill";
    popup = new GeoExt.Popup({
        title: c,
        location: b,
        anchored: true,
        modal: false,
        //x: 250,
        //y: 150,
        map: map,
        width: e,
        height: "auto",
        html: d,
        autoScroll: true,
        maximizable: true
        //collapsible: true
    });
    popup.show();
}


function addFeature(Feature, map_id) {

    var resultLayer = map.getLayer(map_id);

    var wkt = new OpenLayers.Format.WKT();
    var bounds;

    var shape = Feature;
    var wkt_feature = wkt.read(shape.EWKT);
    var wkt_array = [wkt_feature];
    wkt_feature.geometry.transform(geographic, OSGB);

    for (var i = 0; i < wkt_array.length; ++i) {
        if (!bounds) {
            bounds = wkt_array[i].geometry.getBounds();
        } else {
            bounds.extend(wkt_array[i].geometry.getBounds());
        }

    }

    var feature = new OpenLayers.Feature.Vector(wkt_feature.geometry, { 'colour': Feature.Colour, 'label': shape.Name, attributes: { survey_id: shape.SurveyID, surveyName: shape.Name, surveyYear: shape.Year, group: 'results'} });


    resultLayer.addFeatures(feature);


};

function addQualFeature(Feature, map_id, type) {

    var LayerType = type;

    if (LayerType == "Proportional") {

        var resultLayer = map.getLayer(map_id);



        var Point = new OpenLayers.Geometry.Point(Feature.lon, Feature.lat);
        Point.transform(geographic, OSGB);

        var feature = new OpenLayers.Feature.Vector(Point, { 'colour': Feature.colour, 'label': Feature.title, 'counts': Feature.counts, QID: Feature.QID });
        resultLayer.addFeatures(feature);

    } else {

        var resultLayer = map.getLayer(map_id);


        resultLayer.addSource(new Heatmap.Source(new OpenLayers.LonLat(Feature.lon, Feature.lat).transform(geographic, OSGB)));



    }




};



function buildQualMapLayer(title, type, qualType, SID, LayerName, maptype, colour) {

    var LayerType = maptype;
    var id = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for (var z = 0; z < 5; z++)
        id += possible.charAt(Math.floor(Math.random() * possible.length));




    if (LayerType == "Proportional") {


        //var LayerName = Ext.getCmp('txtQLayerName').getValue();
        resultLayer = new OpenLayers.Layer.Vector(LayerName, {
            displayInLayerSwitcher: true, isBaseLayer: false,
            styleMap: new OpenLayers.StyleMap({
                fillOpacity: 0.6,
                fillColor: "${colour}",
                label: "${label}",
                fontColor: "gray",
                fontFamily: "arial",
                labelAlign: "c",
                strokeWidth: 1,
                strokeColor: 'gray',
                pointRadius: "${counts}"
            })
        });



    } else {

        resultLayer = new Heatmap.Layer(LayerName, {
            displayInLayerSwitcher: false, isBaseLayer: false, opacity: 0.25, intensity: 0.1 , endColour: colour
        });
    }

    resultLayer.id = id;
    var mapPanel = Ext.getCmp('mappanel');

    var nodes = Ext.getCmp('TOCTP').root.childNodes;

    for (var a = 0; a < nodes.length; a++) {

        if (nodes[a].text == 'Search Results') {
            var subNodes = nodes[a];
            for (var i = 0; i < subNodes.childNodes.length; i++) {
                if (subNodes.childNodes[i].text == type) {
                    var index = -1;
                    for (var j = 0; j < subNodes.childNodes[i].childNodes.length; j++) {
                        var NodesurveyName = subNodes.childNodes[i].childNodes[j].text;
                        if (NodesurveyName == qualType) {
                            index = j;

                        }
                    }
                    if (index === -1) {

                        var newSource = new Ext.tree.TreeNode({ rootVisible: false, leaf: false, icon: " images/silk/book.png", text: qualType, expanded: true, lines: true, id: 'nd_' + SID });
                        var newRecord = new GeoExt.tree.LayerNode({
                            text:LayerName,
                            layer: resultLayer,
                            //layerStore: mapPanel.layers,
                            lines: true,
                            icon: "images/silk/page_white_text.png",
                            leaf: true,
                            expanded: true,
                            group: 'results',
                            SID: SID,
                            LayerID: id
                        });
                        newSource.appendChild(newRecord);
                        subNodes.childNodes[i].appendChild(newSource);

                    } else {

                        var surveyNode = subNodes.childNodes[i].childNodes[index];

                        var newUnit = new GeoExt.tree.LayerNode({
                            text: LayerName,
                            group: 'results',
                            SID: SID,
                            layer: resultLayer,
                            LayerID: id,
                            lines: true,
                            icon: "images/silk/page_white_text.png",
                            leaf: true,
                            expanded: true
                        });

                        surveyNode.appendChild(newUnit);


                    }

                }

            }
        }


    };

    resultLayer.setVisibility(false);
    map.addLayer(resultLayer);
    //Ext.getCmp('TOCTP').doLayout();
    return id;

};



function showResults(layers, layerName, fromColour, toColour, Choropleth, unit, type, SID, id, tableName, min , max) {
    var maxLength ;
    if (layers == "ALL") {
        var CQL_Filter = false;
        maxLength = 400;
    } else {
        var CQL_Filter = 'area_name IN (';

        var chosen_layers = layers.toString().split(';');

        for (var i = 0; i < chosen_layers.length; i++) {


            if (i != chosen_layers.length - 1) {
                var unit_name = chosen_layers[i];


                //CQL_Filter += "'" + jQuery.trim(unit_name) + "' or area_name="

                CQL_Filter += "'" + jQuery.trim(unit_name) + "',"

            } else {
                var unit_name = chosen_layers[i];
                //CQL_Filter += "'" + jQuery.trim(unit_name) + "'"

                CQL_Filter += "'" + jQuery.trim(unit_name) + "')"

            }

        }
        maxLength =  CQL_Filter.length + 400;
    }


    var SLD = Ext.Ajax.request({
//        url: 'SpatialData/DynamicSLD',
        url: DynamicSLD,
        method: 'POST',
        params: {
            layer: 'WISERD:SQL',
            type: 'test',
            fromColour: fromColour,
            toColour: toColour,
            min: min,
            max: max,
            fieldName: "successful",
            classes: 5,
            labelName: 'area_name'
        },
        callback: function () {

            maxLength += SLD.conn.responseText.length;



            //"http://23.21.162.107/geoportal/services/SLDProvider.ashx?json=";
//            var startUrl = "http://131.251.172.95:7000/geoportal/services/SLDProvider.ashx?json=";
            var startUrl = geoportalAddr + 'r=SpatialData/DynamicSLD';

//            var params = encodeURIComponent('{"fromColour":"' + fromColour + '","toColour":"' + toColour + '","fieldName":"successful","min":' + min + ',"max": ' + max + ',"classes":' + 5 + ',"layer": "' + layerName + '"}');
            var params = encodeURIComponent('&fromColour=' + fromColour + '&toColour=' + toColour + 'fieldName=successful' + '&min=' + min + '&max=' + max + '&classes=' + 5 + '&layer=' + layerName);

            var fullURL = startUrl + params;
            var new_layer;

            if (CQL_Filter == false) {

                new_layer = new OpenLayers.Layer.WMS(layerName,
                    //"http://23.21.162.107:8081/geoserver/WISERD/wms",
                    //"http://localhost:5432/geoserver/WISERD/wms",
//		                        "http://131.251.172.95:7000/geoserver/WISERD/wms/",
                    geoserverWMS,
                    {
                        transparent: true,
                        attribution: 'QuantData',
                        sld_body: SLD.conn.responseText,
                        viewparams: 'tableName:' + tableName,
                        styles: '',
                        id: id
                    },
                    {
                        ratio: 1,
                        singleTile: true,
                        tiled: true,
                        tileOptions: {
                            maxGetUrlLength: maxLength,
                            width: 512,
                            height: 512
                        },
                        transitionEffect: 'resize',
                        format: 'png',
                        displayInLayerSwitcher: false,
                        unsupportedBrowsers: []
                    }
                );
            } else {

                new_layer = new OpenLayers.Layer.WMS(layerName,
                    //"http://23.21.162.107:8081/geoserver/WISERD/wms",
                    // "http://localhost:5432/geoserver/WISERD/wms",
//                                "http://131.251.172.95:7000/geoserver/WISERD/wms/",
                    geoserverWMS,
                    { layers: 'WISERD:SQL', transparent: true, attribution: 'QuantData', sld_body: SLD.conn.responseText, viewparams: 'tableName: ' + tableName, styles: '', CQL_Filter: CQL_Filter, id: id },
                    { ratio: 1, singleTile: true, tiled: true, tileOptions: { maxGetUrlLength: maxLength, width: 512, height: 512 }, transitionEffect: 'resize', format: 'png',
                        displayInLayerSwitcher: false, unsupportedBrowsers: []
                    }
                );

            }

            new_layer.group = id;


            map.addLayer(new_layer);




            //var icon = encodeURI("http://23.21.162.107:8081/geoserver/WISERD/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=30&LAYER=WISERD:SQL&sld=" + fullURL);
            //var LegendURL = encodeURI("http://23.21.162.107:8081/geoserver/WISERD/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&LAYER=WISERD:SQL&sld=" + fullURL);
//            var icon = encodeURI("http://131.251.172.95:7000/geoserver/WISERD/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=30&LAYER=WISERD:SQL&sld=" + fullURL);
//            var LegendURL = encodeURI("http://131.251.172.95:7000/geoserver/WISERD/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&LAYER=WISERD:SQL&sld=" + fullURL);

            var icon = encodeURI(geoserverWMS + "?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=30&LAYER=WISERD:SQL&sld=" + fullURL);
            var LegendURL = encodeURI(geoserverWMS + "?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&LAYER=WISERD:SQL&sld=" + fullURL);

            var mp = Ext.getCmp('mappanel');


            var nodes = Ext.getCmp('TOCTP').root.childNodes;

            for (var a = 0; a < nodes.length; a++) {

                if (nodes[a].text == 'Search Results') {
                    var subNodes = nodes[a];
                    for (var i = 0; i < subNodes.childNodes.length; i++) {
                        if (subNodes.childNodes[i].text == type) {

                            var newNode = new GeoExt.tree.LayerNode({
                                text: layerName,
                                layer: new_layer,
                                lines: true,
                                icon: icon,
                                leaf: true,
                                SID: SID,
                                LayerID: tableName,
                                id: id,
                                legendURL: LegendURL
                            });

                            newNode.on({
                                checkchange: function (node, checked) {

                                    //check to see if node is already checked
                                    if (checked != true) {
                                        //if checked remove node from active layer list
                                        //get idx of node in infoLayers array
                                        var idx = infoLayers.indexOf(node);
                                        //if idx is not -1 (i.e. object has been found) then remove it
                                        if (idx != -1) {
                                            infoLayers.remove(node)
                                        };

                                        console.log(infoLayers.length);
                                    } else {
                                        //if not add it to the infoLayers array declared in map.js

                                        infoLayers.push(node);

                                        console.log(infoLayers.length);
                                    }
                                }

                            });
                            //add node to infoLayers array so that it can be included in identify search results
                            infoLayers.push(newNode);
                            subNodes.childNodes[i].appendChild(newNode);

                        }
                    }
                }
            }

            //loadMask.hide();

        }

    });
}



function addChoropleth(layer, layerName, fromColour, toColour, min, max, unit, SID, field, classes, type, tableName) {
    //var tableName = 'x_sid_liw2007_aefa_';

    var SLD = Ext.Ajax.request({
        url: '../../Services/SLDGenerator.asmx/DynamicSLD2',
        method: 'POST',
        params: {
            layer: 'WISERD:SQL',
            type: 'test',
            fromColour: fromColour,
            toColour: toColour,
            min: min,
            max: max,
            fieldName: field,
            classes: classes
        },
        callback: function () {

            //add unique layer id


            var maxLength = SLD.conn.responseText.length + 400;

            var new_layer = new OpenLayers.Layer.WMS.Post(layerName,
                //"http://23.21.162.107:8081/geoserver/WISERD/wms",
                //"http://localhost:5432/geoserver/WISERD/wms",
//                "http://131.251.172.95:7000/geoserver/WISERD/wms",
                geoserverWMS,
                { layers: 'WISERD:SQL', transparent: true, attribution: 'QuantData', sld_body: SLD.conn.responseText, viewparams: 'tableName: ' + tableName },
                { ratio: 1, singleTile: true, tiled: true, tileOptions: { maxGetUrlLength: maxLength, width: 512, height: 512 }, transitionEffect: 'resize', format: 'png',
                    displayInLayerSwitcher: false,
                    unsupportedBrowsers: []
                });


            new_layer.group = id


            map.addLayer(new_layer);


            var nodes = Ext.getCmp('TOCTP').root.childNodes;

            for (var a = 0; a < nodes.length; a++) {

                if (nodes[a].text == 'Search Results') {
                    var subNodes = nodes[a];
                    for (var i = 0; i < subNodes.childNodes.length; i++) {
                        if (subNodes.childNodes[i].text == type) {

                            var newNode = new GeoExt.tree.LayerNode({
                                text: layerName,
                                layer: new_layer,
                                lines: true,
                                //icon: 'http://23.21.162.107:8081/geoserver/WISERD/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=WISERD:SQL&viewparams=tableName:' + tableName + "&sld_body='" + encodeURIComponent(SLD.conn.responseText) + "'",
//                                icon: 'http://131.251.172.95:7000/geoserver/WISERD/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=WISERD:SQL&viewparams=tableName:' + tableName + "&sld_body='" + encodeURIComponent(SLD.conn.responseText) + "'",
                                icon: geoserverWMS + '?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=WISERD:SQL&viewparams=tableName:' + tableName + "&sld_body='" + encodeURIComponent(SLD.conn.responseText) + "'",
                                leaf: true,
                                SID: SID,
                                LayerID: SID
                            });


                            subNodes.childNodes[i].appendChild(newNode);

                        }
                    }
                }
            }


        }

    });






}

function addWMS(name, title, colour, rec) {

    var new_layer = new OpenLayers.Layer.WMS(title,
        "http://inspire.wales.gov.uk:80/maps/wms",
        { layers: name, transparent: true, env: 'fill:' + colour.toString().replace('#', '')  },
        { ratio: 1, singleTile: true, tileOptions: { maxGetUrlLength: 2048, width: 512, height: 512 }, transitionEffect: 'resize', format: 'png', displayInLayerSwitcher: false });


    new_layer.group = name

    map.addLayer(new_layer);


    var nodes = Ext.getCmp('TOCTP').root.childNodes;

    for (var a = 0; a < nodes.length; a++) {

        if (nodes[a].text == 'Boundary Data') {
            var subNodes = nodes[a];
            var newNode = new GeoExt.tree.LayerNode({
                text: title,
                layer: new_layer,
                lines: true,
                icon: 'http://inspire.wales.gov.uk:80/maps/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=' + name, //"images/silk/vector.png",
                leaf: true
            });


            subNodes.appendChild(newNode);


        }
    }



}
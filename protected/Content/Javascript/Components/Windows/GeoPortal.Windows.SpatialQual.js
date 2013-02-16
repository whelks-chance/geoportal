GeoPortal.Windows.SpatialQual = Ext.extend(Ext.Window, {
    title: 'Add Data to Map',
    layout: 'form',
    id: 'winQualSpatial',
    QID: null,
    frame: true,
    width: 400,
    height: 150,
    padding: '10px',
    buttons: [{ xtype: 'button', text: 'Add to Map', icon: 'images/silk/add.png',
        handler: function () {
            var form = Ext.getCmp('qualSpatialForm');
            form.getForm().submit({
                waitMsg: 'Generating Map Data....',
                url: genQualSpatialDataURL,
                params: ({ ID: Ext.getCmp('winQualSpatial').QID }),
                success: function (form, action) {

                    var id = buildQualMapLayer(action.result.shapes[0].title, "Qualitative Data", "Transcripts", Ext.getCmp('winQualSpatial').QID, Ext.getCmp('txtQLayerName').getValue());
                    var heat = new Heatmap.Layer("Heatmap", { opacity: 0.5 });


                    for (var i = 0; i < action.result.shapes.length; i++) {
                        var lat = action.result.shapes[i].lat;
                        var lon = action.result.shapes[i].lon


                        for (var cnt = 0; cnt < action.result.shapes[i].counts; cnt++) {
                            heat.addSource(new Heatmap.Source(new OpenLayers.LonLat(lon, lat).transform(geographic, OSGB)));
                        };



                        var feature = new Object();
                        feature.lat = lat;
                        feature.lon = lon;
                        feature.colour = Ext.getCmp('qualColour').getValue();
                        feature.counts = (action.result.shapes[i].counts * 2);
                        feature.title = action.result.shapes[i].title;
                        feature.QID = Ext.getCmp('winQualSpatial').QID;
                        addQualFeature(feature, id)
                    }

                    //map.addLayer(heat);
                    Ext.getCmp('winQualSpatial').close();

                }
            });
        }
    }],
    initComponent: function () {
        this.Colour = { xtype: 'colorpickerfield', fieldLabel: 'Colour 1', name: 'fieldColour1', value: '#F60434', id: 'qualColour', width: 200 };
        this.items = [{ xtype: 'form', id: 'qualSpatialForm', items: [this.Colour, { xtype: 'textfield', fieldLabel: 'LayerName', id: 'txtQLayerName', anchor: '95%'}], frame: true}];
        GeoPortal.Windows.SpatialQual.superclass.initComponent.call(this);
    }
});
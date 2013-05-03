GeoPortal.Windows.Buffer = Ext.extend(Ext.Window, {
    title: 'Buffer Point',
//    id: 'winBuffer',
    width: Ext.getBody().getViewSize().width * 0.3,
    evt: null,
    modal: true,
    resizable: false,
    id: 'winBuffer',
    buttons: [{
        xtype: 'button',
        text: 'Search',
        handler: function () {
            var win = Ext.getCmp('winBuffer');
           SearchLayer.destroyFeatures();
            var projection = map.getProjectionObject();
            var xy = win.evt.xy;
            var radius = Ext.getCmp('distSliderBuff').getValue() * 1000;
            var lonlat = map.getLonLatFromViewPortPx(xy);
            var pos = new OpenLayers.LonLat(lonlat.lon, lonlat.lat);

            var centroid = new OpenLayers.Geometry.Point(lonlat.lon, lonlat.lat);

            var size = new OpenLayers.Size(16, 16);
            var offset = new OpenLayers.Pixel(0, 0);
            var icon = new OpenLayers.Icon('images/silk/flag_red.png', size, offset);


            var sides = 100;
            var new_geom = OpenLayers.Geometry.Polygon.createGeodesicPolygon(centroid, radius, sides, 45, mercator);

            var layer = map.getLayer('placeSearch');

            layer.addMarker(new OpenLayers.Marker(pos, icon));
            // var wgsPos = pos.transform(geographic, geographic)
            var new_feature = new OpenLayers.Feature.Vector(new_geom, { 'type': 'place' });
            SearchLayer.addFeatures([new_feature]);

            map.zoomToExtent(new_geom.getBounds());

//            var loadMask = new Ext.LoadMask(Ext.getBody(), { msg: "Retrieving Spatial Search Results....", removeMask: false });
//            loadMask.show();
            doSpatialSearch(new_geom);
            Ext.getCmp('winBuffer').close();
            
        }

    }],
    initComponent: function () {

        this.tip = new Ext.slider.Tip({
            getText: function (thumb) {
                return String.format('<b>{0}km</b>', thumb.value);
            }
        });

        this.slider = new Ext.Slider({
            id: 'distSliderBuff',
            fieldLabel: 'Search Radius',
            width: 200,
            value: 2,
            increment: 2,
            minValue: 0,
            maxValue: 20,
            plugins: this.tip
        });

        this.items = [
            {
                xtype: 'form',
                bodyStyle: 'padding: 5px',
                height: 75,
                width: 400,
                items: [this.slider]
            }
        ];
        GeoPortal.Windows.Buffer.superclass.initComponent.call(this);
    }
});
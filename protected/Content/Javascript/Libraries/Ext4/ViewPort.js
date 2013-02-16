Ext.require(['*']);
Ext.onReady(function () {
    var cw;
    Ext.create('Ext.Viewport', {
        layout: {
            type: 'border',
            padding: 5
        },
        defaults: {
            split: true
        },
        items: [{
            region: 'west',
            collapsible: true,
            //collapseMode: 'mini', 
            title: 'Table of Contents',
            split: true,
            width: '30%',
            items: new GeoPortal.Panels.SidePanel()
        }, {
            region: "center",
            id: "mappanel",
            //title: "Map",
            xtype: "gx_mappanel",
            map: map,
            tbar: new GeoPortal.Toolbars.MapToolbar(),
            bbar: new GeoPortal.Toolbars.MapStatus(),
            items: [{
                xtype: "gx_zoomslider",
                id: 'zoomSlider',
                vertical: true,
                height: 100,
                plugins: new GeoExt.ZoomSliderTip({ template: "<div>Zoom Level: {zoom}</div><div>Scale: 1 : {scale}</div>" })
            }],
            layers: layers,
            split: true
        }]
    });
});
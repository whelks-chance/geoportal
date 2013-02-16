var colorMenu = new Ext.menu.ColorMenu({
handler: function(cm, color) {
    Ext.Msg.alert('Color picker', String.format('You picked {0}.', color));
    }
});




AnnotateMapUi = Ext.extend(Ext.Window, {
    title: 'Annotate Map',
    layout: 'fit',
    autoWidth: true,
    autoHeight: true,
    resizable: false,
    minWidth: 400,
    id: 'annoMap',
    initComponent: function () {

        //setup draw tools

        var tool;
        var drawTools = {};


        tool = new GeoExt.Action({
            control: new OpenLayers.Control.DrawFeature(vector, OpenLayers.Handler.Path),
            map: map,
            text: 'Draw Line',
            enableToggle: true,
            icon: '../images/silk/vector.png',
            toggleGroup: 'DrawTools',
            tooltip: 'Draw a line',
            id: 'btnDrwLine'
        });

        drawTools["line"] = tool;

        tool = new GeoExt.Action({
            control: new OpenLayers.Control.DrawFeature(vector, OpenLayers.Handler.Polygon),
            map: map,
            text: 'Draw Polygon',
            enableToggle: true,
            icon: '../images/silk/shape_handles.png',
            toggleGroup: 'DrawTools',
            tooltip: 'Draw a Polygon',
            id: 'btnDrwPoly'
        });

        drawTools["poly"] = tool;



        this.tbar = {
            xtype: 'toolbar',
            autoHeight: true,
            width: 400,
            forceLayout: true,
            items: [
                {
                    xtype: 'tbtext',
                    text: 'TOOLS'
                },
                {
                    xtype: 'tbseparator'
                },
                drawTools["line"],
                drawTools["poly"],                
               {
                    xtype: 'button',
                    text: 'Add Label',
                    enableToggle: true,
                    icon: '../images/silk/tag_purple.png',
                    toggleGroup: 'DrawTools',
                    tooltip: 'Add Label',
                    id: 'btnaddlabel'
                },
                {
                    xtype: 'button',
                    text: 'Colour',
                    icon: '../images/silk/color_wheel.png',
                    tooltip: 'Pick a colour....',
                    id: 'btnColour',
                    menu: colorMenu
                }
            ]
        };
        AnnotateMapUi.superclass.initComponent.call(this);
    }
});

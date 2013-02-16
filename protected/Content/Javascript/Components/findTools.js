FindToolUi = Ext.extend(Ext.Window, {
    title: 'Find Tool',
    height: 59,
    resizable: false,
    width: 200,
    layout: 'fit',
    initComponent: function () {
        this.tbar = {
            xtype: 'toolbar',
            autoHeight: true,
            id: 'tlbFindTool',
            items: [
                {
                    xtype: 'button',
                    tooltip: 'Placename',
                    icon: '../images/silk/map_magnify.png',
                    id: 'btnFindPlacename'
                },
                {
                    xtype: 'button',
                    tooltip: 'Point',
                    icon: '../images/silk/bullet_red.png'
                },
                {
                    xtype: 'button',
                    tooltip: 'Area around point',
                    icon: '../images/geosilk/buffer.png',
                    id: 'btnBuffer'
                },
                {
                    xtype: 'button',
                    tooltip: 'Draw rectangle',
                    icon: '../images/geosilk/shape_square_red.png',
                    id: 'btnFindRectangle'
                },
                {
                    xtype: 'button',
                    tooltip: 'Draw irregular area',
                    icon: '../images/geosilk/line_red.png',
                    id: 'btnFindDrawArea'
                },
                {
                    xtype: 'button',
                    tooltip: 'Find by geography',
                    icon: '../images/silk/world.png',
                    id: 'btnFindGeography'
                }
            ]
        };
        FindToolUi.superclass.initComponent.call(this);
    }
});

GeoPortal.Toolbars.MapStatus = Ext.extend(Ext.Toolbar, {
    height: 25,
    id: 'mapStatus',
    disabled: false,
    initComponent: function () {
        this.items = [
             {
                 xtype: 'tbtext',
                 width: 200,
                 id: 'txtStatusMessage',
                 style: 'color:  #9000A1; font-size: 12px; font-weight: bolder;'
             },
            {
                xtype: 'tbseparator'
            },
            {
                xtype: 'tbtext',
                text: 'Current Location:'
            },
            {
                xtype: 'container',
                width: 125,
                id: 'txtSBLatLon',
                style: 'color:  #9000A1; font-size: 12px; font-weight: bolder;'
            },
            {
                xtype: 'tbseparator'
            },
            {
                xtype: 'tbtext',
                text: 'Current Zoom:'
            },
            {
                xtype: 'tbtext',
                id: 'txtSBCurrentZoom',
                style: 'color:  #9000A1; font-size: 12px; font-weight: bolder;'
            },
            {
                xtype: 'tbseparator'
            },
             {
                 xtype: 'tbbutton',
                 id: 'minSS',
                 text: 'Spatial Search Results',
                 tooltip: "Show Search Results",
                 hidden: true,
                 handler: function () {
                     Ext.getCmp('spatResWin').show(); 
                 
                 var btn = this;
                 btn.hide(); }
             },
            {
                xtype: 'tbfill'
            },
            {
                xtype: 'tbseparator'
            },
            {
                xtype: 'tbtext',
                text: 'User:'
            },
            {
                xtype: 'tbtext',
                text: 'Guest',
                id: 'txtSBUser'
            }
        ];
        GeoPortal.Toolbars.MapStatus.superclass.initComponent.call(this);
    }
});

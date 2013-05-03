Ext.ns('GeoPortal.Windows');
GeoPortal.Windows.MyAccount = Ext.extend(Ext.Window, {
    title: 'My Account',
    id: 'myACWin',
    layout: 'auto',
    width: 800,
    height: Ext.getBody().getViewSize().height * 0.8,
//    autoScroll: true,
    maximizable: true,
    modal: true,
    initComponent: function () {
            this.items = [
            {
                xtype: 'tabpanel',
                activeTab: 0,
                title: '',
                items: [
                    new GeoPortal.Forms.UserDetails(),
                    {
                        xtype: 'panel',
                        title: 'My Messages'
                    },
                    new GeoPortal.Forms.MyMaps()
                    ,
                    {
                        xtype: 'panel',
                        title: 'My Searches'
                    },
                    {
                        xtype: 'panel',
                        title: 'My Uploads'
                    }
                ]
            }
        ];
        GeoPortal.Windows.MyAccount.superclass.initComponent.call(this);
    }
});
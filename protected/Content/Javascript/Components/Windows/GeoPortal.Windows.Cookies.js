//Ext.ns('Geoportal.Windows');
GeoPortal.Windows.Cookies = Ext.extend(Ext.Window, {
    id: 'cookieWindow',
    title: 'WISERD DataPortal Cookie Policy',
    width: Ext.getBody().getViewSize().width * 0.5,
    height: Ext.getBody().getViewSize().height * 0.8,
    resizable: true,
    modal: true,
    autoScroll: 'true',
//    draggable: true,
    initComponent: function () {
        this.items = [
            {
                xtype: 'panel',
                padding : 20,
//                title: 'Introduction',
                id: 'panelCookie',
                autoScroll: 'true',
                autoLoad: 'protected/Content/html/cookies.htm'
            }
        ];
        GeoPortal.Windows.Cookies.superclass.initComponent.call(this);
    }

});
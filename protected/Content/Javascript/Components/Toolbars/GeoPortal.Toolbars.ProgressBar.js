GeoPortal.Toolbars.ProgressBar = Ext.extend(Ext.Window, {
    title: 'Searching....',
    width: 300,
    closable: false,
    bodyStyle: 'padding: 5px;',
    initComponent: function () {
        this.items = [
            {
                xtype: 'progress',
                id: 'LoadProgress'
            }
        ];
        GeoPortal.Toolbars.ProgressBar.superclass.initComponent.call(this);
    }
});
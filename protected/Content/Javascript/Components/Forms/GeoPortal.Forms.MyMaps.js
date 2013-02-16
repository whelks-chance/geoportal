GeoPortal.Forms.MyMaps = Ext.extend(Ext.Panel, {
    title: 'My Maps',
    layout: 'border',
    width: 795,
    height: 700,
    initComponent: function () {        
        this.items = [
            {
                xtype: 'dataview',
                itemSelector: 'div',
                tpl: '<tpl for="."><div>Template</div></tpl>',
                region: 'center'
            },
            {
                xtype: 'form',
                title: 'Details',
                region: 'east',
                width: 200,
                labelWidth: 50
            }
        ];
        this.bbar = {
            xtype: 'toolbar',
            items: [ '->', {text:'Status'}]
        };
        GeoPortal.Forms.MyMaps.superclass.initComponent.call(this);
    }
});

/**
 * Created with JetBrains PhpStorm.
 * User: wiserd
 * Date: 16/08/13
 * Time: 10:13
 * To change this template use File | Settings | File Templates.
 */
Ext.ns('GeoPortal.Forms.DataEntry');
GeoPortal.Forms.DataEntry.DBlines = Ext.extend(Ext.form.FormPanel, {

    title: 'PostgreSQL',
    autoScroll: true,
    id: 'frmEntrySQL',
    headerAsText: false,
    padding: 5,
    labelWidth: 75,
    bodyCssClass: 'text-align:center',

    initComponent : function () {

        this.items = [
            {
                fieldLabel      : 'psqlarea',
                id              : 'psqlarea',
                name            : 'psql',
                xtype           : 'textarea',
                autoScroll      : true,
                height          : 200,
                anchor: '100%'
            }
        ];
        GeoPortal.Forms.DataEntry.DBlines.superclass.initComponent.call(this);

    }
});
/**
 * Created with JetBrains PhpStorm.
 * User: wiserd
 * Date: 04/11/13
 * Time: 17:02
 * To change this template use File | Settings | File Templates.
 */

GeoPortal.Forms.ONSAPI = Ext.extend(Ext.form.FormPanel, {

    title: 'ONS API Data',
    layout: 'fit',
//    bodyStyle: 'padding: 5px',
//    hideBorders: true,
    initComponent: function () {

        var heirachyOptions = [{"name" : "2011PCONH", "id" : "2011PCONH:QS418EW"}, {"name" : "2011WARDH", "id" : "2011WARDH:QS418EW"}];

        var heirachyStore = new Ext.data.JsonStore ({
            fields: [
                {name: 'name', mapping: 'name'},
                {name: 'id',  mapping: 'id'}],
            data : heirachyOptions
//            root : "heirachies"
        });

//        Ext.Ajax.request({
//            url: remoteSourceURL,
//            method : 'POST',
//            success: function(resp) {
//                var responseData = Ext.decode(resp.responseText);
//            },
//            failure: function(resp) {
//                console.log('failure!');
//            }
//        });

        this.items = [
            {

                xtype: 'fieldset',
                id: 'fieldsource',
                title: 'Select Remote Data Source',
                items: [
                    {

                        // The combo box which allows selection of a remote API from the list.
                        // List data retrieved from RemoteData/getRemoteSources

                        xtype: 'combo',
                        id: 'cmboHeirachy',
                        anchor: '100%',
                        fieldLabel: 'Select Heirachy Root',
                        name: 'Heirachy',
                        editable: false,
                        triggerAction: 'all',
                        displayField: 'name',
                        hiddenName: 'hiddenURL',
                        valueField: 'name',
                        listeners: {
                            'select': function(t){
                                var cmboHeirachy = Ext.getCmp('cmboHeirachy');
//                                apiName = cmbSource.getValue();
                            },
                            afterrender: function(combo) {
                                var recordSelected = combo.getStore().getAt(0);
                                combo.setValue(recordSelected.get('field1'));
                            }
                        },
                        mode: 'local',
                        store : heirachyStore
                    },
                    {
                        xtype: 'container',
                        layout: 'column',
                        items: [
                            {

//
                                xtype: 'container',
                                columnWidth: 0.5,
                                layout: 'form',
//                                style: 'margin:10px;',
                                items: [
                                ]
                            },
                            {

//
                                xtype: 'container',
                                columnWidth: 0.1,
                                layout: 'form',
//                                style: 'margin:10px;',
                                items: [
                                ]
                            },
                            {

//
                                xtype: 'container',
                                columnWidth: 0.4,
                                layout: 'form',
//                                style: 'margin:10px;',
                                items: [
                                ]
                            }
                        ]
                    }
                ]
            }
        ];

        GeoPortal.Forms.ONSAPI.superclass.initComponent.call(this);

    }
});
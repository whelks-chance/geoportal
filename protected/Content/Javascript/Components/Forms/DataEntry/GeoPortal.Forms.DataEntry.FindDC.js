/**
 * Created with JetBrains PhpStorm.
 * User: wiserd
 * Date: 14/08/13
 * Time: 16:56
 * To change this template use File | Settings | File Templates.
 */
GeoPortal.Forms.DataEntry.FindDC = Ext.extend(Ext.form.FormPanel, {
//    width: 300,
    id: 'frmFindDC',
//    height: 140,
    padding: 5,
    dataset: null,
    initComponent: function () {
        var datasetID = "";

        var dcIdStore = new Ext.data.JsonStore ({
            fields: [
                {name: 'name', mapping: 'name'},
                {name: 'id',  mapping: 'id'}],
            id: "dcIdStore",
            root : "DublinCoreId"
        });

        Ext.Ajax.request({
            url: getDCids,
            method : 'POST',
            success: function(resp) {
                var responseData = Ext.decode(resp.responseText);
                dcIdStore.loadData(responseData);
            },
            failure: function(resp) {
                console.log('failure!');
            }
        });

        this.bbar = {
            xtype: 'toolbar',
            items : [
                {
                    xtype: 'tbfill'
                },
                {
                    xtype: 'button',
                    text: 'Load',
                    id: 'loadDCinfobtn',
                    handler: function(){

                        var wiserdID = datasetID.substr(2);

                        Ext.getCmp("frmEntryDC").getForm().load(
                            {
                                url: DCmetaURL,
                                waitMsg: 'Loading.......',
                                method: 'POST',
                                params: {
                                    SID: wiserdID
                                }
                            }
                        );
                    }
                }
            ]
        };
        this.items = [
            {
                // Drop down for the dataset
                // Lets user select a survey, sends the id to RemoteData/getRemoteDataset
                // Returns the regions available for the data

                xtype: 'combo',
                tpl: '<tpl for="."><div ext:qtip="{name}" class="x-combo-list-item">{name}</div></tpl>',
                id: 'dcId',
                anchor: '100%',
                fieldLabel: 'Select Dublin Core ID',
                name: 'Dataset',
                triggerAction: 'all',
                displayField: 'name',
                hiddenName: 'hiddenDCid',
                valueField: 'id',
                listeners: {
                    'select': function(t){
                        var cmboDataset = Ext.getCmp('dcId');
                        datasetID = cmboDataset.getValue();
                    }
                },
                mode: 'local',
                store : dcIdStore
            }
        ];
        GeoPortal.Forms.DataEntry.FindDC.superclass.initComponent.call(this);
    }
});

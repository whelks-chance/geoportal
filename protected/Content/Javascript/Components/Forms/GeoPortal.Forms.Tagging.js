/**
 * Created with JetBrains PhpStorm.
 * User: wiserd
 * Date: 17/07/13
 * Time: 11:54
 * To change this template use File | Settings | File Templates.
 */
GeoPortal.Forms.Tagging = Ext.extend(Ext.form.FormPanel, {
//    width: 800,
//    height: 800,
    loadMask: true,
    loadMsg: 'loading.....',
    waitMsg: 'Loading........',
    id: 'frmTagging',
    title: 'Tagging',
    bodyStyle: 'padding: 5px',
    autoScroll: true,
    hideBorders: true,
    wiserdID: '',
    initComponent: function () {
        var docTypes = '[{"docType":"Qualitative Data","docTypeId":"qual"},{"docType":"Grey Data","docTypeId":"grey"}]';

        var docTypeStore = new Ext.data.JsonStore ({
            fields: [
                {name: 'docType', mapping: 'docType'},
                {name: 'docTypeId',  mapping: 'docTypeId'}],
            id: "docTypeStore"
//            root : "surveyData"
        });
        docTypeStore.loadData(Ext.decode(docTypes));

        Ext.Ajax.request({
            url: getTimeTicks,
            method : 'POST',
            success: function(resp) {
                this.wiserdID = resp.responseText;
            },
            failure: function(resp) {
                console.log('failure!');
            }
        });

        this.bbar = {
            xtype: 'toolbar',
            height: 26,
            items: [
                {
                    xtype: 'tbfill'
                },
                {
                    xtype: 'button',
                    text: 'Get Tags',
                    bodyStyle: 'float: right',
                    icon: './images/silk/tag_blue.png',
                    handler: function () {
                        var taggingPanel = Ext.getCmp('frmTagging');
                        taggingPanel.getForm().submit({
                            url: getTaggingTags,
                            waitMsg: 'Getting Tags.....',
                            success: function (form, action) {
                                Ext.Msg.alert("Sucess!", action.result.message);
                            },
                            failure: function (form, action) {
                                Ext.Msg.alert(action.result.message);
                            }
                        })
                    }
                },
                {
                    xtype: 'button',
                    text: 'Create Metadata',
                    bodyStyle: 'float: right',
                    icon: './images/silk/application_edit.png',
                    handler: function () {
                        var taggingPanel = Ext.getCmp('frmTagging');
                        taggingPanel.getForm().submit({
                            url: createTaggingMetadata,
                            waitMsg: 'Creating Metadata.....',
                            success: function (form, action) {
                                console.log(action);
                                Ext.Msg.alert("Success!", action.response.responseText);
                            },
                            failure: function (form, action) {
                                console.log("nope");
                                Ext.Msg.alert(action.result.message);
                            }
                        })
                    }
                }
            ]
        }

        this.items = [
            {
                id: 'logoImage',
                html: '<img src="./images/Logos/base-image-W.png" border="5">'
            },
            {
                xtype: 'fieldset',
                layout: 'form',
                items: [

                    {
                        xtype: 'textfield',
                        id: 'wiserdIdField',
                        anchor: '100%',
                        value: 'WISID_' + this.wiserdID,
                        fieldLabel: 'WISERD ID',
                        name: 'wiserdid'
                    },
                    {
                        xtype: 'combo',
                        tpl: '<tpl for="."><div ext:qtip="{docType}" class="x-combo-list-item">{docType}</div></tpl>',
                        id: 'cmboDocType',
                        anchor: '100%',
                        fieldLabel: 'Document Type',
                        name: 'DocType',
                        editable : false,
                        triggerAction: 'all',
                        displayField: 'docType',
                        hiddenName: 'docTypeId',
                        valueField: 'docTypeId',
                        mode: 'local',
                        store : docTypeStore
                    },
                    {
                        xtype: 'textfield',
                        id: 'documentTitleField',
                        anchor: '100%',
//                        value: this.geographyRegion,
                        fieldLabel: 'Document Title',
                        name: 'title'
                    },
                    {
                        xtype: 'datefield',
                        id: 'dteCreation',
//                        emptyText: '',
                        format: 'Y/m/d',
                        anchor: '50%',
//                        columnWidth: 0.5,
                        name: 'dateCreation',
                        fieldLabel : 'Document Creation Date'
                    },
                    {
                        xtype: 'textfield',
                        id: 'metadataCreationDate',
//                        emptyText: '',
                        anchor: '100%',
//                        value: this.geographyRegion,
                        fieldLabel: 'MetaData Creation Date',
                        name: 'metadataDate'
                    },
                    {
                        xtype: 'textarea',
                        id: 'txtTagText',
                        fieldLabel: 'Text to Tag',
                        name: 'tagText',
                        height: 250,
                        anchor: '100%'
                    },
                    {
                        xtype: 'textfield',
                        id: 'noPages',
                        emptyText: '',
                        anchor: '100%',
//                        value: this.geographyRegion,
                        fieldLabel: 'Number Of Pages in Document',
                        name: 'noPages'
                    }
                ]
            },
            {
                id: 'calaisImage',
                html: '<img src="./images/Logos/calais_logo.jpg" border="5">'
            }
        ];
        GeoPortal.Forms.Tagging.superclass.initComponent.call(this);
    }
});
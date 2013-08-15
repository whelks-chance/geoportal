/**
 * Created with JetBrains PhpStorm.
 * User: wiserd
 * Date: 13/08/13
 * Time: 15:23
 * To change this template use File | Settings | File Templates.
 */
Ext.ns('GeoPortal.Forms.DataEntry');
GeoPortal.Forms.DataEntry.DublinCore = Ext.extend(Ext.form.FormPanel, {

        title: 'Dublin Core',
        autoScroll: true,
        id: 'frmEntryDC',
        headerAsText: false,
        padding: 5,
        labelWidth: 75,
        SID: null,
        bodyCssClass: 'text-align:center',

        initComponent : function () {

            this.items = [
                {
                    xtype: 'fieldset',
                    title: 'Dublin Core',
                    defaults: {labelStyle: 'font-weight:bold;' },
                    collapsible: true,
                    items: [
                        {
                            xtype: 'textfield',
                            fieldLabel: 'WISERD ID',
                            anchor: '97%',
                            name: 'dcWiserdID'
                        },
                        {
                            xtype: 'textarea',
                            anchor: '97%',
                            fieldLabel: 'Title',
                            name: 'dcTitle',
                            autoHeight: true
                        },
                        {
                            xtype: 'textarea',
                            anchor: '97%',
                            fieldLabel: 'Subject',
                            name: 'dcSubject',
                            autoHeight: true
                        },
                        {
                            xtype: 'textarea',
                            anchor: '97%',
                            fieldLabel: 'Description',
                            name: 'dcDescription',
                            autoHeight: true
                        },
                        {
                            xtype: 'container',
                            layout: 'column',
                            cls: '',
                            anchor: '97%',
                            autoWidth: true,
                            items: [
                                {
                                    xtype: 'container',
                                    columnWidth: 0.5,
                                    layout: 'form',
                                    defaults: {labelStyle: 'font-weight:bold;' },
                                    labelWidth: 75,
                                    items: [
                                        {
                                            xtype: 'textfield',
                                            name: 'dcDate',
                                            anchor: '94%',
                                            fieldLabel: 'Date'
                                        }
                                    ]
                                },
                                {
                                    xtype: 'container',
                                    columnWidth: 0.5,
                                    layout: 'form'
                                }
                            ]
                        },
                        {
                            xtype: 'container',
                            layout: 'column',
                            anchor: '97%',
                            autoWidth: true,
                            items: [
                                {
                                    xtype: 'container',
                                    columnWidth: 0.5,
                                    layout: 'form',
                                    defaults: {labelStyle: 'font-weight:bold;' },
                                    labelWidth: 75,
                                    items: [
                                        {
                                            xtype: 'textfield',
                                            name: 'dcType',
                                            anchor: '94%',
                                            fieldLabel: 'Type'
                                        }
                                    ]
                                },
                                {
                                    xtype: 'container',
                                    columnWidth: 0.5,
                                    defaults: {labelStyle: 'font-weight:bold;' },
                                    layout: 'form',
                                    items: [
                                        {
                                            xtype: 'textfield',
                                            fieldLabel: 'Format',
                                            anchor: '94%',
                                            name: 'dcFormat'
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            xtype: 'textarea',
                            anchor: '97%',
                            fieldLabel: 'Creator',
                            name: 'dcCreator',
                            autoHeight: true
                        },
                        {
                            xtype: 'textarea',
                            anchor: '97%',
                            fieldLabel: 'Publisher',
                            name: 'dcPublisher',
                            autoHeight: true
                        },
                        {
                            xtype: 'textarea',
                            anchor: '97%',
                            fieldLabel: 'Contributor',
                            name: 'dcContributor',
                            autoHeight: true
                        },
                        {
                            xtype: 'textarea',
                            anchor: '97%',
                            fieldLabel: 'Source',
                            name: 'dcSource',
                            autoHeight: true
                        },
                        {
                            xtype: 'textarea',
                            anchor: '97%',
                            fieldLabel: 'Rights',
                            name: 'dcRights',
                            autoHeight: true
                        },
                        {
                            xtype: 'textarea',
                            anchor: '97%',
                            fieldLabel: 'Coverage',
                            name: 'dcCoverage',
                            autoHeight: true
                        },
                        {
                            xtype: 'container',
                            layout: 'column',
                            anchor: '97%',
                            items: [
                                {
                                    xtype: 'container',
                                    layout: 'form'
                                },
                                {
                                    xtype: 'container'
                                }
                            ]
                        },
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Language',
                            anchor: '97%',
                            name: 'dcLanguage'
                        },
                        {
                            xtype: 'textarea',
                            anchor: '97%',
                            fieldLabel: 'Relation',
                            name: 'dcRelation',
                            autoHeight: true
                        }
                    ]
                }
            ];
            this.tbar = {
                xtype: 'toolbar',
                items: [
                    {
                        xtype: 'button',
                        id: 'btnDCLoad',
                        icon: 'images/silk/application_get.png',
                        text: 'Load',
                        type: 'reset',
                        handler : this.FormLoad,
                        scope : this
                    },
                    {
                        xtype: 'tbfill'
                    },
                    {
                        xtype: 'button',
                        id: 'btnDCInsert',
                        icon: 'images/silk/application_form_add.png',
                        text: 'Insert',
                        type: 'reset',
                        handler : this.FormInsert,
                        scope : this
                    },
                    {
                        xtype: 'button',
                        id: 'btnDCUpdate',
                        icon: 'images/silk/application_form_edit.png',
                        text: 'Update',
                        type: 'reset',
                        handler : this.FormUpdate,
                        scope : this
                    },
                    {
                        xtype: 'button',
                        id: 'btnDCDelete',
                        icon: 'images/silk/application_form_delete.png',
                        text: 'Delete',
                        type: 'reset',
                        handler : this.FormDelete,
                        scope : this
                    },
                    {
                        xtype: 'button',
                        id: 'btnDCReset',
                        icon: 'images/silk/arrow_rotate_clockwise.png',
                        text: 'Reset Form',
                        type: 'reset',
                        handler : this.FormReset,
                        scope : this
                    }
                ]
            };

            GeoPortal.Forms.DataEntry.DublinCore.superclass.initComponent.call(this);
        },
        FormLoad : function() {
            var loadDCWin = new Ext.Window({ items: [new GeoPortal.Forms.DataEntry.FindSurvey()], title: 'Load Survey', modal: true, width: 500, id: 'LoadDCWin' });
            loadDCWin.show();
        },
        FormReset : function() {
            console.log('reset ' + this.id)
            var thisPanel = Ext.getCmp(this.id);
            console.log(thisPanel);
            thisPanel.getForm().reset();
        },
        FormInsert : function() {
            console.log('reset ' + this.id)
            var thisPanel = Ext.getCmp(this.id);
            console.log(thisPanel);
            thisPanel.getForm().submit({
                url: insertDC,
                waitMsg: 'Inserting Dublic Core Data....',
                success: function (form, action) {
                    Ext.Msg.alert("Success!",action.result.message);
//                    Ext.getCmp('ChgPWWin').hide();
                },
                failure: function (form, action) {
                    Ext.Msg.alert("Error!",action.result.message);
                }
            });
        },
        FormUpdate : function() {
            console.log('reset ' + this.id)
            var thisPanel = Ext.getCmp(this.id);
            console.log(thisPanel);
            thisPanel.getForm().reset();
        },
        FormDelete : function() {
            console.log('reset ' + this.id)
            var thisPanel = Ext.getCmp(this.id);
            console.log(thisPanel);
            thisPanel.getForm().reset();
        }
    }
);

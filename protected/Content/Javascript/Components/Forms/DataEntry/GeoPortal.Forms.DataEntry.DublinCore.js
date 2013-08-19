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

            var dcFormatStore = new Ext.data.JsonStore ({
                fields: [
                    {name: 'name', mapping: 'dc_format_title'},
                    {name: 'id',  mapping: 'dcformatid'}
                ],
                id: "dcFormatTypesStore",
                root : "dcFormatTypes"
            });

            var dcLanguageStore = new Ext.data.JsonStore ({
                fields: [
                    {name: 'name', mapping: 'dc_language_title'},
                    {name: 'id',  mapping: 'dclangid'}
                ],
                id: "dcLanguageStore",
                root : "dcLangs"
            });

            var dcTypeStore = new Ext.data.JsonStore ({
                fields: [
                    {name: 'name', mapping: 'dc_type_title'},
                    {name: 'id',  mapping: 'dctypeid'}
                ],
                id: "dcTypeStore",
                root : "dcTypes"
            });

            Ext.Ajax.request({
                url: dataOptionLists,
                method : 'POST',
                params : {
                    dublincore_format: true,
                    dublincore_language: true,
                    dublincore_type: true
                },
                success: function(resp) {
                    var responseData = Ext.decode(resp.responseText);
                    dcFormatStore.loadData(responseData);
                    dcLanguageStore.loadData(responseData);
                    dcTypeStore.loadData(responseData);
                },
                failure: function(resp) {
                    console.log('failure!');
                }
            });

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
                            name: 'dcWiserdID',
                            id: 'dcWiserdIDfield'
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
                                            xtype: 'combo',
                                            forceSelection: true,
                                            editable: false,
                                            id: 'dcTypeCombo',
                                            anchor: '94%',
                                            fieldLabel: 'Type',
                                            name: 'dcType',
                                            triggerAction: 'all',
                                            displayField: 'name',
//                            hiddenName: 'hiddenVariable',
//                            valueField: 'id',
                                            mode: 'local',
                                            store : dcTypeStore
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
                                            xtype: 'combo',
                                            forceSelection: true,
                                            editable: false,
                                            id: 'dcFormatCombo',
                                            anchor: '94%',
                                            fieldLabel: 'Format',
                                            name: 'dcFormat',
                                            triggerAction: 'all',
                                            displayField: 'name',
//                            hiddenName: 'hiddenVariable',
//                            valueField: 'id',
                                            mode: 'local',
                                            store : dcFormatStore
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
                            xtype: 'combo',
                            forceSelection: true,
                            editable: false,
                            id: 'dcLanguageCombo',
                            anchor: '97%',
                            fieldLabel: 'Language',
                            name: 'dcLanguage',
                            triggerAction: 'all',
                            displayField: 'name',
//                            hiddenName: 'hiddenVariable',
//                            valueField: 'id',
                            mode: 'local',
                            store : dcLanguageStore
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
                        id: 'btnNewSurvey',
                        icon: 'images/silk/application_get.png',
                        text: 'New Survey',
                        type: 'reset',
                        handler : this.NewSurvey,
                        scope : this
                    },
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
        NewSurvey : function() {
            Ext.MessageBox.prompt('Survey', 'Please enter a new Survey ID. Appropriate id tags will be automatically added.', function(btn, text){
                if (btn == 'ok'){

                    var wid = 'wisid_' + text;
                    var sid = 'sid_' + text;
                    var qid = 'qid_' + text;
                    var resid = 'resid_qid_' + text;

                    Ext.getCmp('dcWiserdIDfield').setValue(wid);
                    Ext.getCmp('surveyIDfield').setValue(sid);
                    Ext.getCmp('QuestionSurveyID').setValue(sid);
                    Ext.getCmp('QuestionIdField').setValue(qid);
                    Ext.getCmp('resQuestionIDfield').setValue(qid);
                    Ext.getCmp('responseIdField').setValue(resid);

                    var breadcrumb = Ext.getCmp('breadcrumb');
                    breadcrumb.updateBreadcrumb(wid, sid, qid, resid);

                }
            });
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

/**
 * Created with JetBrains PhpStorm.
 * User: wiserd
 * Date: 13/08/13
 * Time: 15:23
 * To change this template use File | Settings | File Templates.
 */
Ext.ns('GeoPortal.Forms.DataEntry');
GeoPortal.Forms.DataEntry.Response = Ext.extend(Ext.form.FormPanel, {

        title: 'Response',
        autoScroll: true,
        id: 'frmEntryResponse',
        headerAsText: false,
        padding: 5,
        labelWidth: 75,
        SID: null,
        bodyCssClass: 'text-align:center',


        initComponent : function () {

            var responseTypeStore = new Ext.data.JsonStore ({
                fields: [
                    {name: 'name', mapping: 'response_name'},
                    {name: 'id',  mapping: 'responseid'}
                ],
                url: dataOptionLists,
                baseParams : {
                    response_type: true
                },
                id: "responseTypeStore",
                root : "responseTypes"
            });
            responseTypeStore.on('load', function(store, recs, opt){
                var restypcmbo = Ext.getCmp('responseTypeCombo');
                restypcmbo.setValue(responseTypeStore.getAt(0).get('name'));

                this.doLayout();
            }, this);
            responseTypeStore.load();

            this.on('actioncomplete', function (form,action) {
                if (action.type=='load') {
                    var responseID = Ext.getCmp('responseIdField').getValue();

                    var breadcrumb = Ext.getCmp('breadcrumb');
                    breadcrumb.updateBreadcrumb(null, null, null, responseID);

                    Ext.getCmp('responseTableRIdField').setValue(responseID);
                }
            });

            this.tbar = {
                xtype: 'toolbar',
                items: [
                    {
                        xtype: 'button',
                        id: 'btnResponseLoad',
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
                        id: 'btnRsave',
                        icon: 'images/silk/database_edit.png',
                        text: 'Save',
                        type: 'reset',
                        handler : this.FormSave,
                        scope : this
                    },
                    {
                        xtype: 'button',
                        id: 'btnResponseDelete',
                        icon: 'images/silk/application_form_delete.png',
                        text: 'Delete',
                        type: 'reset',
                        handler : this.FormDelete,
                        scope : this
                    },
                    {
                        xtype: 'button',
                        id: 'btnResponseReset',
                        icon: 'images/silk/arrow_rotate_clockwise.png',
                        text: 'Reset Form',
                        type: 'reset',
                        handler : this.FormReset,
                        scope : this
                    }
                ]
            };

            this.items = [
                {
                    xtype: 'fieldset',
                    title: 'General',
                    defaults: { labelStyle: 'font-weight:bold;' },
//                    collapsible: true,
                    items: [
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Question ID',
                            anchor: '97%',
                            name: 'questionID',
                            allowBlank:false,
                            readOnly: true,
                            id: 'resQuestionIDfield'
                        },
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Response ID',
                            anchor: '97%',
                            name: 'responseID',
                            id: 'responseIdField'
                        },
//                        {
//                            xtype: 'textfield',
//                            fieldLabel: 'Type',
//                            anchor: '97%',
//                            name: 'responseType'
//                        },
                        {
                            xtype: 'combo',
                            forceSelection: true,
                            editable: false,
                            id: 'responseTypeCombo',
                            anchor: '97%',
                            fieldLabel: 'Type',
                            name: 'responseType',
                            triggerAction: 'all',
                            displayField: 'name',
//                            hiddenName: 'hiddenVariable',
//                            valueField: 'id',
                            mode: 'local',
                            store : responseTypeStore
                        },
                        {
                            xtype: 'textarea',
                            anchor: '97%',
                            fieldLabel: 'Response Text',
                            name: 'responseText'
                        },
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Response Table ID',
                            anchor: '97%',
                            name: 'responseTableID'
                        },
                        {
                            xtype: 'textarea',
                            anchor: '97%',
                            fieldLabel: 'Checks',
                            name: 'responseChecks'
                        },
                        {
                            xtype: 'textarea',
                            anchor: '97%',
                            fieldLabel: 'Computed Variables',
                            name: 'responseVariables'
                        }
                    ]
                },
                {
                    xtype: 'fieldset',
                    title: 'Routing Information',
                    defaults: { labelStyle: 'font-weight:bold;' },
//                    collapsed: true,
//                    collapsible: true,
                    items: [
                        {
                            xtype: 'textarea',
                            anchor: '97%',
                            fieldLabel: 'Routing Notes',
                            name: 'responseRouting'
                        }
                    ]
                }
            ];

            GeoPortal.Forms.DataEntry.Response.superclass.initComponent.call(this);
        },
        FormLoad : function() {
            var surveyField = Ext.getCmp('QuestionSurveyID');
            var surveyID = surveyField.getValue();
//            console.log('surveyID : ' + surveyID);

            if (surveyID == "") {
                alert('No Survey ID defined, please load a previous survey');
            } else {
                var loadFQWin = new Ext.Window({ items:[new GeoPortal.Forms.DataEntry.FindQuestions({SID:surveyID})], title:'Load Question', modal:true, width:500, id:'LoadQuestionWin' });
                loadFQWin.show();
            }
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
                url: insertResponse,
                waitMsg: 'Inserting Response Data....',
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
        },
        FormSave : function() {

            var rid = Ext.getCmp('responseIdField').getValue();
            var qid = Ext.getCmp('resQuestionIDfield').getValue();

            Ext.Ajax.request({
                url: checkRecordExists,
                scope: this,
                method : 'POST',
                params : {
                    recordType: "survey_response",
                    recordID: rid
                },
                success: function(resp) {
                    var responseData = Ext.decode(resp.responseText);
                    var ridProjectID = responseData.projectid;
                    console.log(ridProjectID);

                    if (responseData.exists == true) {
                        console.log('ok ' + responseData);
                        Ext.MessageBox.confirm('Status', 'Response RID is already in use, overwrite?', function (btn, text) {

                            if(btn == 'yes') {
                                Ext.getCmp('frmEntryQuestion').getForm().submit({
                                    scope: this,
                                    url: insertResponse,
                                    params : {
                                        update : true,
                                        projectID : ridProjectID
                                    },
                                    waitMsg: 'Inserting Response Data....',
                                    success: function (form, action) {
                                        Ext.MessageBox.alert("Success", "Response data overwritten");
                                    },
                                    failure: function (form, action) {
                                        Ext.MessageBox.alert("Failure", action.result.message);
                                    }
                                });
                            } else {
                                Ext.MessageBox.alert("No, don't overwrite response");
                            }
                        });
                    } else {
                        if(qid == "" || rid == "") {
                            Ext.MessageBox.alert('Status', 'Please set a Question ID by loading a question and ensure a Response ID is entered.');
                        } else {
                            var thisPanel = Ext.getCmp(this.id);
                            if(thisPanel.getForm().isValid()) {
                                thisPanel.getForm().submit({
                                    url: insertResponse,
                                    waitMsg: 'Inserting Response Data....',
                                    success: function (form, action) {
                                        Ext.Msg.alert("Success!",action.result.message);
                                    },
                                    failure: function (form, action) {
                                        Ext.Msg.alert("Error!",action.result.message);
                                    }
                                });
                            } else {
                                Ext.MessageBox.alert('Status', 'There is an invalid entry in the Response form.');
                            }
                        }
                    }
                },
                failure: function(resp) {

                }
            });
        }
    }
);

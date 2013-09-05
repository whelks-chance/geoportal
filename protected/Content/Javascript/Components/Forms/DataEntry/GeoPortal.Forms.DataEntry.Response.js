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

            this.on('actioncomplete', function (form,action) {
                if (action.type=='load') {
                    var responseID = Ext.getCmp('responseIdField').getValue();

                    var breadcrumb = Ext.getCmp('breadcrumb');
                    breadcrumb.updateBreadcrumb(null, null, null, responseID);
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
                        id: 'btnResponseInsert',
                        icon: 'images/silk/application_form_add.png',
                        text: 'Insert',
                        type: 'reset',
                        handler : this.FormInsert,
                        scope : this
                    },
                    {
                        xtype: 'button',
                        id: 'btnResponseUpdate',
                        icon: 'images/silk/application_form_edit.png',
                        text: 'Update',
                        type: 'reset',
                        handler : this.FormUpdate,
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
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Type',
                            anchor: '97%',
                            name: 'responseType'
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
            console.log('surveyID : ' + surveyID);

            if (surveyID == "") {
                alert('No Survey ID defined, please load a previous survey');
            } else {
                var loadFQWin = new Ext.Window({ items:[new GeoPortal.Forms.DataEntry.FindQuestions({SID:surveyID})], title:'Load Question', modal:true, width:500, id:'LoadDCWin' });
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
        }
    }
);

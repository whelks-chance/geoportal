/**
 * Created with JetBrains PhpStorm.
 * User: wiserd
 * Date: 13/08/13
 * Time: 15:23
 * To change this template use File | Settings | File Templates.
 */
Ext.ns('GeoPortal.Forms.DataEntry');
GeoPortal.Forms.DataEntry.Questions = Ext.extend(Ext.form.FormPanel, {

        title: 'Questions',
        autoScroll: true,
        id: 'frmEntryQuestion',
        headerAsText: false,
        padding: 5,
        labelWidth: 75,
        SID: null,
        bodyCssClass: 'text-align:center',

        initComponent : function () {

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

            this.items = [
                {
                    xtype: 'fieldset',
                    title: 'General',
                    collapsible: true,
                    defaults: { labelStyle: 'font-weight:bold;' },
                    items: [
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Survey ID',
                            anchor: '97%',
                            name: 'QuestionSurveyID',
                            id: 'QuestionSurveyID'
                        },
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Question ID',
                            anchor: '97%',
                            name: 'QuestionID'
                        },
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Question No',
                            anchor: '97%',
                            name: 'QuestionNumber'
                        },
                        {
                            xtype: 'textarea',
                            anchor: '97%',
                            fieldLabel: 'Question Text',
                            name: 'QuestionText'
                        },
                        {
                            xtype: 'textarea',
                            anchor: '97%',
                            fieldLabel: 'Notes/ Prompts',
                            name: 'QuestionNotesPrompts'
                        },
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Variable ID',
                            anchor: '97%',
                            name: 'QuestionVariable'
                        }
                    ]
                },
                {
                    xtype: 'fieldset',
                    title: 'WISERD Thematic Tagging',
                    collapsible: true,
                    defaults: { labelStyle: 'font-weight:bold;' },
                    items: [
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Groups',
                            anchor: '97%',
                            name: 'QuestionThematicGroups'
                        },
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Sub Themes',
                            anchor: '97%',
                            name: 'QuestionThematicTags'
                        }
                    ]
                },
                {
                    xtype: 'fieldset',
                    title: 'Routing Information',
                    collapsed: true,
                    collapsible: true,
                    defaults: { labelStyle: 'font-weight:bold;' },
                    items: [
                        {
                            xtype: 'textfield',
                            fieldLabel: ' Type',
                            anchor: '97%',
                            name: 'QuestionType'
                        },
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Follows QID',
                            anchor: '97%',
                            name: 'QuestionLinkedFrom'
                        },
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Sub Of',
                            anchor: '97%',
                            name: 'QuestionSubOf'
                        }
                    ]
                }
            ]

            GeoPortal.Forms.DataEntry.Questions.superclass.initComponent.call(this);
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

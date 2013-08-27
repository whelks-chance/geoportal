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

            var thematicGroupStore = new Ext.data.JsonStore ({
                fields: [
                    {name: 'name', mapping: 'grouptitle'},
                    {name: 'id',  mapping: 'tgroupid'}
                ],
                id: "thematicGroupStore",
                root : "thematic_groups"
            });
            var thematicTagStore = new Ext.data.JsonStore ({
                fields: [
                    {name: 'name', mapping: 'tag_text'},
                    {name: 'id',  mapping: 'tagid'}
                ],
                id: "thematicTagStore",
                root : "group_tags"
            });
            var questionTypeStore = new Ext.data.JsonStore ({
                fields: [
                    {name: 'name', mapping: 'q_type_text'},
                    {name: 'id',  mapping: 'q_typeid'}
                ],
                id: "questionTypeStore",
                root : "questionTypes"
            });

            Ext.Ajax.request({
                url: dataOptionLists,
                method : 'POST',
                params : {
                    thematic_groups: true,
                    group_tags: true,
                    q_type: true
                },
                success: function(resp) {
                    var responseData = Ext.decode(resp.responseText);
                    thematicGroupStore.loadData(responseData);
                    thematicTagStore.loadData(responseData);
                    questionTypeStore.loadData(responseData);

                },
                failure: function(resp) {
                    console.log('failure!');
                }
            });

            this.tbar = {
                xtype: 'toolbar',
                items: [
                    {
                        xtype: 'button',
                        id: 'btnQuestionLoad',
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
                        id: 'btnQuestionInsert',
                        icon: 'images/silk/application_form_add.png',
                        text: 'Insert',
                        type: 'reset',
                        handler : this.FormInsert,
                        scope : this
                    },
                    {
                        xtype: 'button',
                        id: 'btnQuestionUpdate',
                        icon: 'images/silk/application_form_edit.png',
                        text: 'Update',
                        type: 'reset',
                        handler : this.FormUpdate,
                        scope : this
                    },
                    {
                        xtype: 'button',
                        id: 'btnQuestionDelete',
                        icon: 'images/silk/application_form_delete.png',
                        text: 'Delete',
                        type: 'reset',
                        handler : this.FormDelete,
                        scope : this
                    },
                    {
                        xtype: 'button',
                        id: 'btnQuestionReset',
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
                            allowBlank:false,
                            anchor: '97%',
                            name: 'QuestionSurveyID',
                            id: 'QuestionSurveyID'
                        },
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Question ID',
                            allowBlank:false,
                            anchor: '97%',
                            name: 'QuestionID',
                            id: 'QuestionIdField'
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
                        },
                        {
                            xtype: 'textfield',
                            fieldLabel: 'q_text',
                            anchor: '97%',
                            name: 'q_text'
                        }
                    ]
                },
                {
                    xtype: 'fieldset',
                    title: 'WISERD Thematic Tagging',
                    collapsible: true,
                    defaults: { labelStyle: 'font-weight:bold;' },
                    items: [
//                        {
//                            xtype: 'textfield',
//                            fieldLabel: 'Groups',
//                            anchor: '97%',
//                            name: 'QuestionThematicGroups'
//                        },
                        {
                            xtype: 'combo',
                            forceSelection: true,
                            editable: false,
                            id: 'thematicGroupCombo',
                            anchor: '97%',
                            fieldLabel: 'Groups',
                            name: 'QuestionThematicGroups',
                            triggerAction: 'all',
                            displayField: 'name',
//                            hiddenName: 'hiddenVariable',
//                            valueField: 'id',
                            mode: 'local',
                            store : thematicGroupStore
                        },
//                        {
//                            xtype: 'textfield',
//                            fieldLabel: 'Sub Themes',
//                            anchor: '97%',
//                            name: 'QuestionThematicTags'
//                        },
                        {
                            xtype: 'combo',
                            forceSelection: true,
                            editable: false,
                            id: 'QuestionThematicTagsCombo',
                            anchor: '97%',
                            fieldLabel: 'Sub Themes',
                            name: 'QuestionThematicTags',
                            triggerAction: 'all',
                            displayField: 'name',
//                            hiddenName: 'hiddenVariable',
//                            valueField: 'id',
                            mode: 'local',
                            store : thematicTagStore
                        }
                    ]
                },
                {
                    xtype: 'fieldset',
                    title: 'Routing Information',
                    collapsed: false,
                    collapsible: true,
                    defaults: { labelStyle: 'font-weight:bold;' },
                    items: [
                        {
                            xtype: 'combo',
                            forceSelection: true,
                            editable: false,
                            id: 'questionTypeCombo',
                            anchor: '97%',
                            fieldLabel: 'Type',
                            name: 'QuestionType',
                            triggerAction: 'all',
                            displayField: 'name',
//                            hiddenName: 'hiddenVariable',
//                            valueField: 'id',
                            mode: 'local',
                            store : questionTypeStore
                        }
                        ,
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
                url: insertQuestion,
                waitMsg: 'Inserting Question Data....',
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

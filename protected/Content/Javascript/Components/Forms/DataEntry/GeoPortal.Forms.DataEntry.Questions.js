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
                        id: 'btnQsave',
                        icon: 'images/silk/database_edit.png',
                        text: 'Save',
                        type: 'reset',
                        handler : this.FormSave,
                        scope : this
                    },
//                    {
//                        xtype: 'button',
//                        id: 'btnQuestionInsert',
//                        icon: 'images/silk/application_form_add.png',
//                        text: 'Insert',
//                        type: 'reset',
//                        handler : this.FormInsert,
//                        scope : this
//                    },
//                    {
//                        xtype: 'button',
//                        id: 'btnQuestionUpdate',
//                        icon: 'images/silk/application_form_edit.png',
//                        text: 'Update',
//                        type: 'reset',
//                        handler : this.FormUpdate,
//                        scope : this
//                    },
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
//                    collapsible: true,
                    defaults: { labelStyle: 'font-weight:bold;' },
                    items: [
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Survey ID',
                            readOnly: true,
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
                        }
                    ]
                },
                {
                    xtype: 'fieldset',
                    title: 'WISERD Thematic Tagging',
//                    collapsible: true,
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
//                    collapsed: false,
//                    collapsible: true,
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
                        },
                        {
                            xtype: 'container',
                            layout: {
                                type: 'column'
                            },
                            items: [
                                {
                                    xtype: 'container',
                                    layout: {
                                        type: 'form'
                                    },
                                    columnWidth: 0.9,
                                    items: [
                                        {
                                            xtype: 'textfield',
                                            labelStyle: 'font-weight:bold;',
                                            id: 'followsQIDfield',
                                            fieldLabel: 'Follows QID',
                                            anchor: '97%',
                                            name: 'QuestionLinkedFrom'
                                        }
                                    ]
                                },
                                {
                                    xtype: 'container',
                                    layout: {
                                        type: 'form'
                                    },
                                    columnWidth: 0.1,
                                    items: [
                                        {
                                            xtype: 'button',
                                            id: 'btnRouting',
                                            icon: 'images/silk/magnifier.png',
                                            text: 'Find QID',
//                                            type: 'reset',
                                            handler : function(){
                                                var surveyField = Ext.getCmp('QuestionSurveyID');
                                                var surveyID = surveyField.getValue();
                                                var routingWin = new Ext.Window(
                                                    { items:[new GeoPortal.Forms.DataEntry.QuestionRouting(
                                                        {
                                                            textfieldcmp : 'followsQIDfield',
                                                            SID : surveyID
                                                        }
                                                    )],
                                                        title:'Load Question',
                                                        modal:true,
                                                        width:500,
                                                        id:'routingWin'
                                                    });
                                                routingWin.show();

                                            },
                                            scope : this
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            xtype: 'container',
                            layout: {
                                type: 'column'
                            },
                            items: [
                                {
                                    xtype: 'container',
                                    layout: {
                                        type: 'form'
                                    },
                                    columnWidth: 0.9,
                                    items: [
                                        {
                                            xtype: 'textfield',
                                            labelStyle: 'font-weight:bold;',
                                            id: 'subOfQIDfield',
                                            fieldLabel: 'Sub Of',
                                            anchor: '97%',
                                            name: 'QuestionSubOf'
                                        }
                                    ]
                                },
                                {
                                    xtype: 'container',
                                    layout: {
                                        type: 'form'
                                    },
                                    columnWidth: 0.1,
                                    items: [
                                        {
                                            xtype: 'button',
                                            id: 'btnsubOfRouting',
                                            icon: 'images/silk/magnifier.png',
                                            text: 'Find QID',
//                                            type: 'reset',
                                            handler : function(){
                                                var surveyField = Ext.getCmp('QuestionSurveyID');
                                                var surveyID = surveyField.getValue();
                                                var routingWin = new Ext.Window(
                                                    { items:[new GeoPortal.Forms.DataEntry.QuestionRouting(
                                                        {
                                                            textfieldcmp : 'subOfQIDfield',
                                                            SID : surveyID
                                                        }
                                                    )],
                                                        title:'Load Question',
                                                        modal:true,
                                                        width:500,
                                                        id:'routingWin'
                                                    });
                                                routingWin.show();

                                            },
                                            scope : this
                                        }
                                    ]
                                }
                            ]
                        }
                    ]
                }
            ]

            GeoPortal.Forms.DataEntry.Questions.superclass.initComponent.call(this);
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
        },
        FormSave : function() {

            var sid = Ext.getCmp('QuestionSurveyID').getValue();
            var qid = Ext.getCmp('QuestionIdField').getValue();

            var submit = true;
            if(sid == "") {
                submit = false;
                Ext.MessageBox.alert('Survey ID Error', 'Please load a survey to add a question to');
            }
            if(qid == "") {
                submit = false;
                Ext.MessageBox.alert('Question ID Error', 'Please enter a Question ID, normally related to the Survey ID');
            }
            if (submit) {

                Ext.Ajax.request({
                    url: checkRecordExists,
                    scope: this,
                    method : 'POST',
                    params : {
                        recordType: "survey_question",
                        recordID: qid
                    },
                    success: function(resp) {
                        var responseData = Ext.decode(resp.responseText);
                        var qidProjectID = responseData.projectid;
                        console.log(qidProjectID);

                        if (responseData.exists == true) {
                            console.log('ok ' + responseData);
                            Ext.MessageBox.confirm('Status', 'Question qid is already in use, overwrite?', function (btn, text) {

                                if(btn == 'yes') {
                                    Ext.getCmp('frmEntryQuestion').getForm().submit({
                                        scope: this,
                                        url: insertQuestion,
                                        params : {
                                            update : true,
                                            projectID : qidProjectID
                                        },
                                        waitMsg: 'Inserting Question Data....',
                                        success: function (form, action) {
                                            Ext.MessageBox.alert("Success", "Question data overwritten");
                                        },
                                        failure: function (form, action) {
                                            Ext.MessageBox.alert("Failure", action.result.message);
                                        }
                                    });
                                } else {
                                    Ext.MessageBox.alert("no, don't overwrite question");
                                }
                            });
                        } else {

                            var thisPanel = Ext.getCmp('frmEntryQuestion');
                            if(thisPanel.getForm().isValid()) {
                                thisPanel.getForm().submit({
                                    url: insertQuestion,
                                    waitMsg: 'Inserting Question Data....',
                                    success: function (form, action) {
                                        Ext.Msg.alert("Success!",action.result.message);
                                    },
                                    failure: function (form, action) {
                                        Ext.Msg.alert("Error!",action.result.message);
                                    }
                                });
                            } else {
                                Ext.MessageBox.alert('Status', 'There is an invalid entry in the Question entry form.');
                            }

                        }
                    },
                    failure: function(resp) {

                    }
                });
            }
        }
    }
);

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


            this.items = [
                {
                    xtype: 'fieldset',
                    title: 'General',
                    collapsible: true,
                    defaults: { labelStyle: 'font-weight:bold;' },
                    items: [
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
        }
    }
);

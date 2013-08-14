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

            this.items = [
                {
                    xtype: 'fieldset',
                    title: 'General',
                    defaults: { readOnly: true, labelStyle: 'font-weight:bold;' },
                    collapsible: true,
                    items: [
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Question ID',
                            anchor: '97%',
                            name: 'questionID'
                        },
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Response ID',
                            anchor: '97%',
                            name: 'responseID'
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
                    collapsed: true,
                    collapsible: true,
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
        FormReset : function() {
            console.log('reset ' + this.id)
            var thisPanel = Ext.getCmp(this.id);
            console.log(thisPanel);
            thisPanel.getForm().reset();
        }
    }
);

GeoPortal.Windows.BugReport = Ext.extend(Ext.Window, {
    title: 'Report A Bug',
    id: 'bugWindow',
    width: 800,
    height: Ext.getBody().getViewSize().height * 0.8,
    buttons: [{ xtype: 'button',
        text: 'Submit',
        icon: '../images/silk/email.png',
        handler: function () {
            var form = Ext.getCmp('bugForm');
            if (form.getForm().isValid()) {
                form.getForm().submit({
                    url: BugUrl,
                    waitMsg: 'Submitting bug report...',
                    success: function (form, action) {
                        Ext.getCmp('bugWindow').destroy();
                        Ext.Msg.alert("Sucess!", action.result.message);
                    },
                    failure: function (form, action) {
                        Ext.Msg.alert("Error!", action.result.message);
                    }
                });
            }
        }
    }],
    initComponent: function () {
        this.items = [
            {
                xtype: 'form',
                id: 'bugForm',
                checkValid: true,
                bodyStyle: 'padding: 5px',
                items: [
                    {
                        xtype: 'fieldset',
                        title: 'Contact Details',
                        collapsible: true,
                        animCollapse: true,
                        layoutConfig: {
                            defaultAnchor: 75
                        },
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: 'From',
                                anchor: '75%',
                                name: 'name',
                                allowBlank: false
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Email Address',
                                anchor: '75%',
                                name: 'email',
                                allowBlank: false
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Date',
                                id: 'sendDate',
                                anchor: '75%',
                                name: 'sendDate',
                                readOnly: true
                            }
                        ]
                    },
                    {
                        xtype: 'fieldset',
                        title: 'Bug Report',
                        labelAlign: 'top',
                        animCollapse: true,
                        collapsible: true,
                        items: [
                            {
                                xtype: 'textarea',
                                anchor: '100%',
                                fieldLabel: 'Problem Encountered',
                                name: 'message',
                                height: this.height * 0.4,
                                allowBlank: false
                            },
                            {
                                xtype: 'textarea',
                                anchor: '100%',
                                fieldLabel: 'Activity when problem encountered',
                                height: this.height * 0.15,
                                name: 'activity',
                                allowBlank: false
                            }
                        ]
                    }
                ]
            }
        ];
        GeoPortal.Windows.BugReport.superclass.initComponent.call(this);
    }
});

GeoPortal.Windows.BugReport = Ext.extend(Ext.Window, {
    title: 'Report A Bug',
    id: 'bugWindow',
    width: 800,
    height: Ext.getBody().getViewSize().height * 0.8,
    maximizable: true,
    autoScroll: true,
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

        var nameField = new Ext.form.TextField(
            {
                xtype: 'textfield',
                fieldLabel: 'From',
                id: 'bugFromField',
                anchor: '75%',
                name: 'name'
//                allowBlank: false,
//                readOnly: true
            }
        );


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
//                            {
//                                xtype: 'textfield',
//                                fieldLabel: 'From',
//                                id: 'bugFromField',
//                                anchor: '75%',
//                                name: 'name',
//                                allowBlank: false
//                            },
                            nameField,
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

        var usernameField = Ext.getCmp('txtSBUser');
        console.log(usernameField.el.dom.innerHTML);
        console.log(usernameField.el);
        console.log(usernameField.el.innerText);
        nameField.setValue(usernameField.el.innerText);

        GeoPortal.Windows.BugReport.superclass.initComponent.call(this);
    }
});

Ext.ns('GeoPortal.Forms');
GeoPortal.Forms.Register = Ext.extend(Ext.form.FormPanel, {
    padding: 10,
    monitorValid: true,
    height: Ext.getBody().getViewSize().height * 0.8,
    labelWidth: 80,
    autoScroll: true,
    maximizable: true,
    id: 'frmRegister',
    //registartion function
    RegisterMe: function () {
        var regPanel = Ext.getCmp('frmRegister');

        regPanel.getForm().submit({
            url: regURL,
            method: 'post',
            waitMsg: 'Creating User.....',
            params: { UserName: regPanel.getForm().getValues().txtRegUserName,
                password: regPanel.getForm().getValues().txtRegPassword,
                firstName: regPanel.getForm().getValues().txtRegFName,
                lastName: regPanel.getForm().getValues().txtRegLName,
                Email: regPanel.getForm().getValues().txtRegEmail,
//                Email2: regPanel.getForm().getValues().txtRegEmail2,
                Bio: regPanel.getForm().getValues().txtRegBio,
                Institution: regPanel.getForm().getValues().txtRegInstitution,
                Tel: regPanel.getForm().getValues().txtRegTelephone,
                Address: regPanel.getForm().getValues().txtRegAddress
            },
            success: function (form, action) {
                Ext.getCmp("RegWin").hide();
                Ext.Msg.alert("Success!", action.result.message);
            },
            failure: function (form, action) {
                console.log(action);
                switch (action.failureType) {
                    case Ext.form.Action.CLIENT_INVALID:
                        Ext.Msg.alert('Failure', 'Form contains invalid values. Check your username and password');
                        break;
                    case Ext.form.Action.CONNECT_FAILURE:
                        Ext.Msg.alert('Failure', 'Communication failure - please try again');
                        break;
                    case Ext.form.Action.SERVER_INVALID:
                        Ext.Msg.alert('Failure', 'Error with servers response, please check input fields for possible errors');
                        break;
                    default :
                        Ext.Msg.alert("Error", "An error occured during registration. Please check the information fields and try again");
                }
            }
        })


    },
    FormReset : function() {
        var regPanel = Ext.getCmp('frmRegister');
        regPanel.getForm().reset();
    },
    Info : function(){
        Ext.Msg.alert("Why Register?", "The WISERD programme will use the details you provide for the purpose of [insert purpose i,e why you need the information]. As a collaborative programme, the information you provide will be shared between Aberystwyth, Bangor, Cardiff, Glamorgan and Swansea Universities all of whom are registered under the Data Protection Act with the Information Commissioners Office. Your information will be kept securely and will not be provided to third parties outside of the collaboration without your permission.");
    },
    initComponent: function () {
        this.items = [
            {
                xtype: 'fieldset',
                title: 'Login Details',
                items: [
                    {
                        xtype: 'container',
                        layout: 'column',
                        items: [
                            {
                                xtype: 'container',
                                columnWidth: 1,
                                layout: 'form',
                                items: [
                                    {
                                        xtype: 'textfield',
                                        fieldLabel: 'UserName',
                                        allowBlank: false,
                                        anchor: '100%',
                                        id: 'txtRegUserName',
                                        listeners: {
                                            'change': function(){
                                                var txtcmp = Ext.getCmp('txtRegUserName');
                                                var username = txtcmp.getValue();
                                                var registerBtn = Ext.getCmp('btnRegister');

                                                Ext.Ajax.request({
                                                    url: checkNameFreeURL,
                                                    params : {UserName : username},
                                                    method : 'POST',

                                                    success: function(resp) {
                                                        var responseData = Ext.decode(resp.responseText);
                                                        console.log(responseData);

                                                        if (responseData.success == true){
                                                            registerBtn.enable();
//                                                            alert("The username " + username + " is available.");
                                                        } else {
                                                            registerBtn.disable();
                                                            txtcmp.markInvalid();
                                                            alert("Unfortunatly, this username is already in use. Please try another");
                                                        }
                                                    },
                                                    failure: function(resp) {

                                                    }
                                                });
                                            }
                                        }
                                    }
                                ]
                            },
                            {
                                xtype: 'container',
                                columnWidth: 1,
                                layout: 'form',
                                items: [
                                    {
                                        xtype: 'textfield',
                                        fieldLabel: 'Password',
                                        allowBlank: false,
                                        anchor: '100%',
                                        id: 'txtRegPassword',
                                        inputType: 'password'
                                    }
                                ]
                            }
                        ]
                    }
                ]
            },
            {
                xtype : 'fieldset',
                title : 'User Details',
                items: [
                    {
                        xtype: 'container',
                        columnWidth: 0.5,
                        layout: 'form',
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: 'First Name',
                                allowBlank: false,
                                anchor: '100%',
                                id: 'txtRegFName'
                            },
                            {
                                xtype: 'textfield',
                                anchor: '100%',
                                fieldLabel: 'LastName',
                                allowBlank: false,
                                id: 'txtRegLName'
                            }
                        ]
                    },
                    {
                        xtype: 'container',
                        columnWidth: 0.5,
                        layout: 'form',
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Email',
                                allowBlank: false,
                                anchor: '100%',
                                id: 'txtRegEmail'
                            }
//                            {
//                                xtype: 'textfield',
//                                fieldLabel: 'Email 2',
//                                anchor: '100%',
//                                id: 'txtRegEmail2'
//                            }
                        ]
                    }
                ]
            },
            {
                xtype: 'fieldset',
                title: 'Bio',
                items: [
                    {
                        xtype: 'container',
                        layout: 'form',
                        items: [
                            {
                                xtype: 'textarea',
                                anchor: '100%',
                                fieldLabel: 'Bio',
                                height: 200,
                                id: 'txtRegBio'
                            }
                        ]
                    },
                    {
                        xtype: 'container',
                        layout: 'column',
                        items: [
                            {
                                xtype: 'container',
                                columnWidth: 0.5,
                                layout: 'form',
                                items: [
                                    {
                                        xtype: 'textfield',
                                        fieldLabel: 'Institution',
                                        anchor: '95%',
                                        id: 'txtRegInstitution'
                                    }
                                ]
                            },
                            {
                                xtype: 'container',
                                columnWidth: 0.5,
                                layout: 'form',
                                items: [
                                    {
                                        xtype: 'textfield',
                                        anchor: '100%',
                                        fieldLabel: 'Telephone',
                                        id: 'txtRegTelephone'
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        xtype: 'textarea',
                        anchor: '100%',
                        fieldLabel: 'Address',
                        height: 100,
                        id: 'txtRegAddress'
                    }
                ]
            }
        ];
        this.bbar = {
            xtype: 'toolbar',
            items: [
                {
                    xtype: 'button',
                    text: 'Data Info',
                    id: 'btnInfo',
                    handler: function(){
                        Ext.Msg.alert("Why Register?", "The WISERD programme will use the details you provide for the purpose of [insert purpose i,e why you need the information]. <br>As a collaborative programme, the information you provide will be shared between Aberystwyth, Bangor, Cardiff, Glamorgan and Swansea Universities, all of whom are registered under the Data Protection Act with the Information Commissioners Office. <br>Your information will be kept securely and will not be provided to third parties outside of the collaboration without your permission.");
                    }
                },
                {
                    xtype: 'tbseparator'
                },
                {
                    xtype: 'tbfill'
                },
                {
                    xtype: 'button',
                    text: 'Reset',
                    id: 'btnReset',
                    handler: this.FormReset,
                    scope: this
                },
                {
                    xtype: 'tbseparator'
                },
                {
                    xtype: 'button',
                    text: 'Register',
                    icon: '../images/silk/wand.png',
                    handler: this.RegisterMe,
                    id: 'btnRegister'
                }
            ]
        };
        GeoPortal.Forms.Register.superclass.initComponent.call(this);
    }
});

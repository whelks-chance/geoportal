//Log-in form for the user evaluation test - can also use it for 'live' Geoportal

GeoPortal.Forms.UsabilityLogin = Ext.extend(Ext.form.FormPanel, {
    height: 138,
    width: 400,
    padding: 10,
//    layout: 'fit',
    id: 'frmLogin',

    initComponent: function () {
        Ext.applyIf(this, {
            items: [
                {
                    xtype: 'textfield',
                    allowBlank: false,
                    id: 'txtUsername',
                    anchor: '100%',
                    fieldLabel: 'Username'
                },
                {
                    xtype: 'textfield',
                    allowBlank: false,
                    id: 'txtPassword',
                    anchor: '100%',
                    fieldLabel: 'Password',
                    inputType: 'password'
                },
                {
                    xtype: 'label',
                    anchor: '100%',
                    text: 'WARNING: The WISERD DataPortal is not compatible with Internet Explorer 8 or lower'
                }
//                {
//                    xtype: 'box',
//                    achor: '100%',
//                    autoEl: {
//                        tag: 'a',
//                        href: 'http://www.wiserd.ac.uk/',
//                        target: '_blank',   //  for open in new window
//                        cn: 'Click here to view DataPortal cookie policy'
//                    }
//                }
            ],
            fbar: {
//                xtype: 'panel',
//                dock: 'bottom',
//                fieldLabel: 'Login',
//                hideLabel: true,
//                labelSeparator: '',
//                height:25,
                items: [
                    {
                        xtype: 'button',
                        id: 'btnCookie',
                        text: 'Cookie Policy',
                        icon: 'images/silk/database_add.png',
                        handler: function () {
                            var cookieForm = new GeoPortal.Windows.Cookies();
                            cookieForm.show()
                        }
                    },
//                    {
//                        xtype: 'tbfill'
//                    },
                    {
                        xtype: 'button',
                        id: 'btnRegister',
                        text: 'Register',
                        icon: 'images/silk/user_add.png',
                        handler: function () {

                            var RegForm = new GeoPortal.Forms.Register();

                            var win = new Ext.Window({
                            id: "RegWin",
                            title: 'Registration Details',
                            items: [RegForm],
                            modal: true,
                            width: 600,
                            layout: 'fit',
                            resizable: true,
                            maximizable: true

                            });
                        win.show();
                        }
                    },
                    {
                        xtype: 'button',
                        id: 'btnLogin',
                        text: 'Login',
                        icon: 'images/silk/key.png',
                        handler: function () {
                            //get the data relating to the user's browser and screen
                            var screenSize = screen.width + 'x' + screen.height;
                            var browser = $.browser.browser();
                            var versionNo = $.browser.version.number();
                            var versionStr = $.browser.version.string();
                            var os = $.browser.OS();


                            var frm = Ext.getCmp('frmLogin');
                            frm.getForm().submit({
                                clientValidation: true,
                                //the url of the controller and function
                                url: captureURL,
                                params: {
                                    browser: browser,
                                    version: versionStr,
                                    versionNo: versionNo,
                                    os: os,
                                    screenSize: screenSize

                                },

                                //if form successfully submitted then alert user and proceed with log-in
                                success: function (form, action) {

                                    Ext.Msg.alert('', "You are now logged-in!");
                                    Ext.getCmp("txtSBUser").getEl().update(Ext.getCmp('txtUsername').getValue());
                                    Ext.getCmp('WinLogin').close();

                                },
                                failure: function (form, action) {
                                    switch (action.failureType) {
                                        case Ext.form.Action.CLIENT_INVALID:
                                            Ext.Msg.alert('Failure', 'Form contains invalid values. Check your username and password');
                                            break;
                                        case Ext.form.Action.CONNECT_FAILURE:
                                            Ext.Msg.alert('Failure', 'Communication failure - please try again');
                                            break;
                                        case Ext.form.Action.SERVER_INVALID:
                                            Ext.Msg.alert('Failure', 'Error with username or password, please check and try again.');
                                    }
                                }

                            });


                        }

                    }
                ]
            }
        });

        GeoPortal.Forms.UsabilityLogin.superclass.initComponent.call(this);
    }
});

Ext.apply(Ext.form.VTypes, {
    password: function(value, field)
    {
        if (field.initialPasswordField)
        {
            var pwd = Ext.getCmp(field.initialPasswordField);
            this.passwordText = 'Confirmation does not match your intial password entry.';
            return (value == pwd.getValue());
        }

        this.passwordText = 'Passwords must be at least 5 characters, containing either a number, or a valid special character (!@#$%^&*()-_=+)';

        var hasSpecial = value.match(/[0-9!@#\$%\^&\*\(\)\-_=\+]+/i);
        var hasLength = (value.length >= 5);

        return (hasSpecial && hasLength);
    },

    passwordText: 'Passwords must be at least 5 characters, containing either a number, or a valid special character (!@#$%^&*()-_=+)'
});

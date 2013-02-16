GeoPortal.Forms.Login = Ext.extend(Ext.form.FormPanel, {
    id: 'LoginPanel',
    width: 351,
    height: 150,
    padding: 10,
    waitMsg: 'Loading....',
    buttons: [{
        text: 'Register',
        id: 'btnRegister',
        icon: 'images/silk/user_add.png',
        handler: function () {

            var RegForm = new GeoPortal.Forms.Register()

            var win = new Ext.Window({
                id: "RegWin",
                title: 'Registration Details',
                items: [RegForm],
                modal: true,
                width: 600,
                layout: 'fit',
                resizable: false

            })
            win.show();
        }
    }, {
        text: 'Recover Password',
        id: 'btnForgotPwd'
    }, {
        text: 'Login',
        id: 'btnLogin',
        handler: function () {
            var login = Ext.getCmp('LoginPanel');
            login.getForm().submit({
                url: loginURL,
                //method: 'post',
                waitMsg: 'Checking User Details...',
                params: { UserName: login.getForm().getValues().txtUsername, Password: login.getForm().getValues().txtPassword },
                success: function (form, action) {
                    Ext.Msg.alert('LoginSucess', action.result.message);
                    Ext.getCmp("txtSBUser").getEl().update(action.result.FirstName + ' ' + action.result.LastName);
                    // login.getForm().reset();
                    Ext.getCmp('btnTBPrint').disable();
                    Ext.getCmp('btnTBLogin').disable();
                    Ext.getCmp('btnTBMyAcc').enable();
                    Ext.getCmp('btnTBSave').enable();
                    Ext.getCmp('LoginWin').destroy();
                },
                failure: function (response) {
                    //data = Ext.decode(response.responseText);
                    Ext.Msg.alert('Login Error');
                } 
                })
        }
    }],
     initComponent: function () {
        this.items = [
            {
                xtype: 'textfield',
                fieldLabel: 'Username',
                anchor: '100%',
                id: 'txtUsername', 
                allowBlank: false
            },
            {
                xtype: 'textfield',
                fieldLabel: 'Password',
                anchor: '100%',
                inputType: 'password',
                id: 'txtPassword' ,
                allowBlank: false
            }
        ];
        GeoPortal.Forms.Login.superclass.initComponent.call(this);
    },
    LogMeIn: function () {



    }
});

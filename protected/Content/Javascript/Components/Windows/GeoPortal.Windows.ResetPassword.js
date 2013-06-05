/**
 * Created with JetBrains PhpStorm.
 * User: wiserd
 * Date: 05/06/13
 * Time: 09:23
 * To change this template use File | Settings | File Templates.
 */

GeoPortal.Windows.ResetPassword = Ext.extend(Ext.Window, {
    id: 'resetPasswordWindow',
    title: 'Reset Password',
    width: Ext.getBody().getViewSize().width * 0.5,
    height: Ext.getBody().getViewSize().height * 0.5,
    resizable: true,
    modal: true,

    buttons: [{ xtype: 'button',
        text: 'Submit',
        icon: '../images/silk/email.png',
        handler: function () {
            var form = Ext.getCmp('resetPasswordForm');
            if (form.getForm().isValid()) {
                form.getForm().submit({
                    url: ResetPWURL,
                    waitMsg: 'Resetting password...',
                    success: function (form, action) {
                        Ext.getCmp('resetPasswordWindow').destroy();
                        Ext.Msg.alert("Email sent", action.result.message);
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
                id: 'resetPasswordForm',
                checkValid: true,
                bodyStyle: 'padding: 5px',
                items: [
                    {
                        xtype: 'textfield',
                        allowBlank: false,
                        id: 'txtUsername',
                        anchor: '100%',
                        fieldLabel: 'Username:'
                    },
                    {
                        xtype: 'textfield',
                        allowBlank: false,
                        id: 'txtEmail',
                        anchor: '100%',
                        fieldLabel: 'Email:'
                    }
                ]
            }
        ];
        GeoPortal.Windows.ResetPassword.superclass.initComponent.call(this);
    }

});
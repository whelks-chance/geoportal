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
    width: Ext.getBody().getViewSize().width * 0.4,
//    height: Ext.getBody().getViewSize().height * 0.3,
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
//                layout: 'column',

                items: [
                    {
                        xtype: 'label',
                        name: 'resetLabel1',
                        id: 'resetLabel1',
                        bodyStyle: 'padding: 5px',
                        text: 'Please input the email address you registered your account with.',
                        cls: 'x-form-item-label x-form-item'
                    },
                    {
                        xtype: 'label',
                        name: 'resetLabel2',
                        id: 'resetLabel2',
                        bodyStyle: 'padding: 5px',
                        text: 'A new password will be generated and emailed to you.',
                        cls: 'x-form-item-label x-form-item'
                    },
                    {
                        xtype: 'label',
                        name: 'resetLabel3',
                        id: 'resetLabel3',
                        bodyStyle: 'padding: 5px',
                        text: 'Please then login with the new password, and change it in the "User Account" panel',
                        cls: 'x-form-item-label x-form-item'
                    },
                    {
                        xtype: 'textfield',
                        allowBlank: false,
                        id: 'txtEmail',
                        anchor: '100%',
                        fieldLabel: 'Email',
                        bodyStyle: 'padding: 15px'
                    }
                ]
            }
        ];
        GeoPortal.Windows.ResetPassword.superclass.initComponent.call(this);
    }

});
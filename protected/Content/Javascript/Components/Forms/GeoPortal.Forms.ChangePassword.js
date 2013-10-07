GeoPortal.Forms.ChangePassword = Ext.extend(Ext.form.FormPanel, {
    width: 300,
    id: 'frmChangePW',
//    height: 140,
    padding: 5,
    initComponent: function () {
        this.buttons  =[
           {
           xtype: 'button',
           text: 'Change',
           id: 'btnChangePW',
           handler: function(){
                    var pwPanel = Ext.getCmp('frmChangePW');
                      pwPanel.getForm().submit({
                          url: ChangePWURL,
                          waitMsg: 'Changing Password.....',
                          success: function (form, action) {
                                             Ext.Msg.alert("Sucess!",action.result.message);
                                             Ext.getCmp('ChgPWWin').destroy();
                                        },
                          failure: function (form, action) {
                                             Ext.Msg.alert("Error!",action.result.message);
                          }                        
             } )
           }
        }],
        this.items = [
            {
                xtype: 'textfield',
                fieldLabel: 'Old Password',
                anchor: '100%',
                name: 'oldPassword',
                inputType: 'password',
                id: 'txtOldPassword',
                allowBlank: false
            },
            {
                xtype: 'textfield',
                fieldLabel: 'New Password',
                anchor: '100%',
                name: 'newPW1',
                inputType: 'password',
                id: 'txtNewPassword1',
                allowBlank: false,
                vtype: 'password',
                maxLength: 64
            },
            {
                xtype: 'textfield',
                fieldLabel: 'Confirm Password',
                anchor: '100%',
                name: 'newPW2',
                inputType: 'password',
                id: 'txtNewPassword2' ,
                allowBlank: false,
                vtype: 'password',
                maxLength: 64,
                initialPasswordField: 'txtNewPassword1'
            }
        ];
        GeoPortal.Forms.ChangePassword.superclass.initComponent.call(this);
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
 
   passwordText: 'Passwords must be at least 5 characters, containing either a number, or a valid special character (!@#$%^&*()-_=+)',
});

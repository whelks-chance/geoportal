GeoPortal.Forms.UserDetails = Ext.extend(Ext.form.FormPanel, {
//    width: 800,
//    height: 800,
    loadMask: true,
    loadMsg: 'loading.....',
    waitMsg: 'Loading........',
    id: 'frmMyDetails',
    title: 'My Details',
    bodyStyle: 'padding: 5px',
    autoScroll: true,
    hideBorders: true,
    initComponent: function () {
        this.items = [
            { xtype: 'fieldset',
                layout: 'form',
                items: [{
                    xtype: 'fieldset',
                    anchor: '98%',
                    title: 'My Details',
                    layout: 'column',
                    items: [{
                        frame: true,
                        layout: 'form',
                        columnWidth: 0.75,
                        bodyStyle: "padding: 10px",
                        style: 'padding:15px',
                        buttonAlign: 'right',
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: 'First Name',
                                name: 'FirstName',
                                anchor: '95%',
                                id: 'txtFirstName'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Last Name',
                                name: 'LastName',
                                anchor: '95%',
                                id: 'txtLastName'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'User Name',
                                name: 'UserName',
                                anchor: '95%',
                                id: 'txtUserName'
                            }],
                        buttons: [{
                            xtype: 'button',
                            text: 'Change Password',
                            bodyStyle: 'float: right',
                            icon: './images/silk/lock_edit.png',
                            handler: function () {

                                var CPWrdWin = new Ext.Window({ items: [new GeoPortal.Forms.ChangePassword], title: 'Change Password', modal: true, height: 180, width: 310, id: 'ChgPWWin' });
                                CPWrdWin.show();


                            }
                        }]
                    }, {
                        layout: 'form',
                        frame: true,
                        anchor: '95%',
                        style: 'padding:15px',
                        columnWidth: 0.25,
                        items: [
                            {
                                xtype: 'panel',
                                //width: 150,
                                //name: 'Avatar',
                                //height:100,
                                bodyStyle: 'margin-left: auto; margin-right: auto ',
                                id: 'bxMyIcon'
//                                html: '<center><img src="../Services/LoadDBImage.ashx?UID=19" id="imgAvatar" /></center>'
                            }
                        ],
                        buttons: [{
                            xtype: 'button',
                            text: 'Change Avatar',
                            icon: './images/silk/vcard.png' ,
                             handler: function () {

                                 var UploadWin = new Ext.Window({ items: [new genUploadForm()], title: 'Upload Avatar', modal: true, autoHeight: true, width: 310, id: 'UploadAVWin' });
                               UploadWin.show();


                            }
                        }]
                    }]
                }, {
                    xtype: 'fieldset',
                    layout: 'form',
                    anchor: '98%',
                    title: 'My Contact Details',
                    items: [{
                        xtype: 'textfield',
                        fieldLabel: 'Email Address',
                        name: 'Email',
                        anchor: '95%',
                        id: 'txtEmail'
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Contact No',
                        name: 'Telephone',
                        anchor: '95%',
                        id: 'txtPhone'
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Institution',
                        name: 'Institution',
                        anchor: '95%',
                        id: 'txtInstitution'
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Address',
                        name: 'Address',
                        anchor: '95%',
                        id: 'txtAddress'
                    }
                            ]
                }]
            },


          {
              xtype: 'fieldset',
              layout: 'fit',
              anchor: '98%',
              title: 'My Biography',
              items: [{
                  xtype: 'textarea',
                  id: 'txtBio',
                  name: 'Bio',
                  height: 250,
                  anchor: '98%'
              }],

              buttons: [{
                  xtype: 'button',
                  text: 'Save',
                  bodyStyle: 'float: right',
                  icon: './images/silk/disk.png',
                  handler: function () {
                      var bioPanel = Ext.getCmp('frmMyDetails');
                      bioPanel.getForm().submit({
                          url: bioUpdateURL,
                          waitMsg: 'Updating User Details.....',
                          success: function (form, action) {
                              Ext.Msg.alert("Sucess!", action.result.message);
                          },
                          failure: function (form, action) {
                              Ext.Msg.alert(action.result.message);
                          }
                      })




                  }
              }]
          }

          ]
        GeoPortal.Forms.UserDetails.superclass.initComponent.call(this);
    }
});
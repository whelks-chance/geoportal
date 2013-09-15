Ext.ns('GeoPortal.Windows');
GeoPortal.Windows.MyAccount = Ext.extend(Ext.Window, {
    title: 'My Account',
    id: 'myACWin',
    layout: 'fit',
    width: 800,
    height: Ext.getBody().getViewSize().height * 0.8,
//    autoScroll: true,
    maximizable: true,
    modal: true,
    initComponent: function () {

        Ext.Ajax.request({
            url: dataOptionLists,
            method : 'POST',
            success: function(resp) {
                var responseData = Ext.decode(resp.responseText);

                var acTabPanel = Ext.getCmp('myACtabpanel');

                if ( responseData['roles']['management'] == true) {
                    acTabPanel.add( new GeoPortal.Forms.ProjectManagement() );
                }
                if ( responseData['roles']['surveyEntry'] == true) {
                    acTabPanel.add( new GeoPortal.Forms.DataEntry.SurveyMainEntry())
                }
                if ( responseData['roles']['qualEntry'] == true) {
                    acTabPanel.add( new GeoPortal.Forms.Tagging() );
                }

                acTabPanel.doLayout();
            },
            params : {
                roles : true
            },
            failure: function(resp) {
                console.log('failure!');
            }
        });


        this.items = [
            {
                xtype: 'tabpanel',
                id: 'myACtabpanel',
                activeTab: 0,
                title: '',
                items: [
                    new GeoPortal.Forms.UserDetails(),
                    {
                        xtype: 'panel',
                        title: 'My Messages'
                    },
                    new GeoPortal.Forms.MyMaps(),
                    {
                        xtype: 'panel',
                        title: 'My Searches'
                    },
                    {
                        xtype: 'panel',
                        title: 'My Uploads'
                    }
//                    {
//                        xtype: 'panel',
//                        title: 'Data Entry',
//                        layout: 'border',
//                        items : [
//
//                            {
//                                xtype: 'button',
//                                id: 'btnDataEntry',
//                                icon: 'images/silk/application_get.png',
//                                text: 'DataEntry',
//                                type: 'reset',
//                                handler : function() {
//                                    var dataEntryWin = new GeoPortal.Windows.DataEntry();
//                                    dataEntryWin.show();
//                                },
//                                scope : this
//                            }
//
//                        ]
//                    }
                ]
            }
        ];
        GeoPortal.Windows.MyAccount.superclass.initComponent.call(this);
    }
});
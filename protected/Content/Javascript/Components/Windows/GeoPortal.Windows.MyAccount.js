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
            this.items = [
            {
                xtype: 'tabpanel',
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
                    },
                    {
                        xtype: 'panel',
                        title: 'Data Entry',
                        layout: 'border',
                        items : [

                            {
                                xtype: 'button',
                                id: 'btnDataEntry',
                                icon: 'images/silk/application_get.png',
                                text: 'DataEntry',
                                type: 'reset',
                                handler : function() {
                                    var dataEntryWin = new GeoPortal.Windows.DataEntry();
                                    dataEntryWin.show();
                                },
                                scope : this
                            }

//                            {
//                                xtype: 'field',
//                                region: 'north',
//                                id: 'breadcrumb',
//                                readOnly: 'true',
//                                value: 'DC -> Survey -> Question -> Response',
//                                width: '100%',
////                                    anchor: '97%',
//                                name: 'breadcrumb',
//                                updateBreadcrumb : function(wid, sid, qid, rid){
//                                    var surveyField = Ext.getCmp('breadcrumb');
//
//                                    var previous = surveyField.getValue().split("  =>  ")
//
//                                    if(wid != null) {
//                                        previous[0] = "DC " + wid;
//                                    }
//                                    if(sid != null) {
//                                        previous[1] = "Survey " + sid;
//                                    }
//                                    if(qid != null) {
//                                        previous[2] = "Question " + qid;
//                                    }
//                                    if(rid != null) {
//                                        previous[3] = "Response " + rid;
//                                    }
//                                    surveyField.setValue(previous[0] + '  =>  ' + previous[1] + '  =>  ' + previous[2] + '  =>  ' + previous[3]);
//                                }
//                            },
//                            {
//                                xtype: 'tabpanel',
//                                region: 'center',
////                                    title: 'Data Entry',
////                                    tabPosition: 'top',
//                                activeTab: 0,
////                                    height: '100%',
////                                    layout: 'fit',
//                                items: [
//                                    new GeoPortal.Forms.DataEntry.DublinCore(),
//                                    new GeoPortal.Forms.DataEntry.Survey(),
//                                    new GeoPortal.Forms.DataEntry.Questions(),
//                                    new GeoPortal.Forms.DataEntry.Response(),
//                                    new GeoPortal.Forms.DataEntry.DBlines()
//                                ]
//                            }
                        ]
                    }
                ]
            }
        ];
        GeoPortal.Windows.MyAccount.superclass.initComponent.call(this);
    }
});
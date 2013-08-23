/**
 * Created with JetBrains PhpStorm.
 * User: wiserd
 * Date: 19/08/13
 * Time: 14:33
 * To change this template use File | Settings | File Templates.
 */

GeoPortal.Windows.DataEntry = Ext.extend(Ext.Window, {
    title: 'Data Entry',
    id: 'dataEntryWindow',
    width: 800,
    height: Ext.getBody().getViewSize().height * 0.9,
    maximizable: true,
    autoScroll: true,
    layout: 'fit',

    initComponent: function () {

        this.items = [
            {
                xtype: 'tabpanel',
                activeTab: 0,
                title: 'quoi',
                items: [
                    {
                        xtype: 'panel',
                        title: 'Data Entry',
                        layout: 'border',
                        items : [
                            {
                                xtype: 'field',
                                region: 'north',
                                id: 'breadcrumb',
                                readOnly: 'true',
                                value: 'DC -> Survey -> Question -> Response',
                                width: '100%',
//                                    anchor: '97%',
                                name: 'breadcrumb',
                                updateBreadcrumb : function(wid, sid, qid, rid){
                                    var surveyField = Ext.getCmp('breadcrumb');

                                    var previous = surveyField.getValue().split("  =>  ")

                                    if(wid != null) {
                                        previous[0] = "DC " + wid;
                                    }
                                    if(sid != null) {
                                        previous[1] = "Survey " + sid;
                                    }
                                    if(qid != null) {
                                        previous[2] = "Question " + qid;
                                    }
                                    if(rid != null) {
                                        previous[3] = "Response " + rid;
                                    }
                                    surveyField.setValue(previous[0] + '  =>  ' + previous[1] + '  =>  ' + previous[2] + '  =>  ' + previous[3]);
                                }
                            },
                            {
                                xtype: 'tabpanel',
                                region: 'center',
//                                    title: 'Data Entry',
//                                    tabPosition: 'top',
                                activeTab: 0,
//                                    height: '100%',
//                                    layout: 'fit',
                                items: [
                                    new GeoPortal.Forms.DataEntry.DublinCore(),
                                    new GeoPortal.Forms.DataEntry.Survey(),
                                    new GeoPortal.Forms.DataEntry.Questions(),
                                    new GeoPortal.Forms.DataEntry.Response()
//                                    new GeoPortal.Forms.DataEntry.DBlines()
                                ]
                            }
                        ]
                    },
                    new GeoPortal.Forms.Tagging()
                ]
            }

        ];

        GeoPortal.Windows.DataEntry.superclass.initComponent.call(this);
    }
});

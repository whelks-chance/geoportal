GeoPortal.Forms.RemoteData = Ext.extend(Ext.form.FormPanel, {
//    width: 800,
//    height: 800,
//    autoScroll : true,
//    loadMask: true,
//    loadMsg: 'loading.....',
//    waitMsg: 'Loading........',
    id: 'frmRemoteData',
    title: 'Remote Data',
    layout: 'fit',
//    bodyStyle: 'padding: 5px',
//    hideBorders: true,
    initComponent: function () {

        var remoteStore = new Ext.data.JsonStore ({
            fields: [
                {name: 'name', mapping: 'name'},
                {name: 'url',  mapping: 'url'}],
            id: "remoteSourceStore"
//            root : ""
        });

        Ext.Ajax.request({
            url: remoteSourceURL,
            method : 'POST',
            success: function(resp) {
                console.log('success!');
//                        console.log( resp );
                var responseData = Ext.decode(resp.responseText);
//                        console.log(responseData);
                remoteStore.loadData(responseData);
//                        console.log(surveyStore);
            },
            failure: function(resp) {
                console.log('failure!');
            }
        });

        this.items = [
            {
                xtype: 'fieldset',
                title: 'Select Remote Data Source',
                items: [
                    {
                        xtype: 'combo',
                        id: 'cmboSource',
                        anchor: '100%',
                        fieldLabel: 'Select Remote Data Source',
                        name: 'Source',
                        triggerAction: 'all',
                        displayField: 'name',
                        hiddenName: 'hiddenURL',
                        valueField: 'url',
                        listeners: {
                            'select': function(t){
                                alert(t.value);
                            }
                        },
                        mode: 'local',
                        store : remoteStore
                    },
                    {
                        xtype: 'textfield',
                        id: 'txtRemoteKeyword',
                        emptyText: 'Keywords...',
                        anchor: '100%',
                        fieldLabel: 'Keyword',
                        name: 'Keyword'
                    },
                    {
                        xtype: 'button',
                        id: 'btnRemoteSearch',
                        icon: 'images/silk/magnifier.png',
                        text: 'Search',
                        tooltip: 'Submit Search',
                        handler: function() {
                            var txtcmp = Ext.getCmp('txtRemoteKeyword');
                            var keyword = txtcmp.getValue();

//                            var urlcmp = Ext.getCmp('cmboSource');
//                            var url = urlcmp.getValue();
//
//                            var remoteSourceURL = url.replace('wiserd_dataportal_replace_me', keyword);

                            Ext.Ajax.request({
                                url: remoteDataKeywordSearchURL,
                                params : {Keyword : keyword},
                                method : 'POST',
//                                useDefaultXhrHeader: false,
//                                disableCaching: false,
                                success: function(resp) {
                                    console.log('success!');
                                    var responseData = Ext.decode(resp.responseText);
                                    console.log(responseData);
                                    alert(t.value);
                                },
                                failure: function(resp) {
                                    console.log('failure!');
                                    alert(t.value)
                                }
                            });
                        }
                    }
                ]
            }
        ];
        GeoPortal.Forms.RemoteData.superclass.initComponent.call(this);
    }
});
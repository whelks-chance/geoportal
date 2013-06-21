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

        var datasetID = "";
        var measuresID = "";

        var remoteStore = new Ext.data.JsonStore ({
            fields: [
                {name: 'name', mapping: 'name'},
                {name: 'url',  mapping: 'url'}],
            id: "remoteSourceStore"
//            root : ""
        });

        var keywordStore = new Ext.data.JsonStore ({
            fields: [
                {name: 'name', mapping: 'name'},
                {name: 'id',  mapping: 'id'},
                {name: 'wiserd', mapping: 'wiserd'},
                {name: 'wiserd_survey', mapping: 'wiserd_survey'}],
            id: "foundKeywordStore"
//            root : ""
        });

        var regionStore = new Ext.data.JsonStore ({
            fields: [
                {name: 'name', mapping: 'name'},
                {name: 'id',  mapping: 'id'}],
            id: "foundRegionStore",
            root : "regions"
        });

        var variableStore = new Ext.data.JsonStore ({
            fields: [
                {name: 'name', mapping: 'name'},
                {name: 'id',  mapping: 'id'}],
            id: "foundRegionStore",
            root : "measures"
        });

        var boundaryStore = new Ext.data.JsonStore ({
            fields: [
                {name: 'name', mapping: 'name'},
                {name: 'id',  mapping: 'id'}],
            id: "foundBoundaryStore"
//            root : ""
        });

        Ext.Ajax.request({
            url: remoteSourceURL,
            method : 'POST',
            success: function(resp) {
                var responseData = Ext.decode(resp.responseText);
                remoteStore.loadData(responseData);
            },
            failure: function(resp) {
                console.log('failure!');
            }
        });

        this.items = [
            {

                xtype: 'fieldset',
                id: 'fieldsource',
                title: 'Select Remote Data Source',
                items: [
                    {

                        // The combo box which allows selection of a remote API from the list.
                        // List data retrieved from RemoteData/getRemoteSources

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
//                                alert(t.value);
                            },
                            afterrender: function(combo) {
                                var recordSelected = combo.getStore().getAt(0);
                                combo.setValue(recordSelected.get('field1'));
                            }
                        },
                        mode: 'local',
                        store : remoteStore
                    },
                    {
                        // Text field for the keyword to be typed in

                        xtype: 'textfield',
                        id: 'txtRemoteKeyword',
                        emptyText: 'Keywords...',
                        anchor: '100%',
                        fieldLabel: 'Keyword',
                        name: 'Keyword'
                    },
                    {
                        // Search button.
                        // Takes the keyword and the value from the source dropdown and sends to RemoteData/doKeywordSearch
                        // After results arrive, loads the dataset store

                        xtype: 'button',
                        id: 'btnRemoteSearch',
                        icon: 'images/silk/magnifier.png',
                        text: 'Search',
                        tooltip: 'Submit Search',
                        handler: function() {
                            var txtcmp = Ext.getCmp('txtRemoteKeyword');
                            var keyword = txtcmp.getValue();
//                            var loadMask = new Ext.LoadMask(this.getBody(), {msg:"Retrieving Search Results...."});
//                            loadMask.show();

                            Ext.Ajax.request({
                                url: remoteDataKeywordSearchURL,
                                params : {Keyword : keyword},
                                method : 'POST',

                                success: function(resp) {
                                    console.log('success!');
                                    var responseData = Ext.decode(resp.responseText);
                                    console.log(responseData);

                                    Ext.getCmp('txtRemoteKeyword').enable();
                                    keywordStore.loadData(responseData);
//                                    loadMask.hide()

                                },
                                failure: function(resp) {
                                    console.log('failure!');
//                                    loadMask.hide();
                                }
                            });
                        }
                    },
//                ]
//            },
//            {
//                xtype: 'fieldset',
//                id: 'fielddata',
//                title: 'Data Resource',
//                items: [

                    {
                        // Drop down for the dataset
                        // Lets user select a survey, sends the id to RemoteData/getRemoteDataset
                        // Returns the regions available for the data

                        xtype: 'combo',
                        id: 'cmboDataset',
//                        tpl: '<tpl for="."><div ext:qtip="{name}" class="x-combo-list-item">{name}</div></tpl>',
                        tpl: new Ext.XTemplate(
                            '<tpl for=".">',
                            '<tpl if="wiserd.length == 0"> ',
                            '<div class="x-combo-list-item" style="color:#000000">{name}</div>',
                            '</tpl>',
                            '<tpl if="wiserd.length &gt; 0"> ',
                            '<div ext:qtip="MetaData available!" class="x-combo-list-item" style="color:#ff0000">{name}</div>',
                            '</tpl>',
                            '</tpl>'
                        ),
                        anchor: '100%',
                        fieldLabel: 'Select Remote DataSet',
                        name: 'Dataset',
                        triggerAction: 'all',
                        displayField: 'name',
                        hiddenName: 'hiddenURI',
                        valueField: 'id',
                        listeners: {
                            'select': function(t){
                                var cmboDataset = Ext.getCmp('cmboDataset');
                                datasetID = cmboDataset.getValue();

                                var index = keywordStore.find("id", datasetID);

                                var record = keywordStore.getAt(index);

                                var QID = record.get('wiserd');
                                var SID = record.get('wiserd_survey');

                                if(QID.length > 0) {

                                    Ext.MessageBox.confirm('MetaData Available', 'Would you like to view the WISERD MetaData for this dataset?',
                                        function (btn, text) {
                                            if (btn == 'yes') {

                                                var loadMask = new Ext.LoadMask(Ext.getBody(), {msg:"Retrieving Search Results...."});
                                                loadMask.show();

                                                var metaWindow = new GeoPortal.Windows.MetaData({ SID: SID });
                                                Ext.getCmp("frmQuestion").getForm().load({ url: QmetaURL, waitMsg: 'Loading.......', method: 'POST', params: { ID: QID} });
                                                Ext.getCmp("frmSurvey").getForm().load({ url: SmetaURL, waitMsg: 'Loading.......', method: 'POST', params: { SID: SID} });
                                                Ext.getCmp("frmResponse").getForm().load({ url: RmetaURL, waitMsg: 'Loading.......', method: 'POST', params: { QID: QID} });
                                                Ext.getCmp("frmDC").getForm().load({ url: DCmetaURL, waitMsg: 'Loading.......', method: 'POST', sucess: metaWindow.show(), params: { SID: SID} });

                                                loadMask.hide();
                                            }

                                        }
                                    );
                                }

                                Ext.Ajax.request({
                                    url: remoteDataSetURL,
                                    params : {Dataset : datasetID},
                                    method : 'POST',
                                    success: function(resp) {
                                        console.log('success!');
                                        var responseData = Ext.decode(resp.responseText);
                                        console.log(responseData);

                                        regionStore.loadData(responseData);
                                        variableStore.loadData(responseData);

                                        var csvarea = Ext.getCmp('jsonarea');
                                        csvarea.setValue(resp.responseText);

                                    },
                                    failure: function(resp) {
                                        console.log('failure!');
                                    }
                                });
                            }
                        },
                        mode: 'local',
                        store : keywordStore
                    },
                    {
                        xtype: 'combo',
                        id: 'cmboVariable',
                        anchor: '100%',
                        fieldLabel: 'Select Variable',
                        name: 'Variable',
                        triggerAction: 'all',
                        displayField: 'name',
                        hiddenName: 'hiddenVariable',
                        valueField: 'id',
                        listeners: {
                            'select': function(t){
                                var cmboVariable = Ext.getCmp('cmboVariable');
                                measuresID = cmboVariable.getValue();

//                                Ext.Ajax.request({
//                                    url: remoteVariableURL,
//                                    params : {Dataset : datasetID},
//                                    method : 'POST',
//                                    success: function(resp) {
//                                        console.log('success!');
//                                        var responseData = Ext.decode(resp.responseText);
//                                        console.log(responseData);
//
////                                        regionStore.loadData(responseData);
//
//                                        var csvarea = Ext.getCmp('jsonarea');
//                                        csvarea.setValue(resp.responseText);
//
//                                    },
//                                    failure: function(resp) {
//                                        console.log('failure!');
//                                    }
//                                });
                            }
                        },
                        mode: 'local',
                        store : variableStore
                    },
                    {
                        xtype: 'combo',
                        id: 'cmboRegion',
                        anchor: '100%',
                        fieldLabel: 'Select Region',
                        name: 'Region',
                        triggerAction: 'all',
                        displayField: 'name',
                        hiddenName: 'hiddenRegion',
                        valueField: 'id',
                        listeners: {
                            'select': function(t){

                                var cmboRegion = Ext.getCmp('cmboRegion');
                                var regionID = cmboRegion.getValue();

                                Ext.Ajax.request({
                                    url: remoteRegionURL,
                                    params : {RegionID : regionID, DatasetID: datasetID},
                                    method : 'POST',
                                    success: function(resp) {
                                        console.log('success!');
                                        var responseData = Ext.decode(resp.responseText);
                                        console.log(responseData);

                                        boundaryStore.loadData(responseData);

                                        var csvarea = Ext.getCmp('jsonarea');
                                        csvarea.setValue(resp.responseText);

                                    },
                                    failure: function(resp) {
                                        console.log('failure!');
                                    }
                                });
                            }
                        },
                        mode: 'local',
                        store : regionStore
                    },
                    {
                        xtype: 'combo',
                        id: 'cmboBoundary',
                        anchor: '100%',
                        fieldLabel: 'Select Boundary',
                        name: 'Dataset',
                        triggerAction: 'all',
                        displayField: 'name',
                        hiddenName: 'hiddenBoundary',
                        valueField: 'id',
                        listeners: {
                            'select': function(t){
                                var cmboDataset = Ext.getCmp('cmboBoundary');
                                var boundaryID = cmboDataset.getValue();

                                Ext.Ajax.request({
                                    url: remoteGetDataURL,
                                    params : {DatasetID : datasetID, BoundaryID : boundaryID, MeasuresID : measuresID},
                                    method : 'POST',
                                    success: function(resp) {
                                        console.log('success!');
                                        var responseData = Ext.decode(resp.responseText);
                                        console.log(responseData);

//                                        regionStore.loadData(responseData);

                                        var csvarea = Ext.getCmp('jsonarea');
                                        csvarea.setValue(resp.responseText);

                                    },
                                    failure: function(resp) {
                                        console.log('failure!');
                                    }
                                });
                            }
                        },
                        mode: 'local',
                        store : boundaryStore
                    },
                    {
                        fieldLabel      : 'csvarea',
                        id              : 'jsonarea',
                        name            : 'csv',
                        xtype           : 'textarea',
                        autoScroll      : true,
                        height          : 260,
                        anchor: '100%'

                    }
                ]
            }
        ];
        GeoPortal.Forms.RemoteData.superclass.initComponent.call(this);
    },

    doMap : function(datasetID) {


        var url = "https://www.nomisweb.co.uk/api/v01/dataset/" + datasetID +".data.kml?geography=TYPE279";


        var req = new XMLHttpRequest();

        getData();

        function getData(){
            if(req)
            {
                req.open('GET', url, true);
                req.onreadystatechange = handler;
                req.send();
            }
        }

        function handler(evt)
        {
            if (req.readyState == 4)
            {
                if (req.status == 200)
                {
                    var response = eval('(' + req.responseText + ')');
                    window.alert('Got data, value is ' + response.obs[0].obs_value.value);
                }
                else window.alert('Error with call');
            }
        }

        function GetFeaturesFromKMLString (strKML) {
            var format = new OpenLayers.Format.KML({
                'internalProjection': myMapObject.baseLayer.projection,
                'externalProjection': new OpenLayers.Projection("EPSG:4326")
            });
            return format.read(strKML);
        };

        var newLayer = new OpenLayers.Layer.Vector("Vectors", {
            projection: new OpenLayers.Projection("EPSG:4326"),
            strategies: [new OpenLayers.Strategy.Fixed()],
            protocol: new OpenLayers.Protocol.Script({
//                                                NM_621_1
                url: "https://www.nomisweb.co.uk/api/v01/dataset/" + datasetID +".data.kml?geography=TYPE279",
//                                                params: {
//                                                    q: "select * from xml where url='http://www.topografix.com/fells_loop.gpx'"
//                                                },
                format: new OpenLayers.Format.KML(),

                parseFeatures: function(data) {
                    return this.format.read(data.results[0]);
                }
            }),
            eventListeners: {
                "featuresadded": function () {
                    this.map.zoomToExtent(this.getDataExtent());
                }
            }
        })
        map.addLayer(newLayer);



    }
});

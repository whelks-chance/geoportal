GeoPortal.Windows.WMS = Ext.extend(Ext.Window, {
    width: 570,
    resizable: false,
    height: Ext.getBody().getViewSize().height * 0.8,
    title: 'Add WMS from official data resource',
    initComponent: function () {


        GeoExt.data.YahooWMSCapabilitiesReader = Ext.extend(GeoExt.data.WMSCapabilitiesReader, {
            readRecords: function (data) {
                data = data.results.toString();
                return GeoExt.data.YahooWMSCapabilitiesReader.superclass.readRecords.call(this, data);
            }
        });


        var wmsUrl = "http://inspire.wales.gov.uk:80/maps/wms?request=getCapabilities&version=1.3.0";
        var url = "http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20xml%20where%20url%3D'http%3A%2F%2Finspire.wales.gov.uk%3A80%2Fmaps%2Fwms%3Frequest%3DgetCapabilities%26version%3D1.3.0'&diagnostics=true";

        var wms_store = new Ext.data.Store({
            id: 'WG_wms_store',
            fields: ['layer', 'name', 'title', 'abstract', 'Added'],
            reader: new GeoExt.data.YahooWMSCapabilitiesReader(),
            proxy: new Ext.data.ScriptTagProxy({
                url: url,
                disableCaching: false,
                method: "GET"
            })
        });


        wms_store.load();

        wms_store.addField({ name: 'Added', defaultValue: false });

        Ext.applyIf(this, {
            items: [
                {
                    xtype: 'tabpanel',
                    activeTab: 0,
                    height: this.height - 60,
                    tabPosition: 'bottom',
                    items: [
                        {
                            xtype: 'panel',
                            title: 'Welsh Government',
                            height: this.height - 60,
                            viewConfig: { forceFit: true },
                            autoExpandColumn: 'Abstract',

                            items: [
                                {
                                    xtype: 'grid',
                                    store: wms_store,
                                    id: 'wg',
                                    autoScroll: true,
                                    width: 550,
                                    loadMask: true,
                                    height: this.height - 70,
                                    columns: [
                                        { header: "Name", dataIndex: "name", sortable: true, width: 100 },
                                        { header: "Title", dataIndex: "title", sortable: true, width: 200 },
                                        { header: "Abstract", dataIndex: "abstract", width: 150 },
                                        { xtype: 'actioncolumn', defaultValue: false, items: [
																	{
																	    icon: 'images/silk/cross.png',                // Use a URL in the icon config
																	    tooltip: 'Add to Map',
																	    handler: function (grid, rowIndex, colIndex) {
																	        var store = grid.getStore();
																	        var rec = store.getAt(rowIndex);
																	        if (rec.data.Added == false) {

																	            grid.selModel.selectRow(rowIndex, true);
																	            rec.set("Added", true);
																	            rec.commit();

																	        }
																	        else {
																	            grid.selModel.deselectRow(rowIndex);
																	            rec.set("Added", false);
																	            rec.commit();


																	        }

																	    },
																	    getClass: function (value, metaData, record) {

																	        if (record.data.Added == false) {
																	            this.items[0].icon = 'images/silk/cross.png';
																	            this.items[0].tooltip = 'Add to Map';

																	        };
																	        if (record.data.Added == true) {
																	            this.items[0].icon = 'images/silk/tick.png';
																	            this.items[0].tooltip = 'Remove from Map';

																	        };


																	    }
																	}],
                                            dataIndex: 'Added',
                                            header: 'Add to Map?',
                                            sortable: true,
                                            editable: true,
                                            width: 80
                                        }]
                                }
                            ]
                        }
                    ]
                }
            ],
            bbar: {
                xtype: 'toolbar',
                items: [
                    {
                        xtype: 'tbfill'
                    },
                    {
                        xtype: 'button',
                        text: 'Add WMS',
                        handler: function () {
                          Ext.getCmp('wg').getSelectionModel().each(function (record) {
                              addWMS(record.data.name, record.data.title, 'E01B4C', record);
                              
                            });


                        }
                    }
                ]
            }
        });

        GeoPortal.Windows.WMS.superclass.initComponent.call(this);
    }
});
//**Setup Map Panel using GeoExt **//

GeoPortal.Panels.TreePanel = Ext.extend(Ext.Panel, {
    autoHeight: true,
    initComponent: function () {
        this.items = this.CreateItems();
        GeoPortal.Panels.TreePanel.superclass.initComponent.call(this);
    },
    CreateItems: function () {



        var rMenu = new Ext.menu.Menu({LayerNode: null,
            items: [new Ext.menu.Item({text: 'Delete',
                icon: 'images/silk/delete.png',
                handler:   function (node, e) {
                    var selNode = rMenu.LayerNode;
                    for (var i = 0; i < map.layers.length; i++) {
                        if(map.layers[i].group == selNode.attributes.id){

                            var layer = map.layers[i];
                            map.removeLayer(layer);
                            selNode.remove(true);

                        }
                    }


                }}),
                new Ext.menu.Item({text: 'MetaData', icon: 'images/silk/layout.png',
                    handler: function(node, e){
                        var selNode = rMenu.LayerNode;
                        if (selNode.parentNode.id == 'Survey Data'){

                            var SID = selNode.attributes.SID;
                            var metaWindow = new GeoPortal.Windows.MetaData({ SID: SID });
                            var tabPs = Ext.getCmp("tabMeta");
                            tabPs.remove('tabQuestion', true);
                            tabPs.remove('tabResponse', true);
                            metaWindow.doLayout();
                            Ext.getCmp("frmSurvey").getForm().load({ url: SmetaURL, waitMsg: 'Loading.......', method: 'POST', params: { SID: SID} });
                            //Ext.getCmp("frmResponse").getForm().load({ url: RmetaURL, waitMsg: 'Loading.......', method: 'POST', params: { QID: QID} });
                            Ext.getCmp("frmDC").getForm().load({ url: DCmetaURL, waitMsg: 'Loading.......', method: 'POST', sucess: metaWindow.show(), params: { SID: SID} });

                        }else{

                            var SID = selNode.attributes.SID;
                            var metaWindow = new GeoPortal.Windows.QualMetaData({ SID: SID });
                            Ext.getCmp("frmQDC").getForm().load({ url: QDCmetaURL, waitMsg: 'Loading.......', method: 'POST', sucess: metaWindow.show(), params: { SID: SID} });


                        };

                    }
                }),
                new Ext.menu.Item({text: 'Legend',
                    icon: 'images/silk/page_paintbrush.png',
                    handler:   function (node, e) {
                        var selNode = rMenu.LayerNode;

                        var LegendWin = new Ext.Window({
                            title: 'Legend for ' + selNode.attributes.text,
                            width: 250,
                            x:10,
                            y: 430,
                            resizable: false,
                            items:[new Ext.BoxComponent({
                                html: '<div style="background-color: #FFFFFF; width: 300, height: 400"><img src="' + selNode.attributes.legendURL + '" /></div>'
                            })]
                        });


                        LegendWin.show();

                    }

                }),
                new Ext.menu.Item({text: 'Colour', icon: 'images/silk/color_wheel.png', disabled: true}),
                new Ext.menu.Item({text: 'Rename', icon: 'images/silk/textfield_rename.png', disabled: true})]});

        var wmsMenu = new Ext.menu.Menu({LayerNode: null,
            items: [new Ext.menu.Item({text: 'Add WMS from Official Resource',
                icon: 'images/silk/add.png',
                handler:function (node, e) {
                    var win =   new GeoPortal.Windows.WMS();

                    win.show();
                }
            }),
                new Ext.menu.Item({text: 'Add WMS from URL',
                    icon: 'images/silk/add.png',
                    handler:function (node, e) {


                    }
                })
            ]
        });


        var PanelItems = []
        // create our own layer node UI class, using the TreeNodeUIEventMixin
        //var LayerNodeUI = Ext.extend(GeoExt.tree.LayerNodeUI, new GeoExt.tree.TreeNodeUIEventMixin({ icon: "../images/silk/map_go.png" }));
        var layerList = new Ext.tree.TreeNode({rootVisible: false});

        var baseLayerStore = new GeoExt.data.LayerStore({
            map: map,
            initDir: 0,
            layers: getBaseLayers()
        });

        var overLaysStore = new GeoExt.data.LayerStore({
            map: map,
            initDir: 0,
            layers: getOverLays()
        });

        var boundaryStore = new GeoExt.data.LayerStore({
            map: map,
            initDir: 0,
            layers: getBoundaries()
        });

        var otherDataStore = new GeoExt.data.LayerStore({
            map: map,
            initDir: 0,
            layers: getOtherData()
        })

        var quants = new GeoExt.data.LayerStore({
            id: 'quantsStore',
            map: map,
            initDir: 0,
            layers: getQuantsData()
        });

        var qual = new GeoExt.data.LayerStore({
            id: 'qualsStore',
            map: map,
            initDir: 0,
            layers: getQualData()
        });

        var admin = new GeoExt.data.LayerStore({
            id: 'adminStore',
            map: map,
            initDir: 0,
            layers: []
        });

        var grey = new GeoExt.data.LayerStore({
            id: 'greyStore',
            map: map,
            initDir: 0,
            layers: []
        });

        layerList.appendChild(new GeoExt.tree.BaseLayerContainer({
            text: "Base Mapping",
            //layers: map.getLayers(),
            lines: true,
            icon: "images/silk/world.png",
            leaf: false,
            expanded: true,
            loader:{
                baseAttrs:{
                    radioGroup: "BaseLayers",
                    icon: "images/geosilk/bullet_map.png"
                }
            }
        })
        );

        var myLayers = new GeoExt.tree.OverlayLayerContainer({rootVisible: false,
            loader: {
                filter: function(record) {
                    return record.get("layer").attribution == 'myData';
                }
            },
            leaf: false,  icon: " images/silk/folder_user.png",text: "My Data", expanded: true, lines: true, id: 'myMapsNode'});

        layerList.appendChild(myLayers);
        var resultsLayers = new Ext.tree.TreeNode({rootVisible: false, leaf: false,  icon: " images/silk/layers.png",text: "Search Results", expanded: true, lines: true, id: 'resultsNode'});


        resultsLayers.appendChild(new Ext.tree.TreeNode({
            text: "Survey Data",
            id: "Survey Data",
            rootVisible: true,
            lines: true,
            icon: "images/silk/table_multiple.png",
            leaf: false,
            expanded: true,
            loader: {
                filter: function(record) {
                    return record.get("layer").attribution == "QuantData";
                }
            }

        })
        );

        resultsLayers.appendChild(new GeoExt.tree.OverlayLayerContainer({
            text: "Qualitative Data",
            layerStore: qual,
            lines: false,
            icon: "images/silk/group.png",
            leaf: true,
            expanded: true,
            loader:{
                baseAttrs:{
                    radioGroup: "QuantsLayers",
                    icon: "images/silk/group_add.png"
                }  ,
                filter: function(record) {
                    return record.get("layer").attribution == 'qualData';
                }
            }
        })
        );

        resultsLayers.appendChild(new GeoExt.tree.OverlayLayerContainer({
            text: "Admin Data",
            layerStore: admin,
            lines: false,
            icon: "images/silk/pencil.png",
            leaf: true,
            expanded: true,
            loader:{
                filter: function(record) {
                    return record.get("layer").attribution == 'adminData';
                },
                baseAttrs:{
                    radioGroup: "AdminLayers",
                    icon: "images/silk/pencil_add.png"
                }
            }
        })
        );
        resultsLayers.appendChild(new GeoExt.tree.OverlayLayerContainer({
            text: "Grey Data",
            nodeType: 'gx_overlaylayercontainer',
            layerStore: grey,
            lines: false,
            icon: "images/silk/page_white_stack.png",
            leaf: true,
            expanded: true,
            loader:{
                filter: function(record) {
                    return record.get("layer").attribution == 'greyData';
                },
                baseAttrs:{
                    radioGroup: "GreyLayers",
                    icon: "images/silk/page_white_text.png"
                }
            }
        })
        );


        layerList.appendChild(resultsLayers);

        // set boundary WMS layers

        var boundaries = new OpenLayers.Layer.WMS(
            //"Boundary Data", "http://23.21.162.107:8081/geoserver/WISERD/wms",
//                    "Boundary Data", "http://131.251.172.95:7000/geoserver/WISERD/wms",
            "Boundary Data", geoserverWMS,
            {
                LAYERS: ['Unitary Authorities', 'Aberystwyth Locality', 'Bangor Locality', 'Heads of the Valleys'  ] ,
                format: 'image/png',
                transparent: 'TRUE',
                opacity: 0.5,
                attribution: 'BoundaryData'
            },
            { singleTile: true, ratio: 1, isBaseLayer: false,  visibility: false  }
        );

        map.addLayer(boundaries);


        layerList.appendChild(new GeoExt.tree.LayerNode({
            text: "Boundary Data",
            layer: boundaries,
            icon: "images/silk/pictures.png",
            leaf: false,
            checked: false,
            expanded: true,
            listeners:{ "contextmenu": function(node, e){
                var xy = e.getXY();
                wmsMenu.LayerNode = node;
                wmsMenu.showAt(xy);
            }
            }   ,
            loader:
            {
                param: "LAYERS",
                baseAttrs: {
                    radioGroup: "BoundaryData",
                    draggable: false,
                    //icon: "http://23.21.162.107:8081/geoserver/WISERD/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=30&LAYER=WISERD:Aberystwyth Locality"
//                    icon: "http://131.251.172.95:7000/geoserver/WISERD/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=30&LAYER=WISERD:Aberystwyth Locality"
                    icon: geoserverWMS + "?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=30&LAYER=WISERD:Aberystwyth Locality"
                }
            }
        })
        );


        layerList.appendChild(new GeoExt.tree.OverlayLayerContainer({
            text: "Other Data",
            layerStore: otherDataStore,
            icon: "images/silk/shape_group.png",
            leaf: false,
            expanded: true,
            loader:{
                //filter: function(record) {
                // return record.get("layer").attribution == 'otherData';
                //},
                baseAttrs: {
                    radioGroup: "Other Data",
                    draggable: true,
                    icon: "images/silk/vector.png"
                }
            }
        })
        );

        // create the tree with the configuration from above
        tree = new Ext.tree.TreePanel({
            id: 'TOCTP',
            border: false,
            split: true,
            animate: true,
            enableDD: true,
            containerScroll: true,
            useArrows: true,
            autoScroll: true,
            autoHeight: true,
            collapsible: false,
            collapseMode: "mini",
            bodyStyle: 'padding: 5px',
            height: Ext.getBody().getViewSize().height * 0.85,
            autoScroll: true,
            plugins: [
                new GeoExt.plugins.TreeNodeRadioButton({
                    listeners: {
                        "radiochange": function (node) {
                            alert(node.text + " is now the active layer.");
                        }
                    }
                })
            ],
            loader: new Ext.tree.TreeLoader({
                // applyLoader has to be set to false to not interfer with loaders
                // of nodes further down the tree hierarchy
                applyLoader:false
//                uiProviders: {
//                    "layernodeui": LayerNodeUI
//                }
            }),
            root: layerList,
            listeners: {
                "radiochange": function (node) {
                    alert(node.layer.name + " is now the the active layer.");
                },
                "contextmenu":  {fn: function(node, e) {
                    var xy = e.getXY();

                    if (node.attributes.leaf == true)
                    {
                        rMenu.LayerNode = node;
                        rMenu.showAt(xy);

                    }
                }}
            },
            rootVisible:false,
            lines: true
        });

        //add to panelarray 
        PanelItems.push(tree);

        return PanelItems;



    }




});
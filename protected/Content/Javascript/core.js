         var mapPanel;
         var MapOpts;
         var map;
         var geographic = new OpenLayers.Projection("EPSG:4326");
         var mercator = new OpenLayers.Projection("EPSG:900913");
         var OSGB = new OpenLayers.Projection("EPSG:27700");
         OpenLayers.IMAGE_RELOAD_ATTEMPTS = 3;
         OpenLayers.Util.onImageLoadErrorColor = "transparent";
         
              
         
         
         function SetupMap() {
             Ext.BLANK_IMAGE_URL = '../images/s.gif';
             Ext.QuickTips.init();
           
             var maxExtent = new OpenLayers.Bounds(-5.375, 51.411, -2.667, 53.537);
             //maxExtent.transform(geographic, mercator);
             var options = {
                 //controls: [],
                 projection: mercator,
                 //displayProjection: mercator,
                 resolutions: [1763.889, 352.778, 176.389, 88.194, 35.278, 26.458, 17.639, 8.819, 3.528, 1.764, 0.882, 0.441],
                 units: "m",
                 //fractionalZoom: true,
                 numZoomLevels: 15,
                 //maxResolution: 156543.0339,
                 //cache: cache,
                 //theme: null,
                 maxExtent: maxExtent
             };

             //OpenStreetMap
             osm = new OpenLayers.Layer.OSM("Open Street Map");


             //shaded = new OpenLayers.Layer.VirtualEarth("Shaded", { type: VEMapStyle.Shaded, sphericalMercator: true });

             //add navigation controls

             //nav = new OpenLayers.Control.Navigation();



             map = new OpenLayers.Map(options);

             //map.addControl(nav);

             //Create Toolbar
            vector = new OpenLayers.Layer.Vector("vector");

             var ctrl, toolbarItems = [], action, actions = {};

             // ZoomToMaxExtent control, a "button" control
             action = new GeoExt.Action({
                 control: new OpenLayers.Control.ZoomToMaxExtent(),
                 map: map,
                 icon: '../images/silk/world.png',
                 tooltip: "Zoom to max extent"
             });
             actions["max_extent"] = action;
             toolbarItems.push(action);
            


// Navigation controls
             // in the same toggle group
             action = new GeoExt.Action({
                 icon: '../images/geosilk/pan.png',
                 control: new OpenLayers.Control.Navigation(),
                 map: map,
                 // button options
                 toggleGroup: "draw",
                 allowDepress: false,
                 pressed: true,
                 tooltip: "navigate",
                 // check item options
                 group: "draw",
                 checked: true
             });
             actions["nav"] = action;
             toolbarItems.push(action);

             // Navigation history - two "button" controls
             ctrl = new OpenLayers.Control.NavigationHistory();
             map.addControl(ctrl);

             action = new GeoExt.Action({
                 icon: '../images/silk/arrow_left.png',
                 control: ctrl.previous,
                 disabled: true,
                 tooltip: "previous view"
             });
             actions["previous"] = action;
             toolbarItems.push(action);

             action = new GeoExt.Action({
                 icon: '../images/silk/arrow_right.png',
                 control: ctrl.next,
                 disabled: true,
                 tooltip: "next view"
             });
             actions["next"] = action;
             toolbarItems.push(action);
             toolbarItems.push("-");


             toolbarItems.push('-');

             //draw button
             btnDraw = new Ext.Button({
                 icon: '../images/silk/shape_handles.png',
                 tooltip: "Annotate this Map",
                 handler: OnBtnClick,
                 toggleGroup: "tools",
                 group: "tools"
             });

             toolbarItems.push(btnDraw);

                          

           // SelectFeature control, a "toggle" control
             action = new GeoExt.Action({
                 icon: '../images/silk/information.png',
                 control: new OpenLayers.Control.SelectFeature(vector, {
                     type: OpenLayers.Control.TYPE_TOGGLE,
                     hover: true
                 }),
                 map: map,
                 // button options
                 enableToggle: true,
                 tooltip: "feature information"
             });
             actions["select"] = action;
             toolbarItems.push(action);


             locData = new Ext.Button({
                 icon: '../images/silk/find.png',
                 tooltip: "Find..",
                 handler: OnFindClick,
                 toggleGroup: "tools",
                 group: "tools"
             });
             toolbarItems.push(locData);

             btnMeasure = new Ext.Button({
                 icon: '../images/geosilk/ruler_square.png',
                 tooltip: "Find..",
                 handler: OnMeasureClick,
                 toggleGroup: "tools",
                 group: "tools"
             });

             toolbarItems.push(btnMeasure);
             toolbarItems.push("-");
             toolbarItems.push("->");



              //Print,Save etc

             toolbarItems.push("-");

             btnFS = new Ext.Button({
                 icon: '../images/silk/arrow_out.png',
                 tooltip: "View Full Screen..",
                 toggleGroup: "FS",
                 group: "FS",
                 handler: OnFSClick                 
             });
              toolbarItems.push(btnFS);


             btnPrint = new Ext.Button({
                 icon: '../images/silk/printer.png',
                 tooltip: "Print Current View...",
                 handler: OnPrintClick                 
             });
              toolbarItems.push(btnPrint);


              btnSave = new Ext.Button({
                 icon: '../images/silk/disk.png',
                 tooltip: "Save View...",
                 handler: OnSaveClick                 
             });
              toolbarItems.push(btnSave);




             //Map Options
             toolbarItems.push("-");
             btnMapOptions = new Ext.Button({
                 icon: '../images/silk/map_edit.png',
                 tooltip: "Map Settings",
                 handler: OnMapSettingsClick,
                 
             });

             toolbarItems.push(btnMapOptions);

                       
        
           //feedback

              toolbarItems.push("-");
             btnBug = new Ext.Button({
                 icon: '../images/silk/bug.png',
                 tooltip: "Report an issue...",
                 handler: OnBugClick                 
             });
              toolbarItems.push(btnBug);

             //login
             toolbarItems.push("-");
             btnLogin = new Ext.Button({
                 icon: '../images/silk/key.png',
                 tooltip: "Login",
                 handler: OnLoginClick                 
             });
             toolbarItems.push(btnLogin);

             btnMA = new Ext.Button({
                 icon: '../images/silk/user_green.png',
                 tooltip: "My Account",
                 handler: OnMAClick
               });
             toolbarItems.push(btnMA);
            //spacer
             toolbarItems.push({xtype: 'tbspacer', width: 20});

             new Ext.Viewport({
                 layout: "border",
                 items: [{
                     region: "center",
                     id: "mappanel",
                     //title: "Map",
                     xtype: "gx_mappanel",
                     map: map,
                     tbar: toolbarItems,
                     items: [{
                         xtype: "gx_zoomslider",
                         vertical: true,
                         height: 300,
                         x: 10,
                         y: 20,
                         plugins: new GeoExt.ZoomSliderTip()
                     }],
                     layers: [osm, vector],
                     extent: maxExtent,
                     split: true
                 }, {
                     region: "west",
                     id: 'SP',
                     contentEl: "toc",
                     width: 300,
                     minWidth: 250,
                     collapseMode: "mini",
                     split: true
                 }]
             });


             // create our own layer node UI class, using the TreeNodeUIEventMixin
             var LayerNodeUI = Ext.extend(GeoExt.tree.LayerNodeUI, new GeoExt.tree.TreeNodeUIEventMixin());

             // using OpenLayers.Format.JSON to create a nice formatted string of the
             // configuration for editing it in the UI
             var treeConfig = new OpenLayers.Format.JSON().write([{
                 nodeType: "gx_baselayercontainer"
             }, {
                 nodeType: "gx_overlaylayercontainer",
                 expanded: true,
                 // render the nodes inside this container with a radio button,
                 // and assign them the group "foo".
                 loader: {
                     baseAttrs: {
                         radioGroup: "foo",
                         uiProvider: "layernodeui"
                     }
                 }
             }
           ], true);

             // create the tree with the configuration from above
             tree = new Ext.tree.TreePanel({
                 border: true,
                 renderTo: "toc",
                 title: "Layers",
                 split: true,
                 collapsible: true,
                 collapseMode: "mini",
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
                     applyLoader: false,
                     uiProviders: {
                         "layernodeui": LayerNodeUI
                     }
                 }),
                 root: {
                     nodeType: "async",
                     // the children property of an Ext.tree.AsyncTreeNode is used to
                     // provide an initial set of layer nodes. We use the treeConfig
                     // from above, that we created with OpenLayers.Format.JSON.write.
                     children: Ext.decode(treeConfig)
                 },
                 listeners: {
                     "radiochange": function (node) {
                         alert(node.layer.name + " is now the the active layer.");
                     }
                 },
                 rootVisible: false,
                 lines: true
             });


             var centPoint = new OpenLayers.LonLat(-3.76622, 52.33);
             centPoint.transform(geographic, mercator);
             map.setCenter(centPoint, 7);

             mapPanel = Ext.getCmp("mappanel");

            setTimeout(function(){
             Ext.get('loading-mask').remove();
             Ext.get('loading').fadeOut({remove: true})
             } , 250);
         };


         function OnBtnClick(btn) { 
         
         AnnoWindow = Ext.extend(AnnotateMapUi, {
         initComponent: function () {
         AnnoWindow.superclass.initComponent.call(this);
            }
            });

            Ext.QuickTips.init();
            MapOpts= new AnnoWindow({ modal: false});
            MapOpts.show();        
         
         
          }

         function OnFindClick(btn) { 
         
         findToolsWin = Ext.extend(FindToolUi, {
         initComponent: function () {
         findToolsWin.superclass.initComponent.call(this);
            }
            });
         
         findTools = new findToolsWin();
         findTools.show();
         
         
         //Ext.Msg.alert('Button Click', 'Find button clicked');
         
         }

         function OnMeasureClick(btn) { Ext.Msg.alert('Button Click', 'Measure button clicked'); }

         function OnLoginClick(btn) { Ext.Msg.alert('Button Click', 'Login button clicked'); }

         function OnMAClick(btn) { 
         
           


          MAWindow = Ext.extend(MyAccountUi, {
            initComponent: function() {
               MAWindow.superclass.initComponent.call(this);
            }
        });
         
            Ext.QuickTips.init();
            MA= new MAWindow();
            MA.show();     
         
         //Ext.Msg.alert('Button Click', 'MA button clicked'); 
         }

         

         function OnBugClick(btn) { Ext.Msg.alert('Button Click', 'Report a bug button clicked'); }
     
         function OnPrintClick(btn) { Ext.Msg.alert('Button Click', 'Print button clicked'); }

        function OnSaveClick(btn) { Ext.Msg.alert('Button Click', 'Save button clicked'); }
     
        function OnFSClick(btn) { 
        
            //get SidePanel by ID using Ext find method
            var SP = Ext.getCmp('SP');       
            
            //check to see if visible

            if(SP.isVisible()) {
                SP.collapse();
            }else{
                SP.expand();
            }
        
         }

         AnnotateMapUi
     
function OnMapSettingsClick(btn) {  

MyWindow = Ext.extend(MyWindowUi, {
    initComponent: function () {
        MyWindow.superclass.initComponent.call(this);
    }
});

 Ext.QuickTips.init();
 MapOpts= new MyWindow();
 MapOpts.show();
   
}
 
 
 SetupMap();
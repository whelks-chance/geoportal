var map = CreateMap();
var resultsLayers = [];
var placeSearch;
var styledMap;
var layerRuler;
var anno;
var click;
var BufferClick;
var drawPoly;
var annotataMap = false;
var AnnoWin;
var SearchLayer;
var InfoClick;
var infoLayers = [];
//setup  transform options
var geographic = new OpenLayers.Projection("EPSG:4326");
var mercator = new OpenLayers.Projection("EPSG:900913");
var OSGB = new OpenLayers.Projection("EPSG:27700"); 

function CreateMap() { 

	//Set up variables
	
	//var maxExtent = new OpenLayers.Bounds(-3.375, 45.411, 2.667, 57.537);
	 var geographic = new OpenLayers.Projection("EPSG:4326");
	var mercator = new OpenLayers.Projection("EPSG:900913");
	var OSGB = new OpenLayers.Projection("EPSG:27700");
	var maxExtent = new OpenLayers.Bounds (0,0, 500000, 500000);

	OpenLayers.IMAGE_RELOAD_ATTEMPTS = 0;
	OpenLayers.Util.onImageLoadErrorColor = "transparent";
	maxExtent= maxExtent.transform(OSGB, geographic);

   
			 

	//Set map Options
	var options = {
		allOverlays: false,
		projection: OSGB,
		resolutions: [1763.889,352.778,176.389,88.194,35.278,26.458,17.639,8.819,3.528,1.764,0.882,0.441],
		//resolutions: [1763.889, 352.778, 176.389, 88.194, 35.278, 26.458, 17.639, 8.819, 3.528, 1.764, 0.882, 0.441],
		units: "m",
		numZoomLevels: 25,
		maxExtent: maxExtent,
	   // controls: [new OpenLayers.Control.Navigation(),
		 controls:[
							new OpenLayers.Control.Navigation({zoomWheelEnabled:false}),
							new OpenLayers.Control.PanPanel(),
							new OpenLayers.Control.ZoomPanel()],
		theme:null,
		//maxResolution:156543.0339
			 
	};

	map = new OpenLayers.Map(options);



	map.events.register('zoomend', null, function () {

		//   alert("zoom finished");
		var slider = Ext.getCmp('zoomSlider');

		Ext.fly('txtSBCurrentZoom').update(slider.getZoom());

	});

	//click handler for measure and anno tools

	OpenLayers.Control.Click = OpenLayers.Class(OpenLayers.Control, {
		defaultHandlerOptions: {
			'single': true,
			'double': false,
			'pixelTolerance': 0,
			'stopSingle': false,
			'stopDouble': false
		},

		initialize: function (options) {
			this.handlerOptions = OpenLayers.Util.extend(
						{}, this.defaultHandlerOptions
					);
			OpenLayers.Control.prototype.initialize.apply(
						this, arguments
					);
			this.handler = new OpenLayers.Handler.Click(
						this, {
							'click': this.trigger
						}, this.handlerOptions
					);
		},

		trigger: function (evt) {
			if (annotateMap == true) {

				if (AnnoWin == null) {
					click.deactivate();
					createColorMenus();
					AnnoWin = new GeoPortal.Windows.AnnoWin({ evt: evt });
					AnnoWin.show();
				} else {
					AnnoWin.destroy();
					createColorMenus();
					AnnoWin = new GeoPortal.Windows.AnnoWin({ evt: evt });
					AnnoWin.show();
				}
			} else {
				var lonlat = map.getLonLatFromViewPortPx(evt.xy);
				alert("You clicked near " + lonlat.lat + " N, " + lonlat.lon + " E");
			}
		}
	});


	click = new OpenLayers.Control.Click();
	map.addControl(click);


	//Click Controls Buffer

	 OpenLayers.Control.BufferClick = OpenLayers.Class(OpenLayers.Control, {
		defaultHandlerOptions: {
			'single': true,
			'double': false,
			'pixelTolerance': 0,
			'stopSingle': false,
			'stopDouble': true
		},

		initialize: function (options) {
			this.handlerOptions = OpenLayers.Util.extend(
						{}, this.defaultHandlerOptions
					);
			OpenLayers.Control.prototype.initialize.apply(
						this, arguments
					);
			this.handler = new OpenLayers.Handler.Click(
						this, {
							'click': this.trigger
						}, this.handlerOptions
					);
		},

		trigger: function (evt) {
			var bufferWin = new GeoPortal.Windows.Buffer({evt: evt});
			bufferWin.show ();
			this.deactivate()
			   } 
		});
		 BufferClick = new OpenLayers.Control.BufferClick();
		map.addControl(BufferClick);

	 //info Click
	   OpenLayers.Control.InfoClick = OpenLayers.Class(OpenLayers.Control, {
		defaultHandlerOptions: {
			'single': true,
			'double': false,
			'pixelTolerance': 0,
			'stopSingle': false,
			'stopDouble': true
		},

		initialize: function (options) {
			this.handlerOptions = OpenLayers.Util.extend(
						{}, this.defaultHandlerOptions
					);
			OpenLayers.Control.prototype.initialize.apply(
						this, arguments
					);
			this.handler = new OpenLayers.Handler.Click(
						this, {
							'click': this.trigger
						}, this.handlerOptions
					);
		},

		trigger: function (e) {

		var identifyLayers = [];

		for (var i = 0; i < infoLayers.length; i++) {
				var node = infoLayers[i];
				 var table = new Object();
				  table.tableName = node.attributes.text;
				  table.tableID = node.attributes.LayerID;

				  identifyLayers.push(table);
			};

   

		//get reference to TOC and cycle throught to get Active Layers
 
		var lonlat = map.getLonLatFromPixel(e.xy);



   var identifyResults=  Ext.Ajax.request({
   url: 'SpatialData/getFeatureInfo',
   params: { 
		lat: lonlat.lat,
		lon: lonlat.lon,
		tableIDs: JSON.stringify(identifyLayers)
		 },
   callback: function(){
		   
		   var identified = Ext.util.JSON.decode(identifyResults.conn.responseText);
			var tabItems = [];
			var resultsWin;
			  

			for (var i = 0; i < identified.length; i++) {
				var item = identified[i];

			var tabPanel = {
			  title: item.tableName,
			  html: item.Html,
			  height: 250
			};
			  
			  tabItems.push(tabPanel); 
			}

			var TP = new Ext.TabPanel({
			   activeTab: 0,
			   deferredRender:false,
			   tabPosition: 'bottom',
			   items: tabItems
			 });
		   
			var win = new GeoExt.Popup({
			title: 'Results for identify at ' + Math.round(lonlat.lon, 0) + ',' + Math.round(lonlat.lat,0),
			items: [TP],
			width: 300,
			anchored: true,
			anchorPosition: 'top-right',
			location: e.xy,
			map: map
			});

			win.show();

   }
});


//*****GetFeatureInfo Function not working for SQL View Layers******

//			var layer = map.layers[5];

//			var queryLayers = [];

//			for (var i = 0; i < map.layers.length; i++) {
//				if (map.layers[i].CLASS_NAME == "OpenLayers.Layer.WMS"){
//						 if (map.layers[i].name != "Edina OS OpenData WMS"){
//                            var qLayer = map.layers[i]; 
//                            //if (qLayer.name != 'Boundary Data'){
//							  queryLayers.push(qLayer.params.LAYERS);
//						    //}   
//                         }
//				}
//			};
//	 
//			var url =  layer.getFullRequestString(
//					{
//						REQUEST: "GetFeatureInfo",
//						EXCEPTIONS: "application/vnd.ogc.se_xml",
//						BBOX: map.getExtent().toBBOX(),
//						X: e.xy.x,
//						Y: e.xy.y,
//						INFO_FORMAT: 'text/html',
//						LAYERS: queryLayers,
//						QUERY_LAYERS: queryLayers,
//						FEATURE_COUNT: 50,
//						WIDTH: map.size.w,
//						HEIGHT: map.size.h,
//                        queryVisible: true//,
//                        //viewparams: layer.params.VIEWPARAMS
//					},
//					"http://localhost:5432/geoserver/wms"
//				);


//                
//				   
//				 var response;  
//				 
//               yqlURL ='http://query.yahooapis.com/v1/public/yql?q=' + "select * from html where url='" + encodeURIComponent(url) + "'&format=html";
//                 
//                   
//				OpenLayers.loadURL(yqlURL, {}, response, function(response){
//					
//						 if (Ext.getCmp('fPopUp')){
//						  var popup = Ext.getCmp('fPopUp');
//						  popup.setPosition(e.xy.x, e.xy.y);
//						

//					}else{     
//					var popup = new GeoExt.Popup({         //utilizing the GeoExt popup functionality
//						title: "Feature Information",
//                        height: 100,
//						autoScroll: true,
//						map: map,
//						location: map.getLonLatFromPixel(e.xy),
//						html: response.responseText
//					})

//				 
//					popup.show();


//					};

					
					
					
//					}, null
//					);
				
	 
				  
				  //OpenLayers.Event.stop(e);
			   } 
		});
		InfoClick = new OpenLayers.Control.InfoClick();
		map.addControl(InfoClick);



	return map;
}



function getBaseLayers() {
	var baseLayers = [];
	for (var i = 0; i < map.layers.length; i++) {
		if (map.layers[i].isBaseLayer) {
			baseLayers.push(map.layers[i]);

		}
	}
	return baseLayers;

};

function getOverLays() {
	var overlays = [];
	for (var i = 0; i < map.layers.length; i++) {
		if (map.layers[i].attribution == 'Overlays' ) {
			overlays.push(map.layers[i]);

		}
	}
	return overlays;

};

function getBoundaries() {
	var boundaries = [];
	for (var i = 0; i < map.layers.length; i++) {
		if (map.layers[i].attribution == 'BoundaryData') {
			boundaries.push(map.layers[i]);

		}
	}
	return boundaries;

};

function getOtherData() {
	var otherLayers = [];
	for (var i = 0; i < map.layers.length; i++) {
		if (map.layers[i].attribution == 'otherData') {
			otherLayers.push(map.layers[i]);

		}
	}
	return otherLayers;

};


function getQuantsData() {
	var QuantsData = [];
	for (var i = 0; i < map.layers.length; i++) {
		if (map.layers[i].attribution == 'QuantsData') {
			QuantsData.push(map.layers[i]);

		}

	}
	return QuantsData;               
}

function getQualData() {
	var QualData = [];
	for (var i = 0; i < map.layers.length; i++) {
		if (map.layers[i].attribution == 'QualData') {
			QuantsData.push(map.layers[i]);

		}

	}
	return QualData;
}




function GetLayers() {
	var layers = [];
	//Specify Layers
	var cloudmade = new OpenLayers.Layer.CloudMade("Topographic", { key: 'a34f274e196a4650bb8f49a42afd6ba3', styleId: 998 });
	//shaded = new OpenLayers.Layer.VirtualEarth("Shaded", { type: VEMapStyle.Shaded, sphericalMercator: true });

	var basemapping = new OpenLayers.Layer.WMS("Base Mapping", "http://j308-rf:8081/geoserver/wms",

	{
		layers: 'WISERD:OS StreetView',
		styles: '',
		attribution: 'otherData',
		srs: 'EPSG:4326',
		format: 'image/png',
		tiled: 'true',
		isBaseLayer: true

	});

		 

	 
	   SearchLayer = new OpenLayers.Layer.Vector("SearchLayer", {
		displayInLayerSwitcher: true,
		styleMap: bufferStyle,
		attribution: 'otherData'
	});

	SearchLayer.id = "SearchLayer";
	   
   
	

	//layer to hold measurements
	layerRuler = new OpenLayers.Layer.Vector("Measurements", {
		displayInLayerSwitcher: false
	});


   

   anno = new OpenLayers.Layer.Vector("annotations", {
	   displayInLayerSwitcher: false,
		styleMap: new OpenLayers.StyleMap({'default':{
					strokeColor: "${markerColour}",
					strokeOpacity: 1,
					strokeWidth: 1,
					fillColor: "${markerColour}",
					fillOpacity: 0.5,
					pointRadius: '${pointSize}',
					pointerEvents: "visiblePainted",
					label : "${text}",                    
					fontColor: "${textColour}",
					fontSize: '${fontSize}',
					fontFamily: "Courier New, monospace",
					fontWeight: "bolder",
					labelAlign: "rb",
					labelXOffset: "${xOffset}",
					labelYOffset: "${yOffset}"
				}})
		});

 anno.id = "annotations"


 //openStream setup


 //var bounds = new OpenLayers.Bounds(0, 0, 700000, 1300000);
 var apikey = "4e95289727bee8578fa6e49cc87a946339f625d11577c45fe7019b0f";
 var cache = "true";
 var attr = "Contains Ordnance Survey data. (c) Crown copyright and database right 2011. Data provided by Digimap OpenStream, an EDINA, University of Edinburgh Service.";
 var os_options = {
	 TOKEN: apikey,
	 srs: 'EPSG:4326',
	 format: "image/png",
	 layers: "osopendata",
	 cache: cache,
	 isBaseLayer: true,
	 width: 512,
	 height: 512
 };

 var osopenlayer = new OpenLayers.Layer.WMS(
		   "Edina OS OpenData WMS", "http://openstream.edina.ac.uk/openstream/wms",
		os_options,
		{ attribution: attr,
		opacity: 1,
		buffer: 1,
		displayOutsideMaxExtent: true,
		transitionEffect: "resize" });

 placeSearch = new OpenLayers.Layer.Markers("Placename/Postcode Search", { displayInLayerSwitcher: true, attribution: 'otherData' });
		placeSearch.id = "placeSearch";

		//push to layers array for map

		//layers.push(cloudmade);
		//layers.push(basemapping);
		//layers.push(boundaryData)
		layers.push(osopenlayer);
		layers.push(placeSearch);
		layers.push(anno);
		layers.push(layerRuler);
		layers.push(SearchLayer);
	   
  
		 


	return layers;
	
	};


   
GeoPortal.Toolbars.FindTools = Ext.extend(Ext.Window, {
	title: 'Spatial Search',
	id: 'tbFind',
	height: 25,
	resizable: false,
	width: 150,
	layout: 'fit',
	initComponent: function () {
		this.tbar = {
			xtype: 'toolbar',
			autoHeight: true,
			id: 'tlbFindTool',
			items: [
					  createPlaceAction(),
					  createBufferAction(),
					  createPolyAction(),
					  createRectangleAction(),
				 {
					xtype: 'button',
					tooltip: 'Find by geography',
					enableToggle: true,
					toggleGroup: 'findTools',
					icon: 'images/silk/world.png',
					id: 'btnFindGeography',
					disabled: true
				}
			]
		};
		GeoPortal.Toolbars.FindTools.superclass.initComponent.call(this);
	}
});


function createPolyAction() {

	polyfinished = function (feature) {
		var poly = new OpenLayers.Feature.Vector(feature, { 'name': 'Search Polygon', 'type': 'place' });
		SearchLayer.addFeatures(poly);
		   doSpatialSearch(feature);
		// alert('Poly Done');
	};
	var action = new GeoExt.Action({
		icon: 'images/geosilk/line_red.png',
		control: new OpenLayers.Control.DrawFeature(SearchLayer, OpenLayers.Handler.Polygon, { callbacks: { 'done': polyfinished} }),
		map: map,
		// button options
		toggleGroup: "findTools",
		allowDepress: false,
		tooltip: "Search by freehand polygon",
		// check item options
		group: "findTools"
	});

  

	return action;

};

function createPlaceAction() {

	var action = new GeoExt.Action({
		icon: 'images/silk/map_magnify.png',
		control: new OpenLayers.Control.Button(), //new OpenLayers.Control.DrawFeature(placeSearch, OpenLayers.Handler.Point),
		map: map,
		id:   'btnFindPlacename',
		// button options
		toggleGroup: "findTools",
		allowDepress: false,
		tooltip: "Find by location",
		// check item options
		group: "findTools",
		handler: function (toggled) {

						if (toggled) {
							if (Ext.getCmp('placeWin')) {
								Ext.getCmp('placeWin').show();
							} else {
							 var server = "http://unlock.edina.ac.uk/ws/nameSearch"
							 var jsonReader = new Ext.data.JsonReader({
									root: 'features'
								}, [{ name: 'name', mapping: 'properties.name' },
						{ name: 'country', mapping: 'properties.country' },
						{ name: 'featuretype', mapping: 'properties.featuretype' },
						{ name: 'county', mapping: 'properties.adminlevel1' },
						{ name: 'coords', mapping: 'properties.centroid'}])

								var ds = new Ext.data.Store({
									proxy: new Ext.data.ScriptTagProxy({
										url: server
									}),
									listeners: { beforeload: function () {
										var cmbo = Ext.getCmp('cmboGeoSearch');
										var name = cmbo.getValue();

										ds.baseParams = {
											name: name,
											q: '',
											limit: '',
											format: 'json', // Retrieve the results in JSON format
											maxRows: 10, // Limit the number of results to 10
											count: 'no'
										};
									}
									},
									reader: jsonReader
								});

								// Custom rendering Template
								var resultTpl = new Ext.XTemplate(
							'<tpl for="."><div class="search-item" style="font:normal 11px tahoma, arial, helvetica, sans-serif;padding:3px 10px 3px 10px;border:1px solid #fff;border-bottom:1px solid #eeeeee;white-space:normal;	color:#555;">',
								'<h3 style="display:block;	font:inherit;font-weight:bold;color:#222;><span style="float: right; font-weight:normal;margin:0 0 5px 5px;	width:100px;display:block;clear:none;>{name}, {county},  {country}</span></h3>',
								'{featuretype}',
							'</div></tpl>'
						);


								var search = new Ext.form.ComboBox({
									id: 'cmboGeoSearch',
									store: ds,
									autoDestroy: true,
									hideLabel: true,
									hideTrigger: true,
									fieldLabel: 'Search Text',
									triggerAction: 'all',
									displayField: 'name',
									typeAhead: false,
									loadingText: 'Searching...',
									pageSize: 7,
									//renderTo     : Ext.getBody(),
									anchor: '95%',
									tpl: resultTpl,
									selectOnFocus: true,
									forceSelection: true,
									loadingText: 'Searching...',
									allowBlank: false,
									submitValue: true,
									minChars: 2,
									listEmptyText: 'No matches found..',
									lastQuery: '',
									queryDelay: 200,
									emptyText: 'e.g. Cardiff or CF10BBQ',
									onSelect: function (record) {
										this.Value = record.data.coords;
										this.setValue(record.data.name + ", " + record.data.county + ", " + record.data.country);
										this.collapse();
										Ext.getCmp('btnNameSearch').setDisabled(false);
									},
									itemSelector: 'div.search-item',
									autoComplete: true,
									hiddenValue: 'coords'

								});

								var tip = new Ext.slider.Tip({
									getText: function (thumb) {
										return String.format('<b>{0}km</b>', thumb.value);
									}
								});

								var slider = new Ext.Slider({
									id: 'distSlider',
									fieldLabel: 'Distance',
									width: 200,
									autoDestroy: true,
									value: 5,
									increment: 1,
									minValue: 0,
									maxValue: 10,
									plugins: tip,
									listeners: {
										change: function (slider, newValue) {
											Ext.getCmp('currentRadius').update(newValue + ' km');
										}
									}
								});

								var label = new Ext.form.Label({
									id: 'currentRadius',
									text: '5 km'
								});



								var form = new Ext.form.FormPanel({ id: 'placeForm', items: [search, slider, label, {
									xtype: 'textfield',
									id: 'txtSpatialSearch',
									anchor: '100%',
									name: 'SpatKeywords',
									fieldLabel: 'Keywords',
									allowBlank: true,
									emptyText: 'Comma seperated keywords....'
								}], frame: true, autoDestroy: true
								});

								var SearchWin = new Ext.Window({
									width: 400,
									animateTarget: Ext.getCmp('btnFindPlacename').el,
									bodyStyle: 'Padding: 5px',
									autoDestroy: true,
									id: 'placeWin',
									layout: 'form',
									title: 'Search for Placename or Postcode',
									items: [form],  //html: '<div id="placeForm" class="autosuggest-box"><input style="margin-left:10px;margin-top:10px;width:360px;font-style:italic;color:#666" id="autoNameField" name="autoNameField" value="Cardiff..." /></div>',                        
									buttons: [{
										xtype: 'button',
										text: 'Search',
										id: 'btnNameSearch',
										disabled: true,
										handler: function () {
											var frmPlaceSearch = Ext.getCmp('placeForm');
											if (frmPlaceSearch.form.isValid()) {

												var coords = Ext.getCmp('cmboGeoSearch').Value;
												var size = new OpenLayers.Size(16, 16);
												var offset = new OpenLayers.Pixel(0, 0);
												var icon = new OpenLayers.Icon('images/silk/flag_purple.png', size, offset);
												var dist = Ext.getCmp('distSlider').getValue();
												var lat = coords.split(",")[0];
												var lon = coords.split(",")[1];

												var pos = new OpenLayers.LonLat(lat, lon);
												pos.transform(geographic, OSGB);

												var layer = map.getLayer('placeSearch');

												layer.addMarker(new OpenLayers.Marker(pos, icon));
												var centroid = new OpenLayers.Geometry.Point(pos.lon, pos.lat);

												var new_geom = OpenLayers.Geometry.Polygon.createGeodesicPolygon(centroid, (dist * 1000), 100, 45, mercator)
												var buffer = new OpenLayers.Feature.Vector(new_geom, { 'name': Ext.getCmp('cmboGeoSearch').getValue(), 'type': 'place' });
												var progress = new GeoPortal.Toolbars.ProgressBar();
												SearchLayer.addFeatures(buffer);
												map.zoomToExtent(new_geom.getBounds());
												frmPlaceSearch.getForm().submit({
													url: SpatialSearch,
													timeout: 12000,
													params: { geography: new_geom },
													waitMsg: 'Performing Spatial Search....',
													success: function (form, action) {


														SearchWin.close();
														var nodes = Ext.getCmp('TOCTP').root.childNodes;
														for (var a = 0; a < nodes.length; a++) {
															if (nodes[a].text == 'Search Results') {
																var resultsNode = nodes[a];
																for (var k = 0; k < resultsNode.childNodes.length; k++) {
																	if (resultsNode.childNodes[k].childNodes.length > 0) {

																		for (var l = 0; l < resultsNode.childNodes[k].childNodes.length; l++) {

																			for (var m = 0; m < resultsNode.childNodes[k].childNodes[l].childNodes.length; m++) {
																				var surveyNode = resultsNode.childNodes[k].childNodes[l].childNodes[m];
																				for (var n = 0; n < surveyNode.childNodes.length; n++) {

																					//not working layer not found....
																					//                                                                                var unitNode = surveyNode.childNodes[n];
																					//                                                                                var layerID = unitNode.attributes.LayerID;
																					//                                                                                var layer = map.getLayer(layerID);
																					//                                                                                map.removeLayer(layer);


																				}

																			}
																		}

																		resultsNode.childNodes[k].removeAll({ destroy: true });

																	}
																}


															}
														}

														//Ext.getCmp('TOCTP').load();

														var quantCount = action.result.data[0].quantCount;
														var qualCount = action.result.data[0].qualCount;

														for (var item in action.result.data[0].quantData) {

															var result = action.result.data[0].quantData[item];
															var name = result.surveyName;
															var surveyYear = result.year;
															var ID = result.survey_id;
															var name2 = name.replace(/\s/g, "");
															var unit = result.unit;

															var map_id = buildMapLayer(name, "Survey Data", name2, surveyYear, unit, ID);
															//var features = [];
															//var geom = jQuery.makeArray(action.result.data[0].quantData[item].the_geom);
															for (var geom in action.result.data[0].quantData[item].the_geom) {
																var feature = new Object();
																feature.Name = action.result.data[0].quantData[item].the_geom[geom].Name;
																feature.EWKT = action.result.data[0].quantData[item].the_geom[geom].geom;
																feature.SurveyID = ID;
																feature.SurveyName = name;
																feature.Colour = 'Red';
																feature.Year = surveyYear;

																if (feature != undefined) {
																	addFeature(feature, map_id);
																}
															}
														 };
														var nodes = Ext.getCmp('TOCTP').root.childNodes;
														for (var a = 0; a < nodes.length; a++) {
															if (nodes[a].text == 'Search Results') {
																var subNodes = nodes[a];
																for (var i = 0; i < subNodes.childNodes.length; i++) {
																	if (subNodes.childNodes[i].text == 'Survey Data') {
																		subNodes.childNodes[i].setText('Survey Data ' + '<b>(' + quantCount + ')</b>');
																	}
																}
															}
														}
														var qualCount = action.result.data[0].qualCount;
														for (var item in action.result.data[0].qualData) {
															var result = action.result.data[0].qualData[item];
															var name = result.identifier;
															var ID = result.identifier;
															var map_id = buildQualMapLayer(result.name, "Qualitative Data", "Transcripts", ID, name);
															for (var i = 0; i < result.coords.length; i++) {
																var lat = result.coords[i].lat;
																var lon = result.coords[i].lon

																var feature = new Object();
																feature.lat = lat;
																feature.lon = lon;
																feature.colour = 'Purple';
																feature.counts = (result.coords[i].counts * 2);
																feature.title = result.coords[i].name;
																feature.QID = ID;
																addQualFeature(feature, map_id)
															}
													  }
													  var nodes = Ext.getCmp('TOCTP').root.childNodes;
														for (var a = 0; a < nodes.length; a++) {      
															if (nodes[a].text == 'Search Results') {
																var subNodes = nodes[a];
																for (var i = 0; i < subNodes.childNodes.length; i++) {
																	if (subNodes.childNodes[i].text == 'Qualitative Data') {
																		subNodes.childNodes[i].setText('Qualitative Data ' + '<b>(' + qualCount + ')</b>');
																	}
																}
															}
														}
																																						   
														//Ext.Msg.alert("Spatial Data", action.result.message);
													},
													failure: function (form, action) {
														Ext.Msg.alert("Spatial Data", action.result.message);
													}

												});
											}
										}
									}]

								});

								SearchWin.show();
							}
							}
							}
						 });

	return action;

};

function createBufferAction() {


		var action = new GeoExt.Action({
		icon: 'images/silk/buffer.png',
		control: new OpenLayers.Control.BufferClick(),
		map: map,
		// button options
		toggleGroup: "findTools",
		allowDepress: false,
		tooltip: "buffer location",
		// check item options
		group: "findTools"
});

return action;

}

function createRectangleAction() {

	polyfinished = function (feature) {
		var poly = new OpenLayers.Feature.Vector(feature, { 'name': 'Search Polygon', 'type': 'place' });
		SearchLayer.addFeatures(poly);
		doSpatialSearch(feature);
		// alert('Poly Done');
	};

	var opts = {
		callbacks: { 'done': polyfinished },
		handlerOptions: { irregular: true },
	};



	var action = new GeoExt.Action({
		icon: 'images/geosilk/shape_square_red.png',
		control: new OpenLayers.Control.DrawFeature(SearchLayer, OpenLayers.Handler.RegularPolygon,opts),
		map: map,
		// button options
		toggleGroup: "findTools",
		allowDepress: false,
		tooltip: "search by regular polygon",
		// check item options
		group: "findTools"
	});
	return action;
}

function doSpatialSearch(geography) {
  var loadMask = new Ext.LoadMask(Ext.getBody(), {msg:"Retrieving Spatial Search Results...."});
	loadMask.show();
var data = "geography=" + geography;

$.ajax({
url:  SpatialSearch,
type: 'POST', 
dataType: 'json',
data: data,
success: function (data) { showResults(data, loadMask) }
});

   //loadMask.hide();

//Ext.Ajax.request({
//    url: SpatialSearch,
//	timeout: 12000,
//    method: 'POST',
//	params: { geography: geography },
//	waitMsg: 'Performing Spatial Search....',
//    success: function(){ showResults();},
//    failure: function(){("oops");}
//    });


}



//function showResults(action, loadMask){

//var nodes = Ext.getCmp('TOCTP').root.childNodes;
//	for (var a = 0; a < nodes.length; a++) {
//		if (nodes[a].text == 'Search Results') {
//			var resultsNode = nodes[a];
//			for (var k = 0; k < resultsNode.childNodes.length; k++) {
//				if (resultsNode.childNodes[k].childNodes.length > 0) {

//					for (var l = 0; l < resultsNode.childNodes[k].childNodes.length; l++) {

//						for (var m = 0; m < resultsNode.childNodes[k].childNodes[l].childNodes.length; m++) {
//							var surveyNode = resultsNode.childNodes[k].childNodes[l].childNodes[m];
//							for (var n = 0; n < surveyNode.childNodes.length; n++) {

//								//not working layer not found....
//								//                                                                                var unitNode = surveyNode.childNodes[n];
//								//                                                                                var layerID = unitNode.attributes.LayerID;
//								//                                                                                var layer = map.getLayer(layerID);
//								//                                                                                map.removeLayer(layer);


//							}

//						}
//					}

//					resultsNode.childNodes[k].removeAll({ destroy: true });

//				}
//			}


//		}
//	}

//	//Ext.getCmp('TOCTP').load();

//	var quantCount = action.data[0].quantCount;
//	var qualCount = action.data[0].qualCount;

//	for (var item in action.data[0].quantData) {

//		var result = action.data[0].quantData[item];
//		var name = result.surveyName;
//		var surveyYear = result.year;
//		var ID = result.survey_id;
//		var name2 = name.replace(/\s/g, "");
//		var unit = result.unit;

//		var map_id = buildMapLayer(name, "Survey Data", name2, surveyYear, unit, ID);
//		//var features = [];
//		//var geom = jQuery.makeArray(action.result.data[0].quantData[item].the_geom);
//		for (var geom in action.data[0].quantData[item].the_geom) {
//			var feature = new Object();
//			feature.Name = action.data[0].quantData[item].the_geom[geom].Name;
//			feature.EWKT = action.data[0].quantData[item].the_geom[geom].geom;
//			feature.SurveyID = ID;
//			feature.SurveyName = name;
//			feature.Colour = 'Red';
//			feature.Year = surveyYear;

//			if (feature != undefined) {
//				addFeature(feature, map_id);
//			}
//		}
//		};
//	var nodes = Ext.getCmp('TOCTP').root.childNodes;
//	for (var a = 0; a < nodes.length; a++) {
//		if (nodes[a].text == 'Search Results') {
//			var subNodes = nodes[a];
//			for (var i = 0; i < subNodes.childNodes.length; i++) {
//				if (subNodes.childNodes[i].text == 'Survey Data') {
//					subNodes.childNodes[i].setText('Survey Data ' + '<b>(' + quantCount + ')</b>');
//				}
//			}
//		}
//	}
//	var qualCount = action.data[0].qualCount;
//	for (var item in action.data[0].qualData) {
//		var result = action.data[0].qualData[item];
//		var name = result.identifier;
//		var ID = result.identifier;
//		var map_id = buildQualMapLayer(result.name, "Qualitative Data", "Transcripts", ID, name);
//		for (var i = 0; i < result.coords.length; i++) {
//			var lat = result.coords[i].lat;
//			var lon = result.coords[i].lon

//			var feature = new Object();
//			feature.lat = lat;
//			feature.lon = lon;
//			feature.colour = 'Purple';
//			feature.counts = (result.coords[i].counts * 2);
//			feature.title = result.coords[i].name;
//			feature.QID = ID;
//			addQualFeature(feature, map_id)
//		}
//	}
//	var nodes = Ext.getCmp('TOCTP').root.childNodes;
//	for (var a = 0; a < nodes.length; a++) {      
//		if (nodes[a].text == 'Search Results') {
//			var subNodes = nodes[a];
//			for (var i = 0; i < subNodes.childNodes.length; i++) {
//				if (subNodes.childNodes[i].text == 'Qualitative Data') {
//					subNodes.childNodes[i].setText('Qualitative Data ' + '<b>(' + qualCount + ')</b>');
//				}
//			}
//		}
//	}


//	
//	loadMask.hide();
//	
//}
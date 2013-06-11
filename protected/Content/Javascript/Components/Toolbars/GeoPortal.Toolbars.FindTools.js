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
        SearchLayer.destroyFeatures();
        var poly = new OpenLayers.Feature.Vector(feature, { 'name': 'Search Polygon', 'type': 'place' });
        SearchLayer.addFeatures(poly);

//        Ext.MessageBox.confirm('Search Options', 'Would you like to specify advanced search parameters? (Beta)',
//            function (btn, text) {
//                if (btn == 'yes') {
//                    var advSearch = Ext.getCmp('advSearch');
//                    if (advSearch == null) {
//                        var advSearchPanel = new GeoPortal.Forms.AdvancedSearch();
//                        advSearch = new Ext.Window({
//                            title: 'Advanced Search',
//                            id: 'advSearch',
//                            resizable: false,
//                            animateTarget: Ext.getCmp("btnAdvSearch").el,
//                            items: [advSearchPanel]
//                        });
//                    }
//                    advSearch.show();
//                } else {
//                  var loadMask = new Ext.LoadMask(Ext.getBody(), { msg: "Retrieving Spatial Search Results....", removeMask: false });
//                  loadMask.show();
        doSpatialSearch(feature);
        // alert('Poly Done');
        action.control.deactivate();
//                }
//            }
//        );


    };
    var action = new GeoExt.Action({
        icon: 'images/geosilk/line_red.png',
        control: new OpenLayers.Control.DrawFeature(SearchLayer, OpenLayers.Handler.Polygon, { callbacks: { 'done': polyfinished} }),
        map: map,
        // button options
        toggleGroup: "findTools",
        allowDepress: false,
        tooltip: "Search using irregular polygon",
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
        tooltip: "Search by place name",
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
                        resizable: false,
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
                                    SearchLayer.destroyFeatures();
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
//                                    var loadMask = new Ext.LoadMask(Ext.getBody(), {msg:"Retrieving Spatial Search Results...."});
//                                    loadMask.show();
                                    doSpatialSearch(new_geom);
                                    action.control.deactivate();
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
        tooltip: "Search using circular buffer",
        // check item options
        group: "findTools"
    });

    return action;

}

function createRectangleAction() {

    polyfinished = function (feature) {
        SearchLayer.destroyFeatures();
        var poly = new OpenLayers.Feature.Vector(feature, { 'name': 'Search Polygon', 'type': 'place' });
        SearchLayer.addFeatures(poly);
//        var loadMask = new Ext.LoadMask(Ext.getBody(), { msg: "Retrieving Spatial Search Results....", removeMask: false });
//        loadMask.show();
        doSpatialSearch(feature);
        rec_action.control.deactivate();
        // alert('Poly Done');
    };

    var opts = {
        callbacks: { 'done': polyfinished },
        handlerOptions: { irregular: true }
    };



    var rec_action = new GeoExt.Action({
        icon: 'images/geosilk/shape_square_red.png',
        control: new OpenLayers.Control.DrawFeature(SearchLayer, OpenLayers.Handler.RegularPolygon,opts),
        map: map,
        // button options
        toggleGroup: "findTools",
        allowDepress: false,
        tooltip: "Search using rectangle",
        // check item options
        group: "findTools"
    });
    return rec_action;

}

function doSpatialSearch(geography) {

    Ext.MessageBox.confirm('Search Options', 'Would you like to specify advanced search parameters? (Beta)',
        function (btn, text) {
            if (btn == 'yes') {
                var advSearch = Ext.getCmp('advSearch');

                if (advSearch != null) {
                    advSearch.close();
                }
                var advSearchPanel = new GeoPortal.Forms.AdvancedSearch({geographyRegion: geography});
                advSearch = new Ext.Window({
//                        title: 'Advanced Search',
                    id: 'advSearch',
//                        resizable: false,
                    animateTarget: Ext.getCmp("btnAdvSearch").el,
                    items: [advSearchPanel]
                });
                var spatialCheck = Ext.getCmp('cbSpatial');
                var spatialVal = spatialCheck.setValue(true);
                advSearch.show();
            } else {

                var loadMask = new Ext.LoadMask(Ext.getBody(), { msg: "Retrieving Spatial Search Results....", removeMask: false });
                loadMask.show();

                if (Ext.getCmp('spatResWin') != null) {

                    Ext.getCmp('spatResWin').close();
                }

                var searchResults = new GeoPortal.Windows.SpatialResults(
                    {
                        animateTarget: Ext.getCmp('minSS').el
//            callback: function() {
//            }
                    }
                );

                var grdqual = Ext.getCmp('grdQual');
                var grid = Ext.getCmp('grdSurvey');

                //    console.log(grdqual);
                //    console.log(grid);

                searchResults.doLayout();
                grdqual.store.load(
                    {
                        params: {
                            geography: geography,
                            start: 0,
                            limit: 15,
                            type: 'Qual'
                        },
                        scope : this,
                        callback: function () {
                            searchResults.doLayout();
                            grid.store.load(
                                {
                                    params: {
                                        start: 0,
                                        limit: 15,
                                        type: 'Quant'
                                    },
                                    scope: this,
                                    callback: function () {
                                        console.log(searchResults);
                                        searchResults.doLayout();
                                        searchResults.show();
                                        loadMask.hide();
                                    }
                                }
                            );

                        }
                    }
                );
            }
        });
}



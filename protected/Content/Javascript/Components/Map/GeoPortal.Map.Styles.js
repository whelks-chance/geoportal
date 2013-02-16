var bufferStyle = new OpenLayers.StyleMap({
    "default": new OpenLayers.Style({
            title: 'Drawn Area',
            strokeColor: "red",
            strokeOpacity: 1,
            strokeWidth: 1,
            fillColor: "#B0E0E6",
            fillOpacity: 0.25
        },{
            rules: [new OpenLayers.Rule({
            title: 'Search Area',
            filter: new OpenLayers.Filter.Comparison({
                type: OpenLayers.Filter.Comparison.EQUAL_TO,
                property: "type",
                value: "place"
                }),
        symbolizer:{
            strokeColor: "purple",
            strokeOpacity: 1,
            strokeWidth: 1,
            fillColor: "#B0E0E6",
            fillOpacity: 0.25
            }
        }) ]
        })
    });

 
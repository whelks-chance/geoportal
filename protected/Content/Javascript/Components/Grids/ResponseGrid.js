/**
 * Created with JetBrains PhpStorm.
 * User: wiserd
 * Date: 18/11/13
 * Time: 15:52
 * To change this template use File | Settings | File Templates.
 */
Ext.ns('GeoPortal.Grids');
GeoPortal.Grids.ResponseGrid = Ext.extend(Ext.grid.GridPanel, {
    title: 'Response Table',
    autoScroll: true,
    id: 'DataEntryResponseGrid',
    headerAsText: false,
//    padding: 5,
    border : false,
    labelWidth: 75,
    SID: null,
    fieldLabel : 'Response Grid',

    store: new Ext.data.JsonStore ({
        fields: [
            {name: 'docType', mapping: 'docType'},
            {name: 'docTypeId',  mapping: 'docTypeId'}],
        id: "docTypeStore"
    }),

//            id: 'responseGrid',

    stripeRows: true,
    anchor: '97%',
    height: 200,

    viewConfig: {
        forceFit: true,
        type: 'vbox',
        align: 'stretch'
    },

    columns: [
        {header: "catagory", dataIndex: 'catagory'},
        {header: "number", dataIndex: 'number'}
    ],

    initComponent: function () {
        GeoPortal.Grids.ResponseGrid.superclass.initComponent.call(this);
    },

    UpdateGrid : function(headers, rows){
        var gridHeaders = [];
        var storeFields = [];
        for (var i = 0; i < headers.length; i++) {
            if(headers.hasOwnProperty(i)) {
                var header = headers[i];
                var heading = {'header' : header, 'dataIndex' : header};
                var field = {'name' : header, 'mapping' : header};

                gridHeaders.push(heading);
                storeFields.push(field);
            }
        }
        var responseStore = new Ext.data.JsonStore ({
            fields: Ext.decode(JSON.stringify(storeFields)),
            id: "responseStore"
        });

//            var responseGrid = Ext.getCmp('responseGrid');

        var headersText = JSON.stringify(gridHeaders);
        var columnModel = new Ext.grid.ColumnModel(Ext.decode(headersText));
        this.reconfigure(responseStore, columnModel);
        responseStore.loadData(Ext.util.JSON.decode(JSON.stringify(rows)));

        var gridDef = {};
        gridDef['headers'] = headers;
        gridDef['data'] = Ext.decode(JSON.stringify(rows));
        console.log(JSON.stringify(gridDef));
        this.JSONdata = gridDef;
    },
    loadFromQID : function(QID){
        Ext.Ajax.request({
            url: responseOptionsTableURL,
            method : 'POST',
            scope : this,
            params : {
                QID : QID
            },
            success: function(resp) {
                var responseData = Ext.decode(resp.responseText);

                var headers = responseData.data.headers;
                var tableData = responseData.data.data;

                var gridBody = [];
                gridBody.length = 0;
                for (var line in tableData) {
                    if (tableData.hasOwnProperty(line)) {
                        gridBody.push(tableData[line]);
                    }
                }

                this.UpdateGrid(headers, gridBody);

            },
            failure: function(resp) {
                console.log('failure!');
            }
        });
    },
    loadFromCSV : function(csvText){
        var csvArray = this.CSVToArray(csvText, ",");

        var headers = csvArray.shift();

        var dataRowsArray = [];
        for (var j = 0; j < csvArray.length; j++) {

            if(csvArray.hasOwnProperty(j)) {
                var rowData = csvArray[j];

                var thisRow = {};
                for(var i = 0; i < headers.length; i++) {
                    if(headers.hasOwnProperty(i)) {
                        thisRow[headers[i]] = rowData[i];
                    }
                }
                dataRowsArray.push(thisRow);
            }
        }

        this.UpdateGrid(headers, dataRowsArray);
    },
    //CSV parsing found at:
    //http://www.bennadel.com/blog/1504-Ask-Ben-Parsing-CSV-Strings-With-Javascript-Exec-Regular-Expression-Command.htm

    // This will parse a delimited string into an array of
// arrays. The default delimiter is the comma, but this
// can be overriden in the second argument.
    CSVToArray : function( strData, strDelimiter ){
// Check to see if the delimiter is defined. If not,
// then default to comma.
        strDelimiter = (strDelimiter || ",");

// Create a regular expression to parse the CSV values.
        var objPattern = new RegExp(
            (
// Delimiters.
                "(\\" + strDelimiter + "|\\r?\\n|\\r|^)" +

// Quoted fields.
                "(?:\"([^\"]*(?:\"\"[^\"]*)*)\"|" +

// Standard fields.
                "([^\"\\" + strDelimiter + "\\r\\n]*))"
                ),
            "gi"
        );


// Create an array to hold our data. Give the array
// a default empty first row.
        var arrData = [[]];

// Create an array to hold our individual pattern
// matching groups.
        var arrMatches = null;


// Keep looping over the regular expression matches
// until we can no longer find a match.
        while (arrMatches = objPattern.exec( strData )){

// Get the delimiter that was found.
            var strMatchedDelimiter = arrMatches[ 1 ];

// Check to see if the given delimiter has a length
// (is not the start of string) and if it matches
// field delimiter. If id does not, then we know
// that this delimiter is a row delimiter.
            if (
                strMatchedDelimiter.length &&
                    (strMatchedDelimiter != strDelimiter)
                ){

// Since we have reached a new row of data,
// add an empty row to our data array.
                arrData.push( [] );

            }


// Now that we have our delimiter out of the way,
// let's check to see which kind of value we
// captured (quoted or unquoted).
            if (arrMatches[ 2 ]){

// We found a quoted value. When we capture
// this value, unescape any double quotes.
                var strMatchedValue = arrMatches[ 2 ].replace(
                    new RegExp( "\"\"", "g" ),
                    "\""
                );

            } else {

// We found a non-quoted value.
                var strMatchedValue = arrMatches[ 3 ];

            }


// Now that we have our value string, let's add
// it to the data array.
            arrData[ arrData.length - 1 ].push( strMatchedValue );
        }

// Return the parsed data.
        return( arrData );
    }

});
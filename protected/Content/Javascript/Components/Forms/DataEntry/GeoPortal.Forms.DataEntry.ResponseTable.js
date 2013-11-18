/**
 * Created with JetBrains PhpStorm.
 * User: wiserd
 * Date: 08/11/13
 * Time: 10:59
 * To change this template use File | Settings | File Templates.
 */
Ext.ns('GeoPortal.Forms.DataEntry');
Ext.ns('GeoPortal.Grids');
GeoPortal.Forms.DataEntry.ResponseTable = Ext.extend(Ext.form.FormPanel, {

        title: 'Response Table',
        autoScroll: true,
        id: 'frmEntryResponseTable',
        headerAsText: false,
        padding: 5,
        labelWidth: 75,
        SID: null,
        grid : null,
        JSONdata : null,
        bodyCssClass: 'text-align:center',

        initComponent : function () {
            this.grid = new GeoPortal.Grids.ResponseGrid({id : "dataEntryResponseGrid"});


//            var responseStore = new Ext.data.JsonStore ({
//                fields: [
//                    {name: 'docType', mapping: 'docType'},
//                    {name: 'docTypeId',  mapping: 'docTypeId'}],
//                id: "docTypeStore"
////            root : "surveyData"
//            });



            this.items = [
                {
                    xtype: 'fieldset',
                    title: 'Response Table Grid',
//                    collapsed: false,
//                    collapsible: true,
                    defaults: { labelStyle: 'font-weight:bold;' },
                    items: [
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Response ID',
                            anchor: '97%',
                            name: 'responseTableRID',
                            allowBlank : false,
                            id: 'responseTableRIdField'
                        },
                        this.grid
//                        {
//                            xtype: 'grid',
//                            fieldLabel : 'Response Grid',
//                            store: responseStore,
//                            id: 'responseGrid',
//                            stripeRows: true,
//                            anchor: '97%',
////                            width: '100%',
//                            height: 200,
//                            viewConfig: {
//                                forceFit: true,
//                                type: 'vbox',
//                                align: 'stretch'
//                            },
//                            columns: [
//                                {header: "catagory", dataIndex: 'catagory'},
//                                {header: "number", dataIndex: 'number'}
//                            ]
//                        }
                    ]
                },
                {
                    xtype: 'fieldset',
                    title: 'Response Data Entry CSV',
                    defaults: { labelStyle: 'font-weight:bold;' },
                    items: [
                        {
                            xtype: 'textarea',
                            anchor: '97%',
                            fieldLabel: 'Response CSV',
                            allowBlank:false,
                            name: 'responseCSV',
                            id: 'responseCSVarea',
                            height          : "100",
                            autoScroll      : true,
                            value: "category,number"
                        }
                    ]
                }
            ];
            this.bbar = {
                xtype : 'toolbar',
                items: [
                    {
                        xtype: 'button',
                        id: 'btnparseCSV',
                        icon: 'images/silk/magnifier.png',
                        text: 'Parse CSV',
                        scope : this,
                        handler : function(){

                            var csvArea = Ext.getCmp('responseCSVarea');
                            var csvText = csvArea.getValue();

                            this.grid.loadFromCSV(csvText);
//                            var csvArray = this.CSVToArray(csvText, ",");
//
//                            var headers = csvArray.shift();
//
//                            var dataRowsArray = [];
//                            for (var j = 0; j < csvArray.length; j++) {
//
//                                if(csvArray.hasOwnProperty(j)) {
//                                    var rowData = csvArray[j];
//
//                                    var thisRow = {};
//                                    for(var i = 0; i < headers.length; i++) {
//                                        if(headers.hasOwnProperty(i)) {
//                                            thisRow[headers[i]] = rowData[i];
//                                        }
//                                    }
//                                    dataRowsArray.push(thisRow);
//                                }
//                            }
//
//                            this.UpdateGrid(headers, dataRowsArray);
                        }
                    },
                    {
                        xtype: 'tbfill'
                    }
                ]
            };
            this.tbar = {
                xtype: 'toolbar',
                items: [
                    {
                        xtype: 'button',
                        id: 'btnRTQuestionLoad',
                        icon: 'images/silk/application_get.png',
                        text: 'Load',
                        type: 'reset',
                        handler : this.FormLoad,
                        scope : this
                    },
                    {
                        xtype: 'tbfill'
                    },
                    {
                        xtype: 'button',
                        id: 'btnQsave',
                        icon: 'images/silk/database_edit.png',
                        text: 'Save',
                        type: 'reset',
                        handler : this.FormSave,
                        scope : this
                    },
                    {
                        xtype: 'button',
                        id: 'btnRTQuestionDelete',
                        icon: 'images/silk/application_form_delete.png',
                        text: 'Delete',
                        type: 'reset',
                        handler : this.FormDelete,
                        scope : this
                    },
                    {
                        xtype: 'button',
                        id: 'btnRTQuestionReset',
                        icon: 'images/silk/arrow_rotate_clockwise.png',
                        text: 'Reset Form',
                        type: 'reset',
                        handler : this.FormReset,
                        scope : this
                    }
                ]
            };


            GeoPortal.Forms.DataEntry.ResponseTable.superclass.initComponent.call(this);
        },
        loadFromQID : function(QID) {
            this.grid.loadFromQID(QID);
        },
//        loadFromQID : function(QID){
//            Ext.Ajax.request({
//                url: responseOptionsTableURL,
//                method : 'POST',
//                scope : this,
//                params : {
//                    QID : QID
//                },
//                success: function(resp) {
//                    var responseData = Ext.decode(resp.responseText);
//
//                    var headers = responseData.data.headers;
//                    var tableData = responseData.data.data;
//
//                    var gridBody = [];
//                    gridBody.length = 0;
//                    for (var line in tableData) {
//                        if (tableData.hasOwnProperty(line)) {
//                            gridBody.push(tableData[line]);
//                        }
//                    }
//
//                    this.UpdateGrid(headers, gridBody);
//
//                },
//                failure: function(resp) {
//                    console.log('failure!');
//                }
//            });
//        },
//        UpdateGrid : function(headers, rows){
//            var gridHeaders = [];
//            var storeFields = [];
//            for (var i = 0; i < headers.length; i++) {
//                if(headers.hasOwnProperty(i)) {
//                    var header = headers[i];
//                    var heading = {'header' : header, 'dataIndex' : header};
//                    var field = {'name' : header, 'mapping' : header};
//
//                    gridHeaders.push(heading);
//                    storeFields.push(field);
//                }
//            }
//            var responseStore = new Ext.data.JsonStore ({
//                fields: Ext.decode(JSON.stringify(storeFields)),
//                id: "responseStore"
//            });
//
////            var responseGrid = Ext.getCmp('responseGrid');
//            var responseGrid = this.grid;
//
//            var headersText = JSON.stringify(gridHeaders);
//            var columnModel = new Ext.grid.ColumnModel(Ext.decode(headersText));
//            responseGrid.reconfigure(responseStore, columnModel);
//            responseStore.loadData(Ext.util.JSON.decode(JSON.stringify(rows)));
//
//            var gridDef = {};
//            gridDef['headers'] = headers;
//            gridDef['data'] = Ext.decode(JSON.stringify(rows));
//            console.log(JSON.stringify(gridDef));
//            this.JSONdata = gridDef;
//        },
        FormLoad : function() {
            var surveyField = Ext.getCmp('QuestionSurveyID');
            var surveyID = surveyField.getValue();
            console.log('surveyID : ' + surveyID);

            if (surveyID == "") {
                alert('No Survey ID defined, please load a previous survey');
            } else {
                var loadFQWin = new Ext.Window({ items:[new GeoPortal.Forms.DataEntry.FindQuestions({SID:surveyID})], title:'Load Question', modal:true, width:500, id:'LoadQuestionWin' });
                loadFQWin.show();
            }
        },
        FormReset : function() {
            console.log('reset ' + this.id)
            var thisPanel = Ext.getCmp(this.id);
            console.log(thisPanel);
            thisPanel.getForm().reset();
        },
        FormInsert : function() {

        },
        FormUpdate : function() {
            console.log('reset ' + this.id)
            var thisPanel = Ext.getCmp(this.id);
            console.log(thisPanel);
            thisPanel.getForm().reset();
        },
        FormDelete : function() {
            console.log('reset ' + this.id)
            var thisPanel = Ext.getCmp(this.id);
            console.log(thisPanel);
            thisPanel.getForm().reset();
        },
        FormSave : function() {

            var sid = Ext.getCmp('QuestionSurveyID').getValue();
            var qid = Ext.getCmp('QuestionIdField').getValue();

            var submit = true;
            if(sid == "") {
                submit = false;
                Ext.MessageBox.alert('Survey ID Error', 'Please load a survey to add a question to');
            }
            if(qid == "") {
                submit = false;
                Ext.MessageBox.alert('Question ID Error', 'Please enter a Question ID, normally related to the Survey ID');
            }
            if (submit) {

                Ext.Ajax.request({
                    url: checkRecordExists,
                    scope: this,
                    method : 'POST',
                    params : {
                        recordType: "response_table",
                        recordID: qid
                    },
                    success: function(resp) {
                        var responseData = Ext.decode(resp.responseText);
                        var qidProjectID = responseData.projectid;
                        console.log(qidProjectID);

                        if (responseData.exists == true) {
                            console.log('ok ' + responseData);
                            Ext.MessageBox.confirm('Status', 'Question qid is already in use, overwrite?', function (btn, text) {

                                if(btn == 'yes') {
                                    Ext.getCmp('frmEntryQuestion').getForm().submit({
                                        scope: this,
                                        url: insertQuestion,
                                        params : {
                                            update : true,
                                            projectID : qidProjectID
                                        },
                                        waitMsg: 'Inserting Question Data....',
                                        success: function (form, action) {
                                            Ext.MessageBox.alert("Success", "Question data overwritten");
                                        },
                                        failure: function (form, action) {
                                            Ext.MessageBox.alert("Failure", action.result.message);
                                        }
                                    });
                                } else {
                                    Ext.MessageBox.alert("no, don't overwrite question");
                                }
                            });
                        } else {

                            var thisPanel = Ext.getCmp('frmEntryQuestion');
                            if(thisPanel.getForm().isValid()) {
                                Ext.Ajax.request({
                                    url: insertResponseTable,
                                    method : 'POST',
                                    params : {
                                        jsonResonseTable : this.JSONdata
                                    },
                                    success: function(resp) {
                                        console.log('success!');
                                        var responseData = Ext.decode(resp.responseText);
                                        console.log(responseData);
                                    },
                                    failure: function(resp) {
                                        console.log('failure!');
                                    }
                                });
                            } else {
                                Ext.MessageBox.alert('Status', 'There is an invalid entry in the Question entry form.');
                            }

                        }
                    },
                    failure: function(resp) {

                    }
                });
            }
        }

//        //CSV parsing found at:
//        //http://www.bennadel.com/blog/1504-Ask-Ben-Parsing-CSV-Strings-With-Javascript-Exec-Regular-Expression-Command.htm
//
//        // This will parse a delimited string into an array of
//// arrays. The default delimiter is the comma, but this
//// can be overriden in the second argument.
//        CSVToArray : function( strData, strDelimiter ){
//// Check to see if the delimiter is defined. If not,
//// then default to comma.
//            strDelimiter = (strDelimiter || ",");
//
//// Create a regular expression to parse the CSV values.
//            var objPattern = new RegExp(
//                (
//// Delimiters.
//                    "(\\" + strDelimiter + "|\\r?\\n|\\r|^)" +
//
//// Quoted fields.
//                    "(?:\"([^\"]*(?:\"\"[^\"]*)*)\"|" +
//
//// Standard fields.
//                    "([^\"\\" + strDelimiter + "\\r\\n]*))"
//                    ),
//                "gi"
//            );
//
//
//// Create an array to hold our data. Give the array
//// a default empty first row.
//            var arrData = [[]];
//
//// Create an array to hold our individual pattern
//// matching groups.
//            var arrMatches = null;
//
//
//// Keep looping over the regular expression matches
//// until we can no longer find a match.
//            while (arrMatches = objPattern.exec( strData )){
//
//// Get the delimiter that was found.
//                var strMatchedDelimiter = arrMatches[ 1 ];
//
//// Check to see if the given delimiter has a length
//// (is not the start of string) and if it matches
//// field delimiter. If id does not, then we know
//// that this delimiter is a row delimiter.
//                if (
//                    strMatchedDelimiter.length &&
//                        (strMatchedDelimiter != strDelimiter)
//                    ){
//
//// Since we have reached a new row of data,
//// add an empty row to our data array.
//                    arrData.push( [] );
//
//                }
//
//
//// Now that we have our delimiter out of the way,
//// let's check to see which kind of value we
//// captured (quoted or unquoted).
//                if (arrMatches[ 2 ]){
//
//// We found a quoted value. When we capture
//// this value, unescape any double quotes.
//                    var strMatchedValue = arrMatches[ 2 ].replace(
//                        new RegExp( "\"\"", "g" ),
//                        "\""
//                    );
//
//                } else {
//
//// We found a non-quoted value.
//                    var strMatchedValue = arrMatches[ 3 ];
//
//                }
//
//
//// Now that we have our value string, let's add
//// it to the data array.
//                arrData[ arrData.length - 1 ].push( strMatchedValue );
//            }
//
//// Return the parsed data.
//            return( arrData );
//        }
    }
);
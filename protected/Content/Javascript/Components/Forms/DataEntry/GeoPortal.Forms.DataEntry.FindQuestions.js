/**
 * Created with JetBrains PhpStorm.
 * User: wiserd
 * Date: 15/08/13
 * Time: 13:58
 * To change this template use File | Settings | File Templates.
 */

GeoPortal.Forms.DataEntry.FindQuestions = Ext.extend(Ext.form.FormPanel, {
//    width: 300,
    id: 'frmFindQuestions',
//    height: 140,
    padding: 5,
    dataset: null,
    SID: null,
    initComponent: function () {
        var datasetID = "";

        var questionIdStore = new Ext.data.JsonStore ({
            fields: [
                {name: 'name', mapping: 'QuestionName'},
                {name: 'id',  mapping: 'QuestionID'}],
            id: "questionIdStore",
            root : "questionData"
        });

        Ext.Ajax.request({
            url: getSurveyQuestions,
            method : 'POST',
            params : {SID : this.SID},
            success: function(resp) {
                var responseData = Ext.decode(resp.responseText);
                questionIdStore.loadData(responseData);
            },
            failure: function(resp) {
                console.log('failure!');
            }
        });

        this.bbar = {
            xtype: 'toolbar',
            items : [
                {
                    xtype: 'tbfill'
                },
                {
                    xtype: 'button',
                    text: 'Load',
                    id: 'loadQuestioninfobtn',
                    handler: function(){

                        var questionID = datasetID;

                        Ext.getCmp("frmEntryQuestion").getForm().load(
                            {
                                url: QmetaURL,
                                waitMsg: 'Loading.......',
                                method: 'POST',
                                params: {
                                    ID: questionID
                                }
                            }
                        );
                        Ext.getCmp("frmEntryResponse").getForm().load(
                            {
                                url: RmetaURL,
                                waitMsg: 'Loading.......',
                                method: 'POST',
                                params: {
                                    QID: questionID
                                }
                            }
                        );

                        var breadcrumb = Ext.getCmp('breadcrumb');

                        breadcrumb.updateBreadcrumb(null, null, questionID, "");

                        Ext.getCmp('LoadQuestionWin').destroy();

                        Ext.Ajax.request({
                            url: responseOptionsTableURL,
                            method : 'POST',
                            params : {
                                QID : questionID
                            },
                            success: function(resp) {
                                var responseData = Ext.decode(resp.responseText);

                                var headers = responseData.data.headers;
                                var tableData = responseData.data.data;

//                                var gridHeaders = [];
//                                var storeFields = [];
//                                for (var i = 0; i < headers.length; i++) {
//                                    if(headers.hasOwnProperty(i)) {
//                                        var header = headers[i];
//                                        var heading = {'header' : header, 'dataIndex' : header};
//                                        var field = {'name' : header, 'mapping' : header};
//
//                                        gridHeaders.push(heading);
//                                        storeFields.push(field);
//                                    }
//                                }

                                var gridBody = [];
                                gridBody.length = 0;
                                for (var line in tableData) {
                                    if (tableData.hasOwnProperty(line)) {

                                        gridBody.push(tableData[line]);

                                    }
                                }

//                                var fieldsVar = JSON.stringify(storeFields);
//                                var responseStore = new Ext.data.JsonStore ({
//                                    fields: Ext.decode(fieldsVar),
//                                    id: "responseStore"
//                                });
//                                var columnModel = new Ext.grid.ColumnModel(Ext.decode(JSON.stringify(gridHeaders)));
//                                var responseGrid = Ext.getCmp('responseGrid');
//                                responseGrid.reconfigure(responseStore, columnModel);
//                                responseStore.loadData(Ext.decode(JSON.stringify(gridBody)));

                                var responseGridForm = Ext.getCmp('frmEntryResponseTable');
                                responseGridForm.UpdateGrid(headers, gridBody);

                            },
                            failure: function(resp) {
                                console.log('failure!');
                            }
                        });

                    }
                }
            ]
        };
        this.items = [
            {
                // Drop down for the dataset
                // Lets user select a survey, sends the id to RemoteData/getRemoteDataset
                // Returns the regions available for the data

                xtype: 'combo',
                tpl: '<tpl for="."><div ext:qtip="{name}" class="x-combo-list-item">{name}</div></tpl>',
                id: 'surveyIdcombo',
                anchor: '100%',
                fieldLabel: 'Select Question ID',
                name: 'Dataset',
                triggerAction: 'all',
                displayField: 'name',
                hiddenName: 'surveyID',
                valueField: 'id',
                listeners: {
                    'select': function(t){
                        var cmboDataset = Ext.getCmp('surveyIdcombo');
                        datasetID = cmboDataset.getValue();
                    }
                },
                mode: 'local',
                store : questionIdStore
            }
        ];
        GeoPortal.Forms.DataEntry.FindQuestions.superclass.initComponent.call(this);
    }
});


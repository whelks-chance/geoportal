/**
 * Created with JetBrains PhpStorm.
 * User: wiserd
 * Date: 14/08/13
 * Time: 16:56
 * To change this template use File | Settings | File Templates.
 */
GeoPortal.Forms.DataEntry.FindSurvey = Ext.extend(Ext.form.FormPanel, {
//    width: 300,
    id: 'frmFindSurvey',
//    height: 140,
    padding: 5,
    dataset: null,
    initComponent: function () {
        var datasetID = "";

        var surveyIdStore = new Ext.data.JsonStore ({
            fields: [
                {name: 'name', mapping: 'SurveyName'},
                {name: 'wid', mapping:'WiserdID'},
                {name: 'sid',  mapping: 'SurveyID'}],
            id: "surveyIdStore",
            root : "surveyData"
        });

        Ext.Ajax.request({
//            url: getDCids,
            url: advancedSearchMetaURL,
            method : 'POST',
            success: function(resp) {
                var responseData = Ext.decode(resp.responseText);
                surveyIdStore.loadData(responseData);
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
                    id: 'loadSurveyinfobtn',
                    handler: function(){

                        var index = surveyIdStore.find("sid", datasetID);
                        var record = surveyIdStore.getAt(index);
                        var wid = record.get('wid');

                        var surveyID = datasetID;

                        Ext.getCmp("frmEntrySurvey").getForm().load(
                            {
                                url: SmetaURL,
                                waitMsg: 'Loading.......',
                                method: 'POST',
                                params: {
                                    SID: surveyID
                                }
                            }
                        );

                        var choppedwiserdID = wid.substr(2);

                        Ext.getCmp("frmEntryDC").getForm().load(
                            {
                                url: DCmetaURL,
                                waitMsg: 'Loading.......',
                                method: 'POST',
                                params: {
                                    SID: choppedwiserdID
                                }
                            }
                        );

                        var questionPanel = Ext.getCmp('frmEntryQuestion');
                        questionPanel.getForm().reset();
                        var responsePanel = Ext.getCmp('frmEntryResponse');
                        responsePanel.getForm().reset();

                        var surveyField = Ext.getCmp('QuestionSurveyID');
                        surveyField.setValue(surveyID);
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
                fieldLabel: 'Select Survey ID',
                name: 'Dataset',
                triggerAction: 'all',
                displayField: 'name',
                hiddenName: 'surveyID',
                valueField: 'sid',
                listeners: {
                    'select': function(t){
                        var cmboDataset = Ext.getCmp('surveyIdcombo');
                        datasetID = cmboDataset.getValue();
                    }
                },
                mode: 'local',
                store : surveyIdStore
            }
        ];
        GeoPortal.Forms.DataEntry.FindSurvey.superclass.initComponent.call(this);
    }
});

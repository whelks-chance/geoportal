/**
 * Created with JetBrains PhpStorm.
 * User: wiserd
 * Date: 21/09/13
 * Time: 17:06
 * To change this template use File | Settings | File Templates.
 */

GeoPortal.Forms.DataEntry.QuestionRouting = Ext.extend(Ext.form.FormPanel, {

    id: 'frmQuestionRouting',
    padding: 5,
    dataset: null,
    SID: null,
    textfieldcmp : null,
    initComponent: function () {
        var datasetID = "";

        var routingQuestionIdStore = new Ext.data.JsonStore ({
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
                routingQuestionIdStore.loadData(responseData);
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
                    scope: this,
                    handler: function(){

                        var txtfieldcmp = Ext.getCmp(this.textfieldcmp);

                        txtfieldcmp.setValue(datasetID);

                        Ext.getCmp('routingWin').destroy();

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
                store : routingQuestionIdStore
            }
        ];
        GeoPortal.Forms.DataEntry.QuestionRouting.superclass.initComponent.call(this);
    }
});


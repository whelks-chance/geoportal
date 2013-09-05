/**
 * Created with JetBrains PhpStorm.
 * User: wiserd
 * Date: 22/08/13
 * Time: 18:36
 * To change this template use File | Settings | File Templates.
 */


GeoPortal.Forms.DataEntry.SetupNewSurvey = Ext.extend(Ext.form.FormPanel, {
    DCForm : null,
    SurveyForm: null,
    wid : null,
    sid: null,
    width: 300,
    id: 'frmSetupSurvey',
//    height: 140,
    padding: 5,
    initComponent: function () {

        var userProjectStore = new Ext.data.JsonStore ({
            fields: [
                {name: 'name', mapping: 'projectname'},
                {name: 'id',  mapping: 'projectid'}
            ],
            baseParams : {
            },
            url: getUsersProjects,
            id: "userProjectStore",
            root : "usersProjects"
        });
        userProjectStore.on('load', function(store, recs, opt){

            if(store.data.length == 0) {
                Ext.MessageBox.alert('Error', 'Please login to perform this action');
                Ext.getCmp('setupSurveyWin').destroy();
            } else {
                this.doLayout();
            }
        }, this);
        userProjectStore.load();

        this.buttons  =[
            {
                xtype: 'button',
                text: 'Create',
                id: 'btnCreatenewsurvey',
                scope: this,
                handler: function(){

                    var projectName = Ext.getCmp('newSurveyToProjectcombo');
                    var projectID = projectName.getValue();

                    this.DCForm.submit({
                        scope: this,
                        url: insertDC,
                        params : {
                            projectID : projectID
                        },
                        waitMsg: 'Inserting Dublic Core Data....',
                        success: function (form, action) {
//                            Ext.Msg.alert("DC insert Success!",action.result.message);

                            this.SurveyForm.submit({
                                scope: this,
                                url: insertSurvey,
                                params : {
                                    project : projectID,
                                    wid : this.wid
                                },
                                waitMsg: 'Inserting Survey Data....',
                                success: function (form, action) {

                                    var thisPanel = Ext.getCmp('frmSetupSurvey');
                                    thisPanel.getForm().submit({
                                        url: buildNewSurveyLinks,
                                        waitMsg: 'Building Survey structure....',
                                        params : {
                                            wid: this.wid,
                                            sid: this.sid,
                                            projectID: projectID
                                        },
                                        success: function (form, action) {
                                            Ext.Msg.alert("Success!",action.result.message);
                                        },
                                        failure: function (form, action) {
                                            Ext.Msg.alert("Error!",action.result.message);
                                        }
                                    });

                                },
                                failure: function (form, action) {
                                    Ext.Msg.alert("Error!",action.result.message);
                                }
                            });

                        },
                        failure: function (form, action) {
                            Ext.Msg.alert("Error!",action.result.message);
                        }
                    });


                }
            },
            {
                xtype: 'button',
                text: 'Cancel',
                id: 'btnCancelnewsurvey',
                handler: function(){
                    Ext.getCmp('setupSurveyWin').destroy();
                }
            }
        ];
        this.items = [
//            {
//                xtype: 'textfield',
//                flex: 1,
//                fieldLabel: 'Survey Name',
//                anchor: '100%',
//                name: 'surveyName',
////                inputType: 'password',
//                id: 'newsurveyname',
//                allowBlank: false
//            },
            {
                xtype: 'combo',
                flex: 1,
                forceSelection: true,
                editable: false,
                id: 'newSurveyToProjectcombo',
                anchor: '94%',
                fieldLabel: 'Survey Project',
                name: 'surveyProject',
                triggerAction: 'all',
                displayField: 'name',
                hiddenName: 'hiddenVariable',
                valueField: 'id',
                mode: 'local',
                store : userProjectStore
            }
        ];
        GeoPortal.Forms.DataEntry.SetupNewSurvey.superclass.initComponent.call(this);
    }
});


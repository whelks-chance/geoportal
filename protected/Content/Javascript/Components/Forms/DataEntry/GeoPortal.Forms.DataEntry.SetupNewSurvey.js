/**
 * Created with JetBrains PhpStorm.
 * User: wiserd
 * Date: 22/08/13
 * Time: 18:36
 * To change this template use File | Settings | File Templates.
 */


GeoPortal.Forms.DataEntry.SetupNewSurvey = Ext.extend(Ext.form.FormPanel, {
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
            id: "userProjectStore",
            root : "usersProjects"
        });

        Ext.Ajax.request({
            url: getUsersProjects,
            method : 'POST',
            params : {
                dublincore_type: true
            },
            success: function(resp) {
                var responseData = Ext.decode(resp.responseText);
                userProjectStore.loadData(responseData);
            },
            failure: function(resp) {
                console.log('failure!');
            }
        });

        this.buttons  =[
            {
                xtype: 'button',
                text: 'Create',
                id: 'btnCreatenewsurvey',
                handler: function(){
                    var frmSurveyName = Ext.getCmp('newsurveyname');
                    var text = frmSurveyName.getValue();

                    var wid = 'wisid_' + text;
                    var sid = 'sid_' + text;
                    var qid = 'qid_' + text;
                    var resid = 'resid_qid_' + text;

                    Ext.getCmp('dcWiserdIDfield').setValue(wid);
                    Ext.getCmp('surveyIDfield').setValue(sid);
                    Ext.getCmp('QuestionSurveyID').setValue(sid);
                    Ext.getCmp('QuestionIdField').setValue(qid);
                    Ext.getCmp('resQuestionIDfield').setValue(qid);
                    Ext.getCmp('responseIdField').setValue(resid);

                    var breadcrumb = Ext.getCmp('breadcrumb');
                    breadcrumb.updateBreadcrumb(wid, sid, qid, resid);

                    var thisPanel = Ext.getCmp('frmSetupSurvey');
                    thisPanel.getForm().submit({
                        url: buildNewSurveyLinks,
                        waitMsg: 'Building Survey structure....',
                        params : {
                            wid: wid,
                            sid: sid
                        },
                        success: function (form, action) {
                            Ext.Msg.alert("Success!",action.result.message);
                        },
                        failure: function (form, action) {
                            Ext.Msg.alert("Error!",action.result.message);
                        }
                    });
                }
            }];
        this.items = [
            {
                xtype: 'textfield',
                flex: 1,
                fieldLabel: 'Survey Name',
                anchor: '100%',
                name: 'surveyName',
//                inputType: 'password',
                id: 'newsurveyname',
                allowBlank: false
            },
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


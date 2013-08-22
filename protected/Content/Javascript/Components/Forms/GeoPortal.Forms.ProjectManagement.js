/**
 * Created with JetBrains PhpStorm.
 * User: wiserd
 * Date: 21/08/13
 * Time: 12:56
 * To change this template use File | Settings | File Templates.
 */

GeoPortal.Forms.ProjectManagement = Ext.extend(Ext.form.FormPanel, {
//    width: 800,
//    height: 800,
    loadMask: true,
    id: 'frmProjectManagement',
    title: 'Project Management',
//    bodyStyle: 'padding: 5px',
    autoScroll: true,
    hideBorders: true,
    layout: {
        type: 'vbox',
        align: 'stretch'
    },
    initComponent: function () {

//        servis.surveyid, servis.visibilitystateid,
//            visstat.visibilitystatename, so.projectid, proj.projectname
        this.surveyProjectVisibilityStore = new Ext.data.JsonStore({
            fields: [
                {name: 'SurveyID', mapping: 'surveyid'},
                {name: 'StateID', mapping: 'visibilitystateid'},
                {name: 'StateName', mapping: 'visibilitystatename'},
                {name: 'ProjectID', mapping: 'projectid'},
                {name: 'ProjectName', mapping: 'projectname'}
            ],
            root: 'surveyVisibility',
            id: 'visibilityStore',
            url: surveyVisibility,
            totalProperty: 'surveyVisibilityTotal'
        });
        this.surveyProjectVisibilityStore.on('load', function(store, recs, opt){
            this.doLayout();
            //update your display here
        }, this);
        this.surveyProjectVisibilityStore.load();


        var visibilityStore = new Ext.data.JsonStore ({
            fields: [
                {name: 'name', mapping: 'vis_name'},
                {name: 'id',  mapping: 'vis_id'}
            ],
            id: "visibilityStore",
            root : "visibility"
        });

        var projectStore = new Ext.data.JsonStore ({
            fields: [
                {name: 'name', mapping: 'projectname'},
                {name: 'id',  mapping: 'projectid'}
            ],
            id: "projectStore",
            root : "projects"
        });

        var userStore = new Ext.data.JsonStore ({
            fields: [
                {name: 'name', mapping: 'username'},
                {name: 'id',  mapping: 'id'}
            ],
            id: "userStore",
            root : "users"
        });

        var surveyStore = new Ext.data.JsonStore ({
            fields: [
                {name: 'name', mapping: 'surveyid'}
//                {name: 'id',  mapping: 'projectid'}
            ],
            id: "surveyStore",
            root : "surveys"
        });

        Ext.Ajax.request({
            url: dataOptionLists,
            method : 'POST',
            params : {
                visibilities: true,
                projects: true,
                surveys: true,
                users: true
            },
            success: function(resp) {
                var responseData = Ext.decode(resp.responseText);
                visibilityStore.loadData(responseData);
                projectStore.loadData(responseData);
                surveyStore.loadData(responseData);
                userStore.loadData(responseData);
            },
            failure: function(resp) {
                console.log('failure!');
            }
        });


        this.bbar = {
            xtype: 'toolbar',
            height: 26,
            items: [
                {
                    xtype: 'tbfill'
                }
            ]
        }

        this.items = [

            {
                xtype: 'container',
                layout: {
                    type: 'column'
                },
                items: [
                    {
                        xtype: 'container',
                        layout: {
                            type: 'form'
                        },
                        columnWidth: 0.5,
                        items: [
                            {
// Create new Project
                                xtype: 'fieldset',
                                flex: 1,
                                title: 'Create New Project',
                                items: [
                                    {
                                        xtype: 'textfield',
                                        id: 'txtprojectid',
                                        fieldLabel: 'Project ID',
                                        name: 'projectid'
                                    },
                                    {
                                        xtype: 'textfield',
                                        id: 'txtprojectname',
                                        fieldLabel: 'Project name',
                                        name: 'projectname'
                                    },
                                    {
                                        xtype: 'button',
                                        id: 'btnSaveNewProject',
                                        icon: 'images/silk/application_form_add.png',
                                        scope: this,
                                        text: 'Create',
                                        handler : function() {
                                            var idField = Ext.getCmp('txtprojectid');
                                            var idVal = idField.getValue();

                                            var projectField = Ext.getCmp('txtprojectname');
                                            var projectName = projectField.getValue();

                                            Ext.Ajax.request({
                                                url: createProject,
                                                method : 'POST',
                                                scope: this,
                                                params : {
                                                    projectID: idVal,
                                                    projectName: projectName
                                                },
                                                success: function(resp) {
                                                    alert("Created project " + projectName);
                                                    projectStore.reload();
                                                },
                                                failure: function(resp) {
                                                    alert("Failed ");
                                                }
                                            });
                                        }
                                    }
                                ]
                            },
                            {
// Add user to project
                                xtype: 'fieldset',
                                flex: 1,
                                title: 'Add User to Project',
                                items: [
                                    {
                                        xtype: 'combo',
                                        forceSelection: true,
                                        editable: false,
                                        id: 'txtuseridtoproject',
                                        fieldLabel: 'User ID',
                                        name: 'userid',
                                        triggerAction: 'all',
                                        displayField: 'name',
                                        hiddenName: 'hiddenVariable',
                                        valueField: 'id',
                                        mode: 'local',
                                        store : userStore
                                    },
                                    {
                                        xtype: 'combo',
                                        forceSelection: true,
                                        editable: false,
                                        id: 'combousertoprojectname',
                                        fieldLabel: 'Project name',
                                        name: 'projectusername',
                                        triggerAction: 'all',
                                        displayField: 'name',
                                        mode: 'local',
                                        store : projectStore
                                    },
                                    {
                                        xtype: 'button',
                                        id: 'btnAddUserToProject',
                                        icon: 'images/silk/application_form_add.png',
                                        text: 'Add',
                                        handler : function() {
                                            var useridField = Ext.getCmp('txtuseridtoproject');
                                            var useridVal = useridField.getValue();

                                            var projectField = Ext.getCmp('combousertoprojectname');
                                            var projectName = projectField.getValue();

                                            Ext.Ajax.request({
                                                url: addUserToProject,
                                                method : 'POST',
                                                scope: this,
                                                params : {
                                                    userID: useridVal,
                                                    projectName: projectName
                                                },
                                                success: function(resp) {
                                                    alert("Added user " + useridVal + " to project " + projectName);
                                                },
                                                failure: function(resp) {
                                                    alert("Failed ");
                                                }
                                            });
                                        }
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        xtype: 'container',
                        layout: {
                            type: 'form'
                        },
                        columnWidth: 0.5,
                        items: [
                            {
//      Add Survey to Project
                                xtype: 'fieldset',
                                flex: 1,
                                title: 'Add Survey to Project',
                                items: [
                                    {

                                        xtype: 'combo',
                                        forceSelection: true,
                                        editable: false,
                                        id: 'txtprojsurveyid',
                                        fieldLabel: 'Survey ID',
                                        name: 'projectid',
                                        triggerAction: 'all',
                                        displayField: 'name',
                                        mode: 'local',
                                        store : surveyStore
                                    },

                                    {
                                        xtype: 'combo',
                                        forceSelection: true,
                                        editable: false,
                                        id: 'combosurprojectname',
//                                        anchor: '94%',
                                        fieldLabel: 'Project name',
                                        name: 'projectname',
                                        triggerAction: 'all',
                                        displayField: 'name',
                                        hiddenName: 'hiddenVariable',
                                        valueField: 'id',
                                        mode: 'local',
                                        store : projectStore
                                    },

                                    {
                                        xtype: 'button',
                                        id: 'btnAddSurveyToProject',
                                        icon: 'images/silk/application_form_add.png',
                                        text: 'Add',
                                        scope: this,
                                        handler : function() {
                                            var surveyCheck = Ext.getCmp('txtprojsurveyid');
                                            var surveyVal = surveyCheck.getValue();

                                            var projectCheck = Ext.getCmp('combosurprojectname');
                                            var projectVal = projectCheck.getValue();

                                            Ext.Ajax.request({
                                                url: addSurveyToProject,
                                                method : 'POST',
                                                scope: this,
                                                params : {
                                                    projectID: projectVal,
                                                    surveyID: surveyVal
                                                },
                                                success: function(resp) {
                                                    alert("Added " + surveyVal + " to " + projectVal)
                                                    this.surveyProjectVisibilityStore.reload();
                                                },
                                                failure: function(resp) {
                                                    alert("Failed ");
                                                }
                                            });
                                        }
                                    }
                                ]
                            },
                            {
// set Survey visibility
                                xtype: 'fieldset',
                                flex: 1,
                                title: 'Survey Visibility',
                                items: [
                                    {

                                        xtype: 'combo',
                                        forceSelection: true,
                                        editable: false,
                                        id: 'txtsurveyidvis',
                                        fieldLabel: 'Survey ID',
                                        name: 'txtsurveyidvis',
                                        triggerAction: 'all',
                                        displayField: 'name',
                                        mode: 'local',
                                        store : surveyStore
                                    },

                                    {
                                        xtype: 'combo',
                                        forceSelection: true,
                                        editable: false,
                                        id: 'visibilityCombo',
//                                        anchor: '94%',
                                        fieldLabel: 'Visibility',
                                        name: 'surveyVisibility',
                                        triggerAction: 'all',
                                        displayField: 'name',
                                        hiddenName: 'hiddenVariable',
                                        valueField: 'id',
                                        mode: 'local',
                                        store : visibilityStore
                                    },

                                    {
                                        xtype: 'button',
                                        id: 'btnsetsurveyvisibility',
                                        icon: 'images/silk/application_form_add.png',
                                        text: 'Set',
                                        scope: this,
                                        handler : function() {
                                            var surveyCombo = Ext.getCmp('txtsurveyidvis');
                                            var surveyVal = surveyCombo.getValue();

                                            var visibilityCombo = Ext.getCmp('visibilityCombo');
                                            var visibilityComboVal = visibilityCombo.getValue();

                                            Ext.Ajax.request({
                                                url: changeSurveyVisibility,
                                                method : 'POST',
                                                scope: this,
                                                params : {
                                                    visibilityID: visibilityComboVal,
                                                    surveyID: surveyVal
                                                },
                                                success: function(resp) {
                                                    alert("Set " + surveyVal + " visibility to " + visibilityComboVal)
                                                    this.surveyProjectVisibilityStore.reload();
                                                },
                                                failure: function(resp) {
                                                    alert("Failed ");
                                                }
                                            });
                                        }
                                    }
                                ]
                            }
                        ]
                    }
                ]
            },
            {
                xtype: 'grid',
                flex: 3,
//                region: 'south',
//                height: '150',
                frame: true,
                id: 'grdRemoteResults',
                store: this.surveyProjectVisibilityStore,
                loadMask: true,
                stripeRows: true,
                totalProperty: 'surveyVisibilityTotal',
                viewConfig: {
                    forceFit: true
//                    autoFit : true
                },
                columns: [
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'SurveyID',
                        header: 'Survey ID',
                        sortable: true
//                                width: 50
                    },
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'StateID',
                        header: 'Visibility State ID',
                        sortable: true,
                        width: 50
                    },
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'StateName',
                        header: 'Visibility State Name',
                        sortable: true,
                        width: 80
                    },
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'ProjectID',
                        header: 'Project ID',
                        sortable: true,
                        width: 80
                    },
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'ProjectName',
                        header: 'Project Name',
                        sortable: true,
                        width: 100
                    }
                ]
            }
        ];
        GeoPortal.Forms.ProjectManagement.superclass.initComponent.call(this);
    }
});
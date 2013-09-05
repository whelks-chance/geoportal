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
            id: 'surveyProjectVisibilityStore',
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

        this.projectStore = new Ext.data.JsonStore ({
            fields: [
                {name: 'name', mapping: 'projectname'},
                {name: 'id',  mapping: 'projectid'}
            ],
            baseParams : {
                projects: true
            },
            url: dataOptionLists,
            id: "projectStore",
            root : "projects"
        });
        this.projectStore.on('load', function(store, recs, opt){
            this.doLayout();
            //update your display here
        }, this);
        this.projectStore.load();


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

        var roleStore = new Ext.data.JsonStore ({
            fields: [
                {name: 'name'}
//                {name: 'id',  mapping: 'projectid'}
            ],
            id: "roleStore",
            root : "roles.allRoles"
        });

        Ext.Ajax.request({
            url: dataOptionLists,
            method : 'POST',
            scope: this,
            params : {
                visibilities: true,
                roles: true,
                surveys: true,
                users: true
            },
            success: function(resp) {
                var responseData = Ext.decode(resp.responseText);
                visibilityStore.loadData(responseData);
                roleStore.loadData(responseData);
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
                    xtype: 'button',
                    text: 'Refresh',
                    id: 'btnRefreshProjectManagement',
                    scope: this,
                    handler: function(){
                        this.projectStore.reload();
                        this.surveyProjectVisibilityStore.reload();
                    }
                },

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
                                                    this.projectStore.reload();
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
                                        hiddenName: 'hiddenVariable',
                                        valueField: 'id',
                                        mode: 'local',
                                        store : this.projectStore
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
                                            var projectID = projectField.getValue();

                                            Ext.Ajax.request({
                                                url: addUserToProject,
                                                method : 'POST',
                                                scope: this,
                                                params : {
                                                    userID: useridVal,
                                                    projectID: projectID
                                                },
                                                success: function(resp) {
                                                    alert("Added user " + useridVal + " to project " + projectID);
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
// Add user to role
                                xtype: 'fieldset',
                                flex: 1,
                                title: 'Change User Role',
                                items: [
                                    {
                                        xtype: 'combo',
                                        forceSelection: true,
                                        editable: false,
                                        id: 'txtuseridtorole',
                                        fieldLabel: 'User Name',
                                        name: 'userid',
                                        triggerAction: 'all',
                                        displayField: 'name',
//                                        hiddenName: 'hiddenVariable',
//                                        valueField: 'id',
                                        mode: 'local',
                                        store : userStore
                                    },
                                    {
                                        xtype: 'combo',
                                        forceSelection: true,
                                        editable: false,
                                        id: 'comboroleforuser',
                                        fieldLabel: 'Role name',
                                        name: 'rolename',
                                        triggerAction: 'all',
                                        displayField: 'name',
//                                        hiddenName: 'hiddenVariable',
//                                        valueField: 'id',
                                        mode: 'local',
                                        store : roleStore
                                    },
                                    {
                                        xtype: 'button',
                                        id: 'btnAddUserToProject',
                                        icon: 'images/silk/application_form_add.png',
                                        text: 'Add',
                                        handler : function() {
                                            var useridField = Ext.getCmp('txtuseridtorole');
                                            var useridVal = useridField.getValue();

                                            var roleField = Ext.getCmp('comboroleforuser');
                                            var roleName = roleField.getValue();

                                            Ext.Ajax.request({
                                                url: addUserToRole,
                                                method : 'POST',
                                                scope: this,
                                                params : {
                                                    userID: useridVal,
                                                    roleName: roleName
                                                },
                                                success: function(resp) {
                                                    alert("Added user " + useridVal + " to role " + roleName);
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
                                        store : this.projectStore
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
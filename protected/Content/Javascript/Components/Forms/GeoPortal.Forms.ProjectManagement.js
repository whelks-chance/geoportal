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
    title: 'ProjectManagement',
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
                surveys: true
            },
            success: function(resp) {
                var responseData = Ext.decode(resp.responseText);
                visibilityStore.loadData(responseData);
                projectStore.loadData(responseData);
                surveyStore.loadData(responseData);
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
                                        text: 'Create',
                                        handler : function() {

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
                                        xtype: 'textfield',
                                        id: 'txtuseridtoproject',
                                        fieldLabel: 'User ID',
                                        name: 'userid'
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
                                        handler : function() {
                                            var surveyCheck = Ext.getCmp('txtprojsurveyid');
                                            var surveyVal = surveyCheck.getValue();

                                            var projectCheck = Ext.getCmp('combosurprojectname');
                                            var projectVal = projectCheck.getValue();

                                            Ext.Ajax.request({
                                                url: addSurveyToProject,
                                                method : 'POST',
                                                params : {
                                                    projectID: projectVal,
                                                    surveyID: surveyVal
                                                },
                                                success: function(resp) {
                                                    alert("Added " + surveyVal + " to " + projectVal)
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
//                            hiddenName: 'hiddenVariable',
//                            valueField: 'id',
                                        mode: 'local',
                                        store : visibilityStore
                                    },

                                    {
                                        xtype: 'button',
                                        id: 'btnsetsurveyvisibility',
                                        icon: 'images/silk/application_form_add.png',
                                        text: 'Set',
                                        handler : function() {

                                        }
                                    }
                                ]
                            }
                        ]
                    }
                ]
            },
//            {
//                xtype: 'fieldset',
//                flex: 1,
////                layout: 'hbox',
//                title: 'Project',
//                items: [
//                    {
//                        xtype: 'textfield',
//                        id: 'txtprojectid',
////                        anchor: '100%',
//                        fieldLabel: 'Project ID',
//                        name: 'projectid'
//                    },
//
//                    {
//                        xtype: 'textfield',
//                        id: 'txtprojectname',
////                        anchor: '100%',
//                        fieldLabel: 'Project name',
//                        name: 'projectname'
//                    },
//
//                    {
//                        xtype: 'button',
//                        id: 'btnSaveNewProject',
//                        icon: 'images/silk/application_form_add.png',
//                        text: 'Save',
//                        handler : function() {
//
//                        }
//                    }
//                ]
//            },
//            {
//                xtype: 'fieldset',
//                flex: 1,
////                layout: 'vbox',
//                title: 'User',
//                items: [
//                    {
//                        xtype: 'textfield',
//                        id: 'txtuserid',
////                        anchor: '100%',
//                        fieldLabel: 'User ID',
//                        name: 'userid'
//                    },
//
//                    {
//                        xtype: 'textfield',
//                        id: 'txtprojecttousername',
////                        anchor: '100%',
//                        fieldLabel: 'Project name',
//                        name: 'projectusername'
//                    },
//
//                    {
//                        xtype: 'button',
//                        id: 'btnSaveUserToProject',
//                        icon: 'images/silk/application_form_add.png',
//                        text: 'Save',
//                        handler : function() {
//
//                        }
//                    }
//                ]
//            },
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
                        header: 'State ID',
                        sortable: true,
                        width: 50
                    },
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'StateName',
                        header: 'State Name',
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
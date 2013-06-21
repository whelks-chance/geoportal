GeoPortal.Forms.AdvancedSearch = Ext.extend(Ext.form.FormPanel, {
    width: Ext.getBody().getViewSize().width * 0.8,
    height: Ext.getBody().getViewSize().height * 0.9,
//    closable: true,
//    height: '100%',
//    padding: 10,
    loadMask: true,
    loadMsg: 'loading.....',
    autoScroll: true,
    title: 'Advanced Search',
    id: 'advPanel',
    geographyRegion: null,
    doAdvancedSearch: function () {
        console.log('geog1 ' + this.geographyRegion);

        this.on({
            'minimize': {
                fn: this.min
            }
        });

        var advPanel = Ext.getCmp('advPanel');
        var loadMask = new Ext.LoadMask(Ext.getBody(), {msg:"Retrieving Search Results...."});
        loadMask.show();

        var spatialCheck = Ext.getCmp('cbSpatial');
        var spatialVal = spatialCheck.getValue();

        var geographyField = Ext.getCmp('geographyText');
        var geographyValue = geographyField.getValue();

        console.log('spatial' + spatialVal)

        if (spatialVal === true) {

            console.log('geography');

            advPanel.getForm().submit({
                url: advancedSpatialSearchURL,
                timeout: 120,
                method: 'post',
                scope: this,
                params: {
                    dateFrom: advPanel.getForm().getValues().dteFrom,
                    dateTo: advPanel.getForm().getValues().dteTo
//                    geography: geographyValue
                },
                success: function (form, action) {
                    if (Ext.getCmp('spatResWin') != null) {

                        Ext.getCmp('spatResWin').close();
                    }

                    var searchResults = new GeoPortal.Windows.SpatialResults(
                        {
                            animateTarget:Ext.getCmp('minSS').el
                        }
                    );

                    searchResults.doLayout();
                    searchResults.qualStore.proxy = new Ext.data.HttpProxy({ api:{
                        read:advancedSpatialSearchURL
                    }
                    });
                    searchResults.resStore.proxy = new Ext.data.HttpProxy({ api:{
                        read:advancedSpatialSearchURL
                    }
                    });
                    var responseData = Ext.decode(action.response.responseText);

                    searchResults.qualStore.loadData(responseData);
                    searchResults.resStore.loadData(responseData);
                    searchResults.show();
                    loadMask.hide();

                },
                failure: function (form, action) {
                    switch (action.failureType) {
                        case Ext.form.Action.CLIENT_INVALID:
                            Ext.Msg.alert('Failure', 'Form contains invalid values.');
                            break;
                        case Ext.form.Action.CONNECT_FAILURE:
                            Ext.Msg.alert('Failure', 'Communication failure - please try again');
                            break;
                        case Ext.form.Action.SERVER_INVALID:
                            Ext.Msg.alert('Failure', 'Error with username or password, please check and try again.');
                            break;
                        default:
                            Ext.Msg.alert("Error", 'Service failure, please consider posting a bug report');
                    }
                }
            })
        } else {
            console.log('no geog');

            advPanel.getForm().submit({
                url: advancedSearchURL,
                timeout: 120,
                method: 'post',
                scope: this,
                params: {
                    dateFrom: advPanel.getForm().getValues().dteFrom,
                    dateTo: advPanel.getForm().getValues().dteTo
//                geography: this.geographyRegion
                },
                success: function (form, action) {
//                    Ext.getCmp("advSearch").hide();

                    var textBox = Ext.getCmp('txtAdvKeyword');
                    var val = textBox.getValue();

                    var resWin = new GeoPortal.Windows.Results({ title:'Results - Search terms "' + val + '"' });
                    resWin.qualStore.removeAll();
                    resWin.resStore.removeAll();

                    resWin.qualStore.proxy = new Ext.data.HttpProxy({ api:{
                        read:advancedSearchURL
                    }
                    });
                    resWin.resStore.proxy = new Ext.data.HttpProxy({ api:{
                        read:advancedSearchURL
                    }
                    });

                    var responseData = Ext.decode(action.response.responseText);

                    resWin.qualStore.loadData(responseData);
                    resWin.resStore.loadData(responseData);
                    resWin.show();
                    loadMask.hide();

                },
                failure: function (form, action) {
                    console.log(action);
                    Ext.Msg.alert("Error", action.result.message);
                }
            })
        }
    },
    FormReset : function() {
        var advPanel = Ext.getCmp('advPanel');
        advPanel.getForm().reset();
    },
    initComponent: function () {
        var surveyStore = new Ext.data.JsonStore ({
            fields: [
                {name: 'SurveyName', mapping: 'SurveyName'},
                {name: 'SurveyID',  mapping: 'SurveyID'}],
            id: "surveyStore",
            root : "surveyData"
        });

        var thematicStore = new Ext.data.JsonStore ({
            fields: [
                {name: 'theme', mapping: 'grouptitle'},
                {name: 'ID',  mapping: 'tgroupid'},
                {name: 'description', mapping: 'groupdescription'}
            ],
            id: "thematicStore",
            root : "thematicData"
        });

//        var geographyArea = new Ext.form.TextArea
        Ext.Ajax.request({
            url: advancedSearchMetaURL,
            method : 'POST',
            success: function(resp) {
                var responseData = Ext.decode(resp.responseText);
                console.log(responseData);
                surveyStore.loadData(responseData);
                thematicStore.loadData(responseData);
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
                    id: 'btnReset',
                    icon: 'images/silk/arrow_rotate_clockwise.png',
                    text: 'Reset Form',
                    type: 'reset',
                    handler : this.FormReset,
                    scope : this
                },
                {
                    xtype: 'tbfill'
                },
                {
                    xtype: 'button',
                    id: 'btnAdvSearchFrm',
                    icon: 'images/silk/magnifier.png',
                    text: 'Search',
                    tooltip: 'Submit Search',
                    scope: this,
                    handler: this.doAdvancedSearch
                },
                {
                    xtype: 'button',
                    id: 'btnSave',
                    icon: 'images/silk/disk.png',
                    text: 'Save'
                }
            ]
        };
        this.items = [
            {
                xtype: 'fieldset',
                title: 'Keywords',
                items: [
                    {
                        xtype: 'textfield',
                        id: 'txtAdvKeyword',
                        emptyText: 'Comma separated keywords....',
                        anchor: '100%',
                        fieldLabel: 'Keywords',
                        name: 'keywords'
                    },
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
                                        xtype: 'checkbox',
                                        id: 'cbTitle',
                                        checked : true,
                                        anchor: '100%',
                                        fieldLabel: 'Title'
                                    },
                                    {
                                        xtype: 'checkbox',
                                        id: 'cbDescription',
                                        checked : true,
                                        anchor: '100%',
                                        fieldLabel: 'Description'
                                    }
                                ]
                            },{
                                xtype: 'container',
                                layout: {
                                    type: 'form'
                                },
                                columnWidth: 0.5,
                                items: [
                                    {
                                        xtype: 'checkbox',
                                        id: 'cbSrchDescription',
                                        checked : true,
                                        anchor: '100%',
                                        fieldLabel: 'Tags'
                                    }
                                ]
                            }
                        ]
                    }
                ]
            },
            {
                xtype: 'fieldset',
                title: 'Spatial',
                items: [
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
                                columnWidth: 0.2,
                                items: [
                                    {
                                        xtype: 'checkbox',
                                        id: 'cbSpatial',
                                        checked : false,
                                        anchor: '100%',
                                        fieldLabel: 'Limit search within area'
                                    }
                                ]
                            },
                            {
                                xtype: 'container',
                                layout: {
                                    type: 'form'
                                },
                                columnWidth: 0.8,
                                items: [
                                    {
                                        xtype: 'textfield',
                                        id: 'geographyText',
                                        emptyText: 'Spatial description (Advanced)',
                                        anchor: '100%',
                                        value: this.geographyRegion,
//                                        fieldLabel: 'Spatial description',
                                        name: 'geography'
                                    }
                                ]
                            }
                        ]
                    }
                ]
            },
            {
                xtype: 'fieldset',
                layout: {
                    type: 'column'
                },
                title: 'Date Range',
                items: [
                    {
                        xtype: 'datefield',
                        id: 'dteFrom',
                        emptyText: '',
                        format: 'Y/m/d',
                        columnWidth: 0.5,
                        name: 'dateFrom',
                        fieldLabel : 'Date From'
                    },
                    {
                        xtype: 'datefield',
                        id: 'dteTo',
                        emptyText: '',
                        columnWidth: 0.5,
                        format: 'Y/m/d',
                        name: 'dateTo',
                        fieldLabel : 'Date To'
                    }
                ]
            },
            {
                xtype: 'fieldset',
//                height: 90,
                title: 'Search Within Survey Series',
                items: [
                    {
                        xtype: 'combo',
                        tpl: '<tpl for="."><div ext:qtip="{SurveyName} - {SurveyID}" class="x-combo-list-item">{SurveyName}</div></tpl>',
                        id: 'cmboSurvey',
                        anchor: '100%',
                        fieldLabel: 'Select Survey',
                        name: 'Survey',
//                        editable : false,
                        triggerAction: 'all',
                        displayField: 'SurveyName',
                        hiddenName: 'SurveysId',
                        valueField: 'SurveyName',
                        mode: 'local',
                        store : surveyStore
                    }, {
                        xtype: 'combo',
                        tpl: '<tpl for="."><div ext:qtip="{theme} - {ID}" class="x-combo-list-item">{description}</div></tpl>',
                        id: 'cmboThematic',
                        anchor: '100%',
                        fieldLabel: 'Thematic Group',
                        name: 'Thematic',
                        hiddenName: 'ThematicID',
                        displayField: 'description',
                        valueField: 'theme',
                        mode: 'local',
                        triggerAction: 'all',
                        store : thematicStore
                    }
                ]
            },
            {
                xtype: 'container',
                id: 'cntFields',
                layout: {
                    defaultAnchor: '100%',
                    type: 'form'
                },
                items: [
                    {
                        xtype: 'fieldset',
//                                        width: 752,
                        title: 'Data Types',
                        items: [
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
                                                xtype: 'checkbox',
                                                id: 'cbSurvey',
                                                anchor: '100%',
                                                checked : true,
                                                fieldLabel: 'Survey'

                                            },
                                            {
                                                xtype: 'checkbox',
                                                id: 'cbGreyData',
                                                anchor: '100%',
                                                fieldLabel: 'Grey Data'
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
                                                xtype: 'checkbox',
                                                id: 'cbQual',
                                                anchor: '100%',
                                                checked : true,
                                                fieldLabel: 'Qualitative Data'
                                            },
                                            {
                                                xtype: 'checkbox',
                                                id: 'cbAdmin',
                                                anchor: '100%',
                                                fieldLabel: 'Admin Data'
                                            }
                                        ]
                                    }
                                ]
                            }
                        ]
                    }
                ]
            }
        ];
        GeoPortal.Forms.AdvancedSearch.superclass.initComponent.call(this);
    }
});



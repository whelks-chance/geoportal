GeoPortal.Forms.AdvancedSearch = Ext.extend(Ext.form.FormPanel, {
    width: Ext.getBody().getViewSize().width * 0.8,
    height: Ext.getBody().getViewSize().height * 0.8,
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
        this.on({
            'minimize': {
                fn: this.min
            }
        });

        var advPanel = Ext.getCmp('advPanel');
        var loadMask = new Ext.LoadMask(Ext.getBody(), {msg:"Retrieving Search Results...."});
        loadMask.show();

        advPanel.getForm().submit({
            url: advancedSearchURL,
            timeout: 120,
            method: 'post',
            params: {
                dateFrom: advPanel.getForm().getValues().dteFrom,
                dateTo: advPanel.getForm().getValues().dteTo
            },
            success: function (form, action) {
                Ext.getCmp("advSearch").hide();

                var textBox = Ext.getCmp('txtAdvKeyword');
                var val = textBox.getValue();

                if (this.geographyRegion) {
                    console.log('geography');

//                    var loadMask = new Ext.LoadMask(Ext.getBody(), { msg: "Retrieving Spatial Search Results....", removeMask: false });
//                    loadMask.show();

                    if (Ext.getCmp('spatResWin') != null) {

                        Ext.getCmp('spatResWin').close();
                    }

                    var searchResults = new GeoPortal.Windows.SpatialResults(
                        {
                            animateTarget:Ext.getCmp('minSS').el
                        }
                    );

                    var grdqual = Ext.getCmp('grdQual');
                    var grid = Ext.getCmp('grdSurvey');

                    searchResults.doLayout();
                    grdqual.store.load(
                        {
                            params:{
                                geography:this.geographyRegion,
                                start:0,
                                limit:15,
                                type:'Qual'
                            },
                            scope:this,
                            callback:function () {
                                searchResults.doLayout();
                                grid.store.load(
                                    {
                                        params:{
                                            start:0,
                                            limit:15,
                                            type:'Quant'
                                        },
                                        scope:this,
                                        callback:function () {
                                            console.log(searchResults);
                                            searchResults.doLayout();
                                            searchResults.show();
                                            loadMask.hide();
                                        }
                                    }
                                );

                            }
                        }
                    );
                } else {
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

                    console.log('adv response : ' + responseData);

                    resWin.qualStore.loadData(responseData);
                    resWin.resStore.loadData(responseData);
                    resWin.show();
                    loadMask.hide();

//                    resWin.qualStore.load({params:{keywords:val, start:0, limit:15, cbQual:'on'}});
//                    resWin.resStore.load({ url:advancedSearchURL, params:{ keywords:val, start:0, limit:15, mappable:false, cbSurvey:'on' }, callback:function () {
//                        resWin.show();
//                        loadMask.hide();
//                    }
//                    });
                }
            },
            failure: function (form, action) {
                console.log(action);
                Ext.Msg.alert("Error", action.result.message);
            }
        })

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
                {name: 'ID',  mapping: 'tgroupid'}],
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
                layout: {
                    type: 'column'
                },
                title: 'Date Range',
                items: [
                    {
                        xtype: 'datefield',
                        id: 'dteFrom',
                        emptyText: 'From...',
                        format: 'Y/m/d',
                        columnWidth: 0.5,
                        name: 'dateFrom',
                        fieldLabel : 'dateFrom'
                    },
                    {
                        xtype: 'datefield',
                        id: 'dteTo',
                        emptyText: 'To....',
                        columnWidth: 0.5,
                        format: 'Y/m/d',
                        name: 'dateTo',
                        fieldLabel : 'dateTo'
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
                        tpl: '<tpl for="."><div ext:qtip="{theme} - {ID}" class="x-combo-list-item">{theme}</div></tpl>',
                        id: 'cmboThematic',
                        anchor: '100%',
                        fieldLabel: 'Thematic Group',
                        name: 'Thematic',
                        hiddenName: 'ThematicID',
                        displayField: 'theme',
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
            },
            {
                xtype: 'container',
                id: 'geography',
                layout: {
                    defaultAnchor: '100%',
                    type: 'form'
                },
                items: [
                    {
                        fieldLabel      : 'geographyarea',
                        id              : 'geoarea',
                        name            : 'csv',
                        xtype           : 'textarea',
                        autoScroll      : true,
                        height          : 60,
                        anchor: '100%'
                    }
                ]
            }
        ];
        console.log(this.geographyRegion);
        GeoPortal.Forms.AdvancedSearch.superclass.initComponent.call(this);
    }
});



GeoPortal.Forms.AdvancedSearch = Ext.extend(Ext.form.FormPanel, {
    width: 800,
    closable: true,
    padding: 10,
    //title: 'Advanced Search',
    doAdvancedSearch: function () {

                           
              
    
    } ,
    initComponent: function () {
        this.bbar = {
            xtype: 'toolbar',
            height: 26,
            items: [
                {
                    xtype: 'button',
                    id: 'btnReset',
                    icon: 'images/silk/arrow_rotate_clockwise.png',
                    text: 'Reset Form',
                    type: 'reset'
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
                    handler: this.doAdvancedSearch,
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
                        name: 'Keywords'
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
                        format: 'd/m/Y',
                        columnWidth: 0.5,
                        name: 'dateFrom'
                    },
                    {
                        xtype: 'datefield',
                        id: 'dteTo',
                        emptyText: 'To....',
                        columnWidth: 0.5,
                        name: 'dateTo'
                    }
                ]
            },
            {
                xtype: 'fieldset',
                height: 90,
                title: 'Search Within Survey Series',
                items: [
                    {
                        xtype: 'combo',
                        id: 'cmboSurvey',
                        anchor: '100%',
                        fieldLabel: 'Select Survey',
                        name: 'Survey'
                    },
                    {
                        xtype: 'combo',
                        id: 'cmboThematic',
                        anchor: '100%',
                        fieldLabel: 'Thematic Group',
                        name: 'Thematic'
                    }
                ]
            },
            {
                xtype: 'fieldset',
                title: 'Search Options',
                items: [
                    {
                        xtype: 'container',
                        id: 'holder',
                        items: [
                            {
                                xtype: 'container',
                                id: 'cntFields',
                                layout: {
                                    defaultAnchor: '95%',
                                    type: 'form'
                                },
                                items: [
                                    {
                                        xtype: 'fieldset',
                                        width: 752,
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
                                    },
                                    {
                                        xtype: 'fieldset',
                                        width: 752,
                                        title: 'Keyword Search:',
                                        items: [
                                            {
                                                xtype: 'container',
                                                items: [
                                                    {
                                                        xtype: 'container',
                                                        layout: {
                                                            type: 'form'
                                                        },
                                                        items: [
                                                            {
                                                                xtype: 'checkbox',
                                                                id: 'cbTitle',
                                                                anchor: '100%',
                                                                fieldLabel: 'Title'
                                                            },
                                                            {
                                                                xtype: 'checkbox',
                                                                id: 'cbDescription',
                                                                anchor: '100%',
                                                                fieldLabel: 'Description'
                                                            },
                                                            {
                                                                xtype: 'checkbox',
                                                                id: 'cbSrchDescription',
                                                                anchor: '100%',
                                                                fieldLabel: 'Tags'
                                                            }
                                                        ]
                                                    },
                                                    {
                                                        xtype: 'container',
                                                        layout: {
                                                            type: 'form'
                                                        }
                                                    }
                                                ]
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



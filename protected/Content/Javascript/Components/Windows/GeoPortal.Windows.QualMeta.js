GeoPortal.Windows.QualMetaData = Ext.extend(Ext.Window, {
    width: 800,
    SID: null,
    height: Ext.getBody().getViewSize().height * 0.8,
    id: 'QualMetaWin',
    maximizable: true,
    autoScroll: true,
//    resizable: false,
    initComponent: function () {
        this.chartPanel = new GeoPortal.Charts.QualChart({ ID: this.SID }); //, place1: Ext.getCmp('cmboPlace1').getValue(), place2: Ext.getCmp('place2').getValue(), place3: Ext.getCmp('place3').getValue() });
        this.PlaceStore = new Ext.data.JsonStore({
            fields: ['place'],
            root: 'data',
            id: 'qual_places',
            url: getPlaces,
            autoLoad: false,
            baseParams: { ID: this.SID },
            listeners: {
                load: function () {
                    Ext.getCmp('cmboPlace1').setValue(this.getAt(0).data.place);
                    Ext.getCmp('place2').setValue(this.getAt(1).data.place);
                    Ext.getCmp('place3').setValue(this.getAt(2).data.place);
                }
            }
        });
        this.items = [
            {
                xtype: 'tabpanel',
                activeTab: 0,
                items: [
                   {
                       xtype: 'panel',
                       title: 'Dublin Core',
                       autoScroll: true,
                       items: [
                            {
                                xtype: 'form',
                                title: 'formQDC',
                                id: 'frmQDC',
                                headerAsText: false,
                                padding: 5,
                                labelWidth: 75,
                                bodyCssClass: 'text-align:center',
                                autoScroll: true,
                                items: [
                                    {
                                        xtype: 'fieldset',
                                        title: 'Dublin Core',
                                        defaults: { readOnly: true, labelStyle: 'font-weight:bold;' },
                                        collapsible: true,
                                        items: [
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'WISERD ID',
                                                anchor: '97%',
                                                name: 'dcWiserdID'
                                            },
                                            {
                                                xtype: 'textarea',
                                                anchor: '97%',
                                                fieldLabel: 'Title',
                                                name: 'dcTitle',
                                                autoHeight: true
                                            },
                                            {
                                                xtype: 'textarea',
                                                anchor: '97%',
                                                fieldLabel: 'Subject',
                                                name: 'dcSubject',
                                                autoHeight: true
                                            },
                                            {
                                                xtype: 'textarea',
                                                anchor: '97%',
                                                fieldLabel: 'Description',
                                                name: 'dcDescription',
                                                autoHeight: true
                                            },
                                            {
                                                xtype: 'container',
                                                layout: 'column',
                                                cls: '',
                                                anchor: '97%',
                                                autoWidth: true,
                                                items: [
                                                    {
                                                        xtype: 'container',
                                                        columnWidth: 0.5,
                                                        layout: 'form',
                                                        defaults: { readOnly: true, labelStyle: 'font-weight:bold;' },
                                                        labelWidth: 75,
                                                        items: [
                                                            {
                                                                xtype: 'textfield',
                                                                name: 'dcDate',
                                                                anchor: '94%',
                                                                fieldLabel: 'Date'
                                                            }
                                                        ]
                                                    },
                                                    {
                                                        xtype: 'container',
                                                        columnWidth: 0.5,
                                                        layout: 'form'
                                                    }
                                                ]
                                            },
                                            {
                                                xtype: 'container',
                                                layout: 'column',
                                                anchor: '97%',
                                                autoWidth: true,
                                                items: [
                                                    {
                                                        xtype: 'container',
                                                        columnWidth: 0.5,
                                                        layout: 'form',
                                                        defaults: { readOnly: true, labelStyle: 'font-weight:bold;' },
                                                        labelWidth: 75,
                                                        items: [
                                                            {
                                                                xtype: 'textfield',
                                                                name: 'dcType',
                                                                anchor: '94%',
                                                                fieldLabel: 'Type'
                                                            }
                                                        ]
                                                    },
                                                    {
                                                        xtype: 'container',
                                                        columnWidth: 0.5,
                                                        defaults: { readOnly: true, labelStyle: 'font-weight:bold;' },
                                                        layout: 'form',
                                                        items: [
                                                            {
                                                                xtype: 'textfield',
                                                                fieldLabel: 'Format',
                                                                anchor: '94%',
                                                                name: 'dcFormat'
                                                            }
                                                        ]
                                                    }
                                                ]
                                            },
                                            {
                                                xtype: 'textarea',
                                                anchor: '97%',
                                                fieldLabel: 'Creator',
                                                name: 'dcCreator',
                                                autoHeight: true
                                            },
                                            {
                                                xtype: 'textarea',
                                                anchor: '97%',
                                                fieldLabel: 'Publisher',
                                                name: 'dcPublisher',
                                                autoHeight: true
                                            },
                                            {
                                                xtype: 'textarea',
                                                anchor: '97%',
                                                fieldLabel: 'Contributor',
                                                name: 'dcContributor',
                                                autoHeight: true
                                            },
                                            {
                                                xtype: 'textarea',
                                                anchor: '97%',
                                                fieldLabel: 'Source',
                                                name: 'dcSource',
                                                autoHeight: true
                                            },
                                            {
                                                xtype: 'textarea',
                                                anchor: '97%',
                                                fieldLabel: 'Rights',
                                                name: 'dcRights',
                                                autoHeight: true
                                            },
                                            {
                                                xtype: 'textarea',
                                                anchor: '97%',
                                                fieldLabel: 'Coverage',
                                                name: 'dcCoverage',
                                                autoHeight: true
                                            },
                                            {
                                                xtype: 'container',
                                                layout: 'column',
                                                anchor: '97%',
                                                items: [
                                                    {
                                                        xtype: 'container',
                                                        layout: 'form'
                                                    },
                                                    {
                                                        xtype: 'container'
                                                    }
                                                ]
                                            },
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'Language',
                                                anchor: '97%',
                                                name: 'dcLanguage'
                                            },
                                            {
                                                xtype: 'textarea',
                                                anchor: '97%',
                                                fieldLabel: 'Relation',
                                                name: 'dcRelation',
                                                autoHeight: true
                                            }
                                        ]
                                    }
                                ]
                            }
                        ]
                   },
                    {
                        xtype: 'panel',
                        title: 'Tags',
                        height: 770,
                        items: [new GeoPortal.Charts.QualCloud({ ID: this.SID })]
                    },
                    {
                        xtype: 'panel',
                        title: 'Stats',
                        height: 770,
                        items: [{ xtype: 'panel',
                            title: 'Statistics',
                            id: 'stats',
                            autoLoad: true,
                            items: [{ xtype: 'panel',
                                bodyStyle: 'padding: 5px',
                                items: [{ xtype: 'container',
                                    layout: 'column',
                                    items: [{ xtype: 'container',
                                        layout: 'form',
                                        labelWidth: 75,
                                        columnWidth: '.3',
                                        items: [{
                                            xtype: 'combo',
                                            fieldLabel: 'Place1',
                                            anchor: '95%',
                                            store: this.PlaceStore,
                                            name: 'place1',
                                            mode: 'remote',
                                            displayField: 'place',
                                            valueField: 'place',
                                            triggerAction: 'all',
                                            lastQuery: '',
                                            id: 'cmboPlace1',
                                            forceSelection: true
                                        }]
                                    }, { xtype: 'container',
                                        layout: 'form',
                                        columnWidth: '.3',
                                        labelWidth: 75,
                                        items: [{
                                            xtype: 'combo',
                                            fieldLabel: 'Place2',
                                            anchor: '95%',
                                            store: this.PlaceStore,
                                            name: 'place2',
                                            mode: 'remote',
                                            displayField: 'place',
                                            valueField: 'place',
                                            triggerAction: 'all',
                                            lastQuery: '',
                                            id: 'place2',
                                            forceSelection: true
                                        }]
                                    },
                                      { xtype: 'container',
                                          layout: 'form',
                                          columnWidth: '.3',
                                          labelWidth: 50,
                                          items: [{
                                              xtype: 'combo',
                                              fieldLabel: 'Place3',
                                              anchor: '95%',
                                              store: this.PlaceStore,
                                              name: 'place',
                                              mode: 'remote',
                                              displayField: 'place',
                                              valueField: 'place',
                                              triggerAction: 'all',
                                              lastQuery: '',
                                              id: 'place3',
                                              forceSelection: true
                                          }]
                                      }, {
                                          xtype: 'button',
                                          text: 'Redraw',
                                          handler: function () {

                                              var SID = Ext.getCmp('QualMetaWin').SID;

                                               var charts = Ext.getCmp('qualchart');

                                              Ext.getCmp('stats').remove(charts);

                                              var newChart = new GeoPortal.Charts.QualChart({ ID: SID });

                                              Ext.getCmp('stats').add(newChart);

                                              Ext.getCmp('stats').doLayout();


                                          }
                                      }]
                                }]
                            }, this.chartPanel]

                        }]
                    }]

            }

        ];
        GeoPortal.Windows.QualMetaData.superclass.initComponent.call(this);
        this.PlaceStore.load();

    }
});

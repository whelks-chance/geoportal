/**
 * Created with JetBrains PhpStorm.
 * User: wiserd
 * Date: 07/08/13
 * Time: 13:49
 * To change this template use File | Settings | File Templates.
 */
Ext.ns('GeoPortal.Windows');
GeoPortal.Windows.TaggingResults = Ext.extend(Ext.Window, {
    title: 'Tagging Results',
    id: 'tagWin',
    layout: 'fit',
    width: 800,
    tagStore: null,
    wordCountStore: null,
    wid: '',
    height: Ext.getBody().getViewSize().height * 0.8,
    maximizable: true,
    modal: true,
    initComponent: function () {
        this.items = [
            {
                xtype: 'tabpanel',
                activeTab: 0,
                title: '',
                items: [
                    {
                        xtype: 'panel',
                        title: 'Tags',
                        layout: 'fit',
                        items: [
                            {
                                xtype: 'grid',
                                store: this.tagStore,
                                stripeRows: true,
                                width: '100%',
                                viewConfig: {
                                    forceFit: true,
                                    type: 'vbox',
                                    align: 'stretch'
                                },
                                columns: [
                                    {header: "Name", dataIndex: 'name' },
                                    { header: "Type", dataIndex: 'type' },
                                    { header: "Latitude", dataIndex: 'latitude' },
                                    { header: "Longitude", dataIndex: 'longitude' }
                                ]
                            }
                        ]
                    },
                    {
                        xtype: 'panel',
                        title: 'Word Counts',
                        layout: 'fit',
                        items: [
                            {
                                xtype: 'grid',
                                store: this.wordCountStore,
                                stripeRows: true,
                                width: '100%',
                                viewConfig: {
                                    forceFit: true,
                                    type: 'vbox',
                                    align: 'stretch'
                                },
                                columns: [
                                    {header: "Word", dataIndex: 'word' },
                                    {header: "Page", dataIndex: 'page'},
                                    {header: "Count", dataIndex: 'count'}
                                ]
                            }
                        ]
                    },
                    {
                        xtype: 'panel',
                        title: 'Dublin Core',
                        autoScroll: true,
                        items: [
                            {
                                xtype: 'form',
                                title: 'formDC',
                                id: 'frmDC',
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
                                                name: 'dcWiserdID',
                                                value: this.wid
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
                    new GeoPortal.Charts.QualChart()
                ]
            }
        ]
        GeoPortal.Windows.TaggingResults.superclass.initComponent.call(this);
    }
});
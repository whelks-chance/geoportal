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
                                    {header: "Save", dataIndex: 'save', sortable: true, xtype: 'checkcolumn'},
                                    {header: "Name", dataIndex: 'name', sortable: true},
                                    { header: "Type", dataIndex: 'type', sortable: true },
                                    { header: "Latitude", dataIndex: 'latitude', sortable: true },
                                    { header: "Longitude", dataIndex: 'longitude', sortable: true }
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
                                    {header: "Save", dataIndex: 'save', sortable: true, xtype: 'checkcolumn'},
                                    {header: "Word", dataIndex: 'word', sortable: true },
                                    {header: "Page", dataIndex: 'page', sortable: true},
                                    {header: "Count", dataIndex: 'count', sortable: true, sortType: 'asInt'}
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
                                id: 'frmQualDCinsert',
                                headerAsText: false,
                                padding: 5,
                                labelWidth: 75,
                                bodyCssClass: 'text-align:center',
                                autoScroll: true,
                                items: [
                                    {
                                        xtype: 'fieldset',
                                        title: 'Dublin Core',
                                        defaults: {labelStyle: 'font-weight:bold;' },
                                        collapsible: true,
                                        items: [
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'WISERD ID',
                                                anchor: '97%',
                                                name: 'dcWiserdID',
                                                allowBlank:false,
                                                value: this.wid
                                            },
                                            {
                                                xtype: 'textarea',
                                                anchor: '97%',
                                                fieldLabel: 'Title',
                                                name: 'dcTitle',
                                                allowBlank:false,
                                                autoHeight: true
                                            },
                                            {
                                                xtype: 'textarea',
                                                anchor: '97%',
                                                fieldLabel: 'Subject',
                                                name: 'dcSubject',
                                                allowBlank:false,
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
        ];

        this.bbar = {
            xtype: 'toolbar',
            height: 26,
            items: [
                {
                    xtype: 'tbfill'
                },
                {
                    xtype: 'button',
                    text: 'Save Metadata',
                    bodyStyle: 'float: right',
                    scope: this,
//                    icon: './images/silk/application_edit.png',
                    handler: function () {

                        var qualDCinsertPanel = Ext.getCmp('frmQualDCinsert');

                        if(qualDCinsertPanel.getForm().isValid()) {

//                        console.log("workcountstore : ");
//                        console.log(this.wordCountStore);
                        console.log("jsondata : ");
                        var jsonDataWordCount = Ext.encode(Ext.pluck(this.wordCountStore.data.items, 'data'));

                        var jsonDataTags = Ext.encode(Ext.pluck(this.tagStore.data.items, 'data'));
                        qualDCinsertPanel.getForm().submit({
                            url: saveTaggingMetadata,
                            params : {
                                wordCount : jsonDataWordCount,
                                tags : jsonDataTags
                            },
                            waitMsg: 'Saving Metadata.....',
                            success: function (form, action) {
                                console.log(action);

                            },
                            failure: function (form, action) {
                                console.log("nope");
                                Ext.Msg.alert(action.result.message);
                            }
                        });

//                        Ext.Ajax.request({
//                            url: saveTaggingMetadata,
//                            scope: this,
//                            params : {
//                                numbers : this.wordCountStore.reader.Json
////                                values : this.wordCountStore.proxy.reader.Json
//                            },
//                            method : 'POST',
//                            success: function(resp) {
//                                console.log(this.wordCountStore);
//                            },
//                            failure: function(resp) {
//                                console.log('failure!');
//                            }
//                        });
                        } else {
                            Ext.MessageBox.alert('Input Error', 'Invalid fields in Dublin Core tab');
                        }

                    }
                }
            ]
        };

        GeoPortal.Windows.TaggingResults.superclass.initComponent.call(this);
    }
});
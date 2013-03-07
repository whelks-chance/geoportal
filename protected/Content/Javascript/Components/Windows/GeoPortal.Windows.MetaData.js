GeoPortal.Windows.MetaData = Ext.extend(Ext.Window, {
    title: 'Full metadata for selected record',
    id: 'winMeta',
    width: 800,
    layout: 'fit',
    SID: null,
    height:Ext.getBody().getViewSize().height * 0.8,
    resizable: true,
    maximizable: true,
    hideBorders: true,
    initComponent: function () {
     this.UnitStore = new Ext.data.JsonStore({
            fields: ['spatial_id', 'Name', 'long_start', 'long_finish'],
            root: 'rows',
            id: 'spatial_units',
            url: SpatialUnitsURL
        });
    this.chartPanel = new GeoPortal.Charts.MetaChart({SID: this.SID, unit: 'Police Region', type: 'Bar', field1: 'total', field2: 'successful'});;
    this.UnitStore.load({params: {surveyID: this.SID}});
    this.ChartTypes =  [['Bar'],['Pie'],['Line']];
    this.ChartStore = new Ext.data.SimpleStore({
            fields: ['Chart'],
            data: this.ChartTypes
        });
     this.FieldStore = new Ext.data.JsonStore({
            fields: ['Name'],
            root: 'rows',
            id: 'dsName',
            url: fieldsURL
            });
        this.FieldStore.load({params: {SID: this.SID, unit: 'Police Region'}});
        this.items = [
            {
                xtype: 'tabpanel',
                activeTab: 0,
                title: 'tabPanel',
                id: 'tabMeta',
                height: 800,
                items: [
                    {
                        xtype: 'panel',
                        title: 'Question',
                        autoScroll: true,
                        id: 'tabQuestion',
                        items: [
                            {
                                xtype: 'form',
                                title: 'formQuestion',
                                headerAsText: false,
                                padding: 5,
                                labelWidth: 75,
                                defaults: {readOnly: true, labelStyle: 'font-weight:bold;' },
                                labelStyle: 'font-weight:bold;',
                                bodyCssClass: 'text-align:center',
                                autoScroll: true,
                                id: 'frmQuestion',
                                items: [
                                    {
                                        xtype: 'fieldset',
                                        title: 'General',
                                        collapsible: true,
                                        defaults: { labelStyle: 'font-weight:bold;' },
                                        items: [
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'Question ID',
                                                anchor: '97%',
                                                name: 'QuestionID'
                                            },
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'Question No',
                                                anchor: '97%',
                                                name: 'QuestionNumber'
                                            },
                                            {
                                                xtype: 'textarea',
                                                anchor: '97%',
                                                fieldLabel: 'Question Text',
                                                name: 'QuestionText'
                                            },
                                            {
                                                xtype: 'textarea',
                                                anchor: '97%',
                                                fieldLabel: 'Notes/ Prompts',
                                                name: 'QuestionNotesPrompts'
                                            },
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'Variable ID',
                                                anchor: '97%',
                                                name: 'QuestionVariable'
                                            }
                                        ]
                                    },
                                    {
                                        xtype: 'fieldset',
                                        title: 'WISERD Thematic Tagging',
                                        collapsible: true,
                                        defaults: { labelStyle: 'font-weight:bold;' },
                                        items: [
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'Groups',
                                                anchor: '97%',
                                                name: 'QuestionThematicGroups'
                                            },
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'Sub Themes',
                                                anchor: '97%',
                                                name: 'QuestionThematicTags'
                                            }
                                        ]
                                    },
                                    {
                                        xtype: 'fieldset',
                                        title: 'Routing Information',
                                        collapsed: true,
                                        collapsible: true,
                                        defaults: { labelStyle: 'font-weight:bold;' },
                                        items: [
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: ' Type',
                                                anchor: '97%',
                                                name: 'QuestionType'
                                            },
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'Follows QID',
                                                anchor: '97%',
                                                name: 'QuestionLinkedFrom'
                                            },
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'Sub Of',
                                                anchor: '97%',
                                                name: 'QuestionSubOf'
                                            }
                                        ]
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        xtype: 'panel',
                        title: 'Survey',
                        autoScroll: true,
                        items: [
                            {
                                xtype: 'form',
                                title: 'formSurvey',
                                id: 'frmSurvey',
                                headerAsText: false,
                                padding: 5,
                                labelWidth: 75,
                                defaults: { readOnly: true, labelStyle: 'font-weight:bold;' },
                                bodyCssClass: 'text-align:center',
                                autoScroll: true,
                                items: [
                                    {
                                        xtype: 'fieldset',
                                        title: 'General',
                                        defaults: { readOnly: true, labelStyle: 'font-weight:bold;' },
                                        collapsible: true,
                                        items: [
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'Survey ID',
                                                anchor: '97%',
                                                name: 'surveyID'
                                            },
                                            {
                                                xtype: 'textarea',
                                                anchor: '97%',
                                                fieldLabel: 'Survey Title',
                                                name: 'surveyTitle',
                                                autoHeight: true
                                            },
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'Collector',
                                                anchor: '97%',
                                                name: 'surveyCollector'
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
                                                                name: 'surveyStart',
                                                                anchor: '94%',
                                                                fieldLabel: 'Survey Start'
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
                                                                anchor: '94%',
                                                                fieldLabel: 'Survey End',
                                                                name: 'surveyEnd'
                                                            }
                                                        ]
                                                    }
                                                ]
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
                                                                name: 'surveyFrequency',
                                                                anchor: '94%',
                                                                fieldLabel: 'Frequency'
                                                            }
                                                        ]
                                                    },
                                                    {
                                                        xtype: 'container',
                                                        columnWidth: 0.5,
                                                        layout: 'form',
                                                        defaults: { readOnly: true, labelStyle: 'font-weight:bold;' },
                                                        items: [
                                                            {
                                                                xtype: 'textfield',
                                                                anchor: '94%',
                                                                fieldLabel: 'Series Y/N',
                                                                name: 'surveySeries'
                                                            }
                                                        ]
                                                    }
                                                ]
                                            },
                                            {
                                                xtype: 'container',
                                                layout: 'column',
                                                anchor: '97%',
                                                defaults: { readOnly: true, labelStyle: 'font-weight:bold;' },
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
                                                xtype: 'textarea',
                                                anchor: '97%',
                                                fieldLabel: 'Usage Notes',
                                                name: 'surveyNotes',
                                                autoHeight: true
                                            },
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'Location',
                                                anchor: '97%',
                                                name: 'surveyLocation'
                                            },
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'URL',
                                                anchor: '97%',
                                                name: 'surveyURL'
                                            }
                                        ]
                                    },
                                    {
                                        xtype: 'fieldset',
                                        title: 'Data Collection',
                                        collapsible: true,
                                        defaults: { readOnly: true, labelStyle: 'font-weight:bold;' },
                                        items: [
                                            {
                                                xtype: 'textarea',
                                                anchor: '97%',
                                                fieldLabel: 'Collection Method',
                                                name: 'surveyDataCollectionMethod',
                                                autoHeight: true
                                            },
                                            {
                                                xtype: 'textarea',
                                                anchor: '97%',
                                                fieldLabel: 'Collection Situation',
                                                name: 'surveyCollectionSituation',
                                                autoHeight: true
                                            },
                                            {
                                                xtype: 'textarea',
                                                anchor: '97%',
                                                fieldLabel: 'Sampling Prodedure',
                                                name: 'surveySamplingProcedure',
                                                autoHeight: true
                                            },
                                            {
                                                xtype: 'textarea',
                                                anchor: '97%',
                                                fieldLabel: 'Sampling Error',
                                                name: 'surveySamplingError',
                                                autoHeight: true
                                            }
                                        ]
                                    },
                                    {
                                        xtype: 'fieldset',
                                        title: 'Survey/Response Statistics',
                                        defaults: { readOnly: true, labelStyle: 'font-weight:bold;' },
                                        collapsible: true,
                                        buttonAlign: 'center',
                                        items: [
                                            {
                                                xtype: 'container',
                                                layout: 'column',
                                                items: [
                                                    {
                                                        xtype: 'container',
                                                        defaults: { readOnly: true, labelStyle: 'font-weight:bold;' },
                                                        layout: 'form',
                                                        columnWidth: 0.5,
                                                        items: [
                                                            {
                                                                xtype: 'textfield',
                                                                name: 'surveySampleSize',
                                                                fieldLabel: 'Sample Size',
                                                                anchor: '94%'
                                                            }
                                                        ]
                                                    },
                                                    {
                                                        xtype: 'container',
                                                        layout: 'form',
                                                        columnWidth: 0.5,
                                                        defaults: { readOnly: true, labelStyle: 'font-weight:bold;' },
                                                        labelWidth: 110,
                                                        items: [
                                                            {
                                                                xtype: 'textfield',
                                                                name: 'surveyResponseRate',
                                                                fieldLabel: 'Response Rate',
                                                                anchor: '94%'
                                                            }
                                                        ]
                                                    }
                                                ]
                                            },
                                            {
                                                xtype: 'textarea',
                                                anchor: '97%',
                                                fieldLabel: 'Weighting',
                                                name: 'surveyWeighting',
                                                height: 34,
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
                        title: 'Response',
                        autoScroll: true,
//                        autoHeight: true,
                        id: 'tabResponse',
                        items: [
                            {
                                xtype: 'form',
                                title: 'formResponse',
                                id: 'frmResponse',
                                headerAsText: false,
                                padding: 5,
                                labelWidth: 75,
                                defaults: { readOnly: true, labelStyle: 'font-weight:bold;' },
                                bodyCssClass: 'text-align:center',
                                autoScroll: true,
                                items: [
                                    {
                                        xtype: 'fieldset',
                                        title: 'General',
                                         defaults: { readOnly: true, labelStyle: 'font-weight:bold;' },
                                        collapsible: true,
                                        items: [
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'Question ID',
                                                anchor: '97%',
                                                name: 'questionID'
                                            },
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'Response ID',
                                                anchor: '97%',
                                                name: 'responseID'
                                            },
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'Type',
                                                anchor: '97%',
                                                name: 'responseType'
                                            },
                                            {
                                                xtype: 'textarea',
                                                anchor: '97%',
                                                fieldLabel: 'Response Text',
                                                name: 'responseText'
                                            },
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'Response Table ID',
                                                anchor: '97%',
                                                name: 'responseTableID'
                                            },
                                            {
                                                xtype: 'textarea',
                                                anchor: '97%',
                                                fieldLabel: 'Checks',
                                                name: 'responseChecks'
                                            },
                                            {
                                                xtype: 'textarea',
                                                anchor: '97%',
                                                fieldLabel: 'Computed Variables',
                                                name: 'responseVariables'
                                            }
                                        ]
                                    },
                                    {
                                        xtype: 'fieldset',
                                        title: 'Routing Information',
                                        collapsed: true,
                                        collapsible: true,
                                        items: [
                                            {
                                                xtype: 'textarea',
                                                anchor: '97%',
                                                fieldLabel: 'Routing Notes',
                                                name: 'responseRouting'
                                            }
                                        ]
                                    }
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
                        title: 'Statistics',
                        autoScroll: true,
                        id: 'stats',  
                        autoLoad: true,  
                        items: [{xtype: 'panel',
                                bodyStyle: 'padding: 5px',
                                 items: [ { xtype: 'container',
                                            layout: 'column',
                                            items: [{   xtype: 'container',
                                                        layout: 'form',
                                                        labelWidth: 75,
                                                        columnWidth: '.4',
                                                        items: [{
                                                                xtype: 'combo',
                                                                fieldLabel: 'Geography',
                                                                value: 'Police Region',
                                                                anchor: '95%',
                                                                store: this.UnitStore,
                                                                name: 'Unit',
                                                                mode: 'remote' ,
                                                                displayField: 'Name',
                                                                valueField: 'Name' ,
                                                                triggerAction: 'all',
                                                                lastQuery: '',
                                                                id: 'unit',
                                                                forceSelection: true
                                                                }]
                                                    },{   xtype: 'container',
                                                        layout: 'form',
                                                        columnWidth: '.2',
                                                        labelWidth: 75,
                                                        items: [{
                                                                xtype: 'combo',
                                                                fieldLabel: 'Chart Type',
                                                                id: 'chartType',
                                                                value: 'Bar',
                                                                anchor: '95%',
                                                                store: this.ChartStore,
                                                                name: 'Chart',
                                                                mode: 'remote' ,
                                                                displayField: 'Chart',
                                                                valueField: 'Chart',
                                                                triggerAction: 'all',
                                                                lastQuery: '',
                                                                forceSelection: true,
                                                                listeners:{ 
                                                                    select: { fn: function (combo, value) { 
                                                                       if (combo.value =='Pie') {
                                                                         Ext.getCmp('cmboField2').setDisabled = true;                                                                       
                                                                       } else {
                                                                        Ext.getCmp('cmboField2').setDisabled = false; 
                                                                        }   
                                                                    }
                                                                  } 
                                                               }      
                                                            }]
                                                        },
                                                    {   xtype: 'container',
                                                        layout: 'form',
                                                        columnWidth: '.2',
                                                        labelWidth: 50,
                                                        items: [{
                                                                xtype: 'combo',
                                                                fieldLabel: 'Field 1',
                                                                anchor: '95%' ,
                                                                value: 'total',
                                                                store: this.FieldStore, 
                                                                loadingText: 'Loading....',
                                                                mode: 'remote',
                                                                anchor: '95%',
                                                                name: 'Field1',
                                                                forceSelection: true,
                                                                submitValue: true,
                                                                displayField: 'Name',
                                                                valueField: 'Name',
                                                                triggerAction: 'all',
                                                                lastQuery: '',
                                                                id: 'cmboField1'
                                                            }]                   
                                                    },{
                                                        xtype: 'container',
                                                        layout: 'form',
                                                        labelWidth: 50,
                                                        columnWidth: '.2',
                                                        items: [{
                                                                xtype: 'combo',
                                                                fieldLabel: 'Field 2',
                                                                anchor: '95%',
                                                                value: 'successful',
                                                                store: this.FieldStore,
                                                                loadingText: 'Loading....',
                                                                mode: 'remote',
                                                                anchor: '95%',
                                                                name: 'Field2',
                                                                forceSelection: true,
                                                                submitValue: true,
                                                                displayField: 'Name',
                                                                valueField: 'Name',
                                                                triggerAction: 'all',
                                                                lastQuery: '',
                                                                id: 'cmboField2'
                                                                }]   
                                                    },{
                                                        xtype: 'button',
                                                        text: 'Redraw',
                                                        handler: function(){
                                                            var SID = Ext.getCmp('winMeta').SID;
                                                            var Chart = Ext.getCmp('chartType').value;
                                                            var field1  = Ext.getCmp('cmboField1').value;
                                                            var field2 = Ext.getCmp('cmboField2').value;
                                                            var unit = Ext.getCmp('unit').value;
                                                            var charts = Ext.getCmp('charts');

                                                            Ext.getCmp('stats').remove(charts);

                                                            var newChart = new GeoPortal.Charts.MetaChart({SID: SID, unit: unit, type: Chart, field1: field1, field2: field2});

                                                           Ext.getCmp('stats').add(newChart);
                                                        
                                                        Ext.getCmp('stats').doLayout();
                                                        
                                                        
                                                        }
                                                     }]
                                           }]}, this.chartPanel]
                    }
                ]
            }
        ];
        this.tbar = {
            xtype: 'toolbar',
            items: [
                {
                    xtype: 'buttongroup',
                    columns: 2,
                    scale: 'small',
                     items: [
                        {
                            xtype: 'button',
                            text: 'Save Metadata',
                            icon: 'images/silk/disk.png',
                            menu: {
                                xtype: 'menu',
                                items: [
                                    {
                                        xtype: 'menuitem',
                                        text: 'to PDF',
                                        icon: 'images/silk/page_white_acrobat.png',
                                    },
                                    {
                                        xtype: 'menuitem',
                                        text: 'to Account',
                                        icon: 'images/silk/folder_user.png',
                                    }
                                ]
                            }
                        }
                    ]
                },{
                xtype: 'button',
                text: 'Show Response Table',
                icon: 'images/silk/table_edit.png',
                id: 'btnShowResponseTable',
                handler: function(){

               var units = Ext.getCmp('winMeta').UnitStore;
               var win =  Ext.getCmp('winMeta');
               var SID = win.SID;
               units.load({params: {surveyID:SID}});
                   var search = new Ext.form.ComboBox({
                                 xtype: 'combo',
                                 fieldLabel: 'Unit',
                                 value: 'Police Region',
                                 anchor: '95%',
                                 store: units,
                                 name: 'Unit',
                                 mode: 'remote' ,
                                 displayField: 'Name',
                                 valueField: 'Name' ,
                                 triggerAction: 'all',
                                 lastQuery: '',
                                 id: 'cmboUnitResponse',
                                 forceSelection: true
                                 });

                 var form = new Ext.form.FormPanel({ id: 'unitForm', items: [search], frame: true, autoDestroy: true });

                var unitWin = new Ext.Window({
                                width: 400,
                                animateTarget: Ext.getCmp('btnFindPlacename').el,
                                bodyStyle: 'Padding: 5px',
                                autoDestroy: true,
                                id: 'placeWin',
                                layout: 'form',
                                title: 'Show Response Table',
                                items: [form],  
                                buttons: [{
                                    xtype: 'button',
                                    text: 'Get Response Table',
                                    id: 'btnNameSearch',
                                    disabled: false,
                                    handler: function () {
                                    var unit =  Ext.getCmp('cmboUnitResponse').getValue();
                var rWin = new GeoPortal.Windows.Responses({SID: Ext.getCmp('winMeta').SID, unit: unit});
                                 rWin.show();

                                 unitWin.close();
                                 }
                                 }]
                                 });
                                 unitWin.show();
                }
            },{
                                 xtype: 'button',
                                 text: 'View All Survey Questions',
                                 icon: 'images/silk/table_go.png',
                                 handler: function(){
                                 var qWin = new GeoPortal.Windows.Questions({SID: Ext.getCmp('winMeta').SID});
                                 qWin.show();

                                 }

                                }
            ]
        };
        GeoPortal.Windows.MetaData.superclass.initComponent.call(this);
    }
});

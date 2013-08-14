/**
 * Created with JetBrains PhpStorm.
 * User: wiserd
 * Date: 13/08/13
 * Time: 18:54
 * To change this template use File | Settings | File Templates.
 */
/**
 * Created with JetBrains PhpStorm.
 * User: wiserd
 * Date: 13/08/13
 * Time: 15:23
 * To change this template use File | Settings | File Templates.
 */
Ext.ns('GeoPortal.Forms.DataEntry');
GeoPortal.Forms.DataEntry.Survey = Ext.extend(Ext.form.FormPanel, {

        title: 'Survey',
        autoScroll: true,
        id: 'frmEntrySurvey',
        headerAsText: false,
        padding: 5,
        labelWidth: 75,
        SID: null,
        bodyCssClass: 'text-align:center',

        initComponent : function () {

            this.items = [
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
            ];

            this.tbar = {
                xtype: 'toolbar',
                items: [
                    {
                        xtype: 'button',
                        id: 'btnSurveyReset',
                        icon: 'images/silk/arrow_rotate_clockwise.png',
                        text: 'Reset Form',
                        type: 'reset',
                        handler : this.FormReset,
                        scope : this
                    }
                ]
            };

            GeoPortal.Forms.DataEntry.Survey.superclass.initComponent.call(this);
        },
        FormReset : function() {
            console.log('reset ' + this.id)
            var thisPanel = Ext.getCmp(this.id);
            console.log(thisPanel);
            thisPanel.getForm().reset();
        }
    }
);

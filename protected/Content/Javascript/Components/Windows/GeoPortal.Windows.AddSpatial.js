GeoPortal.Windows.AddSpatial = Ext.extend(Ext.Window, {
    title: 'Add Spatial Outputs to Map',
    SurveyID: null,
    width: 400,
    id: 'spatialWin',
    resizable: false,
    padding: 10,
    initComponent: function () {
        this.layout = {
            labelWidth: 125,
            type: 'form',
            defaultAnchor: '95%'
        };
        this.classData = [['Equal Interval'], ['Quantile']];
        this.refUnitData = [['Current Map Extent'], ['Unitary Authority'], ['Police Region'], ['Fire Brigade Region'], ['Assembley Economic Fora Area']];
        this.RefUnitStore = new Ext.data.SimpleStore({
            fields: ['Name'],
            id: 'ref_spatial_units',
            data: this.refUnitData
        });
        this.UnitStore = new Ext.data.JsonStore({
            fields: ['spatial_id', 'Name', 'long_start', 'long_finish', 'short_name', 'min', 'max'],
            root: 'rows',
            id: 'spatial_units',
            url: SpatialUnitsURL
        });
        this.SubUnitStore = new Ext.data.JsonStore({
            fields: ['Name'],
            root: 'rows',
            id: 'spatial_sub_units',
            url: SpatialSubUnitsURL
        });
        this.RefSubUnitStore = new Ext.data.JsonStore({
            fields: ['Name'],
            root: 'rows',
            id: 'ref_spatial_sub_units',
            url: SpatialRefSubUnitsURL
        });
        this.RefIndSubUnitStore = new Ext.data.JsonStore({
            fields: ['Name'],
            root: 'rows',
            id: 'ref_spatial_sub_units',
            url: SpatialRefIndSubUnitsURL
        });
        this.LabelStore = new Ext.data.JsonStore({
            fields: ['Name'],
            root: 'rows',
            id: 'dsName',
            url: SpatialLabelsURL
        });
        this.ChoroStore = new Ext.data.JsonStore({
            fields: ['Name'],
            root: 'rows',
            id: 'dsName',
            url: ChoroURL
        });
        this.ClassStore = new Ext.data.SimpleStore({
            fields: ['method'],
            data: this.classData
        });
        this.fromColour = { xtype: 'colorpickerfield', fieldLabel: 'Colour 1', name: 'fieldColour1', value: '#F60434', id: 'fromColour', width: 200 };
        this.ToColour = { xtype: 'colorpickerfield', fieldLabel: 'Colour 1', name: 'fieldColour1', value: '#FFFFFF', id: 'toColour', width: 200 };
        this.SimpleColour = { xtype: 'colorpickerfield', fieldLabel: 'Base Colour', name: 'fieldColour1', value: '#F60434', id: 'simpleColour' };
        this.items = [
        { xtype: 'form',
            width: 400,
            padding: 10,
            labelWidth: 125,
            id: 'frmSpatial',
            layoutConfig: {
                defaultAnchor: '95%'
            },
            items: [
            {
                xtype: 'combo',
                fieldLabel: 'Choose Spatial Units',
                store: this.UnitStore,
                loadingText: 'Loading....',
                mode: 'remote',
                anchor: '95%',
                name: 'Unit',
                forceSelection: true,
                submitValue: true,
                displayField: 'Name',
                valueField: 'spatial_id',
                triggerAction: 'all',
                lastQuery: '',
                id: 'cmboUnits',
                listeners:
                        { select: { fn: function (combo, value) {
                            Ext.getCmp('cmboLabel').clearValue();
                            Ext.getCmp('cmboChoro').clearValue();
                            var win = Ext.getCmp('spatialWin');
                            win.LabelStore.load({ params: { TableName: this.value} });
                            Ext.getCmp('cmboLabel').setValue('area_name');
                            win.ChoroStore.load({ params: { TableName: this.value} });
                            Ext.getCmp('cmboChoro').setValue('successful');
                            Ext.getCmp('layerName').setValue(value.data.spatial_id);
                        }
                        }
                        }
            },
            this.SimpleColour,
            {
                xtype: 'textfield',
                fieldLabel: 'Layer Name',
                name: 'layerName',
                id: 'layerName',
                allowBlank: false


            },
            {
                xtype: 'fieldset',
                animCollapse: true,
                collapsed: true,
                visible: false,
                collapsible: false,
                disabled: true,
                hidden: true,
                id: 'advOptions',
                title: 'Advanced Options',
                items: [
                  {
                      xtype: 'checkbox',
                      fieldLabel: 'Add as Outline',
                      anchor: '100%',
                      name: 'Outline',
                      id: 'chkFill'
                  },
                        {
                            xtype: 'checkbox',
                            fieldLabel: 'Labels',
                            anchor: '100%',
                            checked: true,
                            name: 'addLabels',
                            id: 'chkLabels',
                            handler: function () {
                                if (this.checked != true) {
                                    Ext.getCmp('cmboLabel').setDisabled(true);
                                } else {
                                    Ext.getCmp('cmboLabel').setDisabled(false);
                                }
                            }

                        }, {
                            xtype: 'checkbox',
                            fieldLabel: 'Draw Choropleth',
                            anchor: '100%',
                            checked: true,
                            name: 'Choropleth',
                            id: 'chkChoropleth',
                            handler: function () {
                                if (this.checked != true) {
                                    Ext.getCmp('cmboChoro').setDisabled(true);
                                    Ext.getCmp('cmboClass').setDisabled(true);
                                    Ext.getCmp('txtInt').setDisabled(true);
                                    Ext.getCmp('toColour').setDisabled(true);


                                } else {
                                    Ext.getCmp('cmboChoro').setDisabled(false);
                                    Ext.getCmp('cmboClass').setDisabled(false);
                                    Ext.getCmp('txtInt').setDisabled(false);
                                    Ext.getCmp('toColour').setDisabled(false);

                                }
                            }
                        },
                        {
                            xtype: 'combo',
                            fieldLabel: 'Label Field',
                            anchor: '95%',
                            name: 'Label',
                            mode: 'remote',
                            triggerAction: 'all',
                            valueField: 'Name',
                            lastQuery: '',
                            submitValue: true,
                            disabled: false,
                            store: this.LabelStore,
                            displayField: 'Name',
                            id: 'cmboLabel',
                            value: 'area_name'
                        },
                        this.fromColour,
                        this.ToColour,
                        {
                            xtype: 'combo',
                            fieldLabel: 'Choropleth Field',
                            anchor: '95%',
                            name: 'ChoroplethField',
                            mode: 'local',
                            triggerAction: 'all',
                            valueField: 'Name',
                            lastQuery: '',
                            submitValue: true,
                            disabled: false,
                            store: this.ChoroStore,
                            displayField: 'Name',
                            id: 'cmboChoro',
                            value: 'successful'
                        },
                          {
                              xtype: 'combo',
                              fieldLabel: 'Classification Method',
                              anchor: '95%',
                              name: 'ClassMethod',
                              mode: 'remote',
                              triggerAction: 'all',
                              valueField: 'method',
                              lastQuery: '',
                              submitValue: true,
                              disabled: false,
                              store: this.ClassStore,
                              displayField: 'method',
                              id: 'cmboClass',
                              value: 'Equal Interval'
                          }, {
                              xtype: 'numberfield',
                              fieldLabel: 'Intervals',
                              name: 'Intervals',
                              id: 'txtInt',
                              value: 5

                          }


                ]
            }
        ]

        }];
        this.bbar = {
            xtype: 'toolbar',
            items: [
                {
                    xtype: 'tbfill'
                },
                {
                    xtype: 'button',
                    text: 'Advanced Options',
                    allowDepress: true,
                    enableToggle: true,
                    disabled: true,
                    icon: 'images/silk/add.png',
                    handler: function () {
                        var Adv = Ext.getCmp('advOptions');
                        if (Adv.hidden == true) {
                            Adv.setDisabled(false);
                            Adv.setVisible(true);
                            Adv.toggleCollapse(true);
                        } else {
                            Adv.setDisabled(true);
                            Adv.setVisible(false);
                            Adv.toggleCollapse(true);

                        }

                        Adv.doLayout();
                    }
                },
                {
                    xtype: 'button',
                    text: 'Add to Map',
                    icon: 'images/silk/map_add.png',
                    handler: function () {
                        var win = Ext.getCmp('spatialWin');
                        var frmAddSpatial = Ext.getCmp('frmSpatial');
                        var LayerName = Ext.getCmp('layerName').getValue();
                        var SurveyID, fromColour, toColour, field, classes, min, max
                        var unitCmbo = Ext.getCmp('cmboUnits');

                        if (Ext.getCmp('advOptions').hidden == true) {
                            SurveyID = win.SurveyID;
                            fromColour = '#FFFFFF';
                            toColour = Ext.getCmp('simpleColour').getValue();
                            classes = 5;
                            field = 'successful';

                        } else {
                            SurveyID = win.SurveyID;
                            fromColour = Ext.getCmp('fromColour').getValue();
                            toColour = Ext.getCmp('toColour').getValue();
                        }

                        var minmax = Ext.Ajax.request({
                            url: 'SpatialData/getMinMax',
                            method: 'POST',
                            params: {
                                tableName: unitCmbo.getValue(),
                                colName: field
                            },
                            callback: function (post, sucess, response) {

                                var id = "";
                                var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

                                for (var z = 0; z < 5; z++)
                                    id += possible.charAt(Math.floor(Math.random() * possible.length));


                                var minMax = response.responseText;
                                minMax = minMax.replace('[', '');
                                minMax = minMax.replace(']', '');
                                showResults('ALL', LayerName, fromColour, toColour, false, unitCmbo.getValue().split('_')[3], "Survey Data", SurveyID, id, unitCmbo.getValue(), minMax.split(',')[0], minMax.split(',')[1])
                                //addChoropleth('test', LayerName, fromColour, toColour, minMax.split(',')[0], minMax.split(',')[1], unitCmbo.getValue().split('_')[3], SurveyID, field, classes, 'Survey Data', unitCmbo.getValue());

                                win.close();

                            }
                        });




                    }
                }
            ]
        };
        GeoPortal.Windows.AddSpatial.superclass.initComponent.call(this);
    }
});
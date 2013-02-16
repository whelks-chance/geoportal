Ext.state.Manager.setProvider(new Ext.state.CookieProvider({
    expires: new Date(new Date().getTime()+(1000*60*60*24*7)), //7 days from now
}));


MyWindowUi = Ext.extend(Ext.Window, {
    width: 500,
    height: 400,
    title: 'Map Options',
    bodyStyle: 'background: white',
    border: true,
    collapsible: false,
    padding: '5px',
    shadow: 'drop',
    shadowOffset: 2,
    modal: true,
    minimizable: true,
    forceLayout: true,
    stateful: true,
    autoDestroy: false,
    initComponent: function () {
        this.items = [
            {
                xtype: 'tabpanel',
                autoWidth: true,
                autoHeight: true,
                stateful: true,
                activeTab: 1,
                boxMinHeight: 300,
                items: [
                    {
                        xtype: 'panel',
                        title: 'Map Features',
                        autoHeight: true,
                        autoWidth: true,
                        padding: 10,
                        layout: 'form',
                        height: 300,
                        items: [
                            {
                                xtype: 'displayfield',
                                value: 'Change these options to customise the appearence of your map:'
                            },
                            {
                                xtype: 'form',
                                autoWidth: true,
                                autoHeight: true,
                                labelAlign: 'left',
                                padding: 5,
                                labelPad: 5,
                                labelWidth: 200,
                                labelSeparator: '',
                                layoutConfig: {
                                    labelSeparator: ':'
                                },
                                items: [
                                    {
                                        xtype: 'checkbox',
                                        labelSeparator: ':',
                                        fieldLabel: 'Show North Arrow',
                                        id: 'cbNorthArrow'
                                    },
                                    {
                                        xtype: 'checkbox',
                                        boxLabel: '',
                                        fieldLabel: 'Show Scale Bar',
                                        id: 'cbScaleBar'
                                    },
                                    {
                                        xtype: 'checkbox',
                                        boxLabel: '',
                                        fieldLabel: 'Show Current Position',
                                        id: 'cbShowCurrentPos'
                                    },
                                    {
                                        xtype: 'checkbox',
                                        fieldLabel: 'Show Current Scale',
                                        id: 'cbCurrentScale'
                                    },
                                    {
                                        xtype: 'checkbox',
                                        itemId: 'cbShowLegend',
                                        fieldLabel: 'Show Legend'
                                    },
                                    {
                                        xtype: 'combo',
                                        itemId: 'cboUnits',
                                        blankText: 'Please Choose...',
                                        fieldLabel: 'Choose Map Units',
                                        mode: 'local'
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        xtype: 'panel',
                        title: 'Map Layers',
                        items: [
                            {
                                xtype: 'form',
                                padding: 10,
                                labelWidth: 150,
                                items: [
                                    {
                                        xtype: "gx_opacityslider",
                                        layer:  map.baseLayer,
                                        fieldLabel: 'Base Mapping Visibility',
                                        width: 250,
                                        id: 'transBMap',
                                        changeVisibility: true,
                                        plugins: new GeoExt.LayerOpacitySliderTip(),
                                        stateful: true
                                    },
                                    {
                                        xtype: 'slider',
                                        value: 0,
                                        fieldLabel: 'Overlay Mapping Visibility',
                                        width: 250,
                                        id: 'transOverlay',
                                        plugins: new Ext.slider.Tip()
                                    },
                                    {
                                        xtype: "gx_opacityslider",
                                        layer: vector,
                                        fieldLabel: 'Annotation Layer Visibility',
                                        width: 250,
                                        id: 'annoOverlay',
                                        changeVisibility: true,
                                        plugins: new Ext.slider.Tip()
                                    }
                                ]
                            }
                        ]
                    }
                ]
            }
        ];
        this.bbar = {
            xtype: 'toolbar',
            items: [
                {
                    xtype: 'tbfill'
                },
                {
                    xtype: 'button',
                    text: 'Save'
                },
                {
                    xtype: 'tbseparator'
                },
                {
                    xtype: 'button',
                    text: 'Cancel'
                }
            ]
        };
        MyWindowUi.superclass.initComponent.call(this);
    }
});




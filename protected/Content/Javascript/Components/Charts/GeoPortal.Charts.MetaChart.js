GeoPortal.Charts.MetaChart = Ext.extend(Ext.Container, {
    layout: 'fit',
    SID: null,
    unit: null,
    type: null,
    field1: null,
    field2: null,
    bodyStyle: 'text-align: center',
    height: 800,
    autoWidth: true,
    id: 'charts',
    initComponent: function () {
        GeoPortal.Charts.MetaChart.superclass.initComponent.call(this);
        this.on('afterrender', this.renderChart, this);
    },
    renderChart: function () {
        Ext4.createWidget('chart', Ext.apply(this.getChartCfg(), {
            layout: 'fit',
            width: 750,
            height: 650,
            renderTo: this.getEl().dom
        }));
    },
    getChartCfg: function () {

        Ext4.define('Responses', {
            extend: 'Ext.data.Model',
            fields: [
            { name: 'adjustedRRate', type: 'string' },
            { name: 'responseRate', type: 'string' },
            { name: 'other', type: 'int' },
            { name: 'ineligible', type: 'int' },
            { name: 'noContact', type: 'int' },
            { name: 'refused', type: 'int' },
            { name: 'successful', type: 'int' },
            { name: 'total', type: 'int' },
            { name: 'name', type: 'string'}]
        });


        var ChartStore = new Ext4.data.Store({
            storeId: 'respStore',
            model: 'Responses',
            proxy: {
                type: 'ajax',
                url: responseTableURL,
                reader: {
                    type: 'json',
                    root: 'data'
                }
            },
            autoLoad: false

        });

        ChartStore.load({ params: { SID: this.SID, unit: this.unit} });

        var ChartConfig;

        if (this.type == 'Pie') {
            var field = this.field1;

            ChartConfig = {
                flex: 1,
                xtype: 'chart',
                animate: {
                    duration: 250
                },
                store: ChartStore,
                shadow: true,

                legend: {
                    position: 'right'
                },
                insetPadding: 40,
                theme: 'Base:gradients',
                series: [{
                    donut: 30,
                    type: 'pie',
                    field: field,
                    showInLegend: true,
                    tips: {
                        trackMouse: true,
                        width: 140,
                        height: 28,
                        renderer: function (storeItem, item) {
                            //calculate percentage.
                            var total = 0;
                            ChartStore.each(function (rec) {
                                total += rec.get(field);
                            });
                            this.setTitle(storeItem.get('name') + ': ' + Math.round(storeItem.get(field) / total * 100) + '%');
                        }
                    },
                    highlight: {
                        segment: {
                            margin: 20
                        }
                    },
                    labelTitle: {
                        font: '13px Arial'
                    },
                    label: {
                        field: 'name',
                        display: 'rotate',
                        contrast: true,
                        font: '12px Arial'
                    }
                }]
            };
        } else if (this.type == 'Bar') {
            ChartConfig = {
                flex: 1,
                xtype: 'chart',
                animate: true,
                store: ChartStore,
                shadow: true,
                legend: {
                    position: 'right'
                },
                axes: [{ type: 'Numeric',
                    position: 'left',
                    fields: [this.field1, this.field2],
                    minimum: 0,
                    scale: 'linear',
                    //label: { renderer: Ext4.util.Format.numberRenderer('0,0') },
                    grid: true,
                    adjustMinimumByMajorUnit: 0,
                    title: 'Responses'
                },
                      { type: 'Category',
                          position: 'bottom',
                          title: 'Area Name',
                          fields: 'name',
                          label: { rotate: { degrees: 270 } }
                      }],
                     
                series: [{
                    type: 'column',
                    axis: 'left',
                    xField: 'name',
                    highlight: true,
                    tips: {
                        trackMouse: true,
                        width: 140,
                        height: 28,
                        renderer: function (storeItem, item) {
                            this.setTitle(item.value[0] + ': ' + item.value[1]);
                        }
                    },
                    yField: [this.field1, this.field2]
                }]
            };

        } else if (this.type == 'Line') {


            ChartConfig = {
                flex: 1,
                xtype: 'chart',
                animate: true,
                store: ChartStore,
                shadow: true,
                legend: {
                    position: 'right'
                },
                axes: [{ type: 'Numeric',
                    position: 'left',
                    fields: [this.field1, this.field2],
                    minimum: 0,
                    scale: 'linear',
                    //label: { renderer: Ext4.util.Format.numberRenderer('0,0') },
                    grid: true,
                    adjustMinimumByMajorUnit: true,
                    title: 'Responses'
                    },  { type: 'Category',
                          position: 'bottom',
                          title: 'Area Name',
                          fields: 'name',
                          label: { rotate: { degrees: 270} }
                      }], 
                series: [{
                    type: 'line',
                    axis: 'left',
                    xField: 'name',
                    yField: [this.field1],             
                    markerConfig: {
                        type: 'circle',
                        size: 4,
                        radius: 4,
                        'stroke-width': 0
                    }
                } ,{ type: 'line',
                    axis: 'left',
                    xField: 'name',
                    yField: [this.field2],             
                    markerConfig: {
                        type: 'cross',
                        size: 4,
                        radius: 4,
                        'stroke-width': 0
                    }   
                    
              }]
            };


        }



        return ChartConfig;

    }

});
GeoPortal.Charts.QualChart = Ext.extend(Ext.Container, {
    layout: 'fit',
    ID: null,
    place1: null,
    place2: null,
    place3: null,
    bodyStyle: 'text-align: center',
    height: 800,
    autoWidth: true,
    id: 'qualchart',
    initComponent: function () {
        GeoPortal.Charts.QualChart.superclass.initComponent.call(this);
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

        Ext4.define('Words', {
            extend: 'Ext.data.Model',
            fields: [
            { name: 'page', type: 'string' },
            { name: 'place1', type: 'string' },
            { name: 'place1Count', type: 'integer' },
            { name: 'place2', type: 'string' },
            { name: 'place2Count', type: 'integer' },
            { name: 'place3', type: 'string' },
            { name: 'place3Count', type: 'integer' }]         
        });


        var ChartStore = new Ext4.data.Store({
            storeId: 'respStore',
            model: 'Words',
            proxy: {
                type: 'ajax',
                url: QualWordsURL,
                reader: {
                    type: 'json',
                    root: 'data'
                }
            },
            autoLoad: false

        });

        this.place1 = Ext.getCmp('cmboPlace1').getValue();
        this.place2=  Ext.getCmp('place2').getValue();
        this.place3 = Ext.getCmp('place3').getValue();

        ChartStore.load({ params: { ID: this.ID, place1: this.place1, place2:this.place2, place3:this.place3}});

        var ChartConfig = {
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
                    fields: ['place1Count', 'place2Count', 'place3Count'],
                    minimum: 0,
                    minorTickSteps: 0,
                    decimals: 0,
                    scale: 'linear',
                    //label: { renderer: Ext4.util.Format.numberRenderer('0,0') },
                    grid: true,
                    title: 'Counts'
                },
                      { type: 'Category',
                          position: 'bottom',
                          title: 'Page Number',
                          fields:'page',
                          label: { rotate: { degrees: 270} }
                      }],

                series: [{
                    type: 'column',
                    axis: 'left',
                    xField: 'page',
                    highlight: true,
                    tips: {
                        trackMouse: true,
                        width: 140,
                        height: 28,
                        renderer: function (storeItem, item) {
                            this.setTitle(item.value[0] + ': ' + item.value[1]);
                        }
                    },
                    yField:  ['place1Count', 'place2Count', 'place3Count'],
                    title:[this.place1, this.place2, this.place3]
                }]
            };

    

        return ChartConfig;

    }

});
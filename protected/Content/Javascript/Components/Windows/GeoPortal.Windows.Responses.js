GeoPortal.Windows.Responses = Ext.extend(Ext.Window, {
    width: 800,
    height: Ext.getBody().getViewSize().height * 0.8,
    SID: null,
    unit: null,
    resizable: false,
    initComponent: function () {
        this.ResponseStore = new Ext.data.JsonStore({
            fields: [{ name: 'adjustedRRate', type: 'string' },
            { name: 'responseRate', type: 'string' },
            { name: 'other', type: 'int' },
            { name: 'ineligible', type: 'int' },
            { name: 'noContact', type: 'int' },
            { name: 'refused', type: 'int' },
            { name: 'successful', type: 'int' },
            { name: 'total', type: 'int' },
            { name: 'name', type: 'string'}],
            baseParams: { SID: this.SID, unit: this.unit, start: 0, limit: 30 },
            root: 'data',
            id: 'questions',
            url: responseTableURL,
            totalProperty: 'totalCount'
        });
        this.ResponseStore.load();
        this.items = [
            {
                xtype: 'grid',
//                height: 770,
                id: 'grdResponses',
                store: this.ResponseStore,
                frame: true,
                loadMask: true,
                stripeRows: true,
                totalProperty: 'totalCount',
                viewConfig: {
                    forceFit: true
                },
                columns: [
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'name',
                        header: 'Name',
                        sortable: true,
                        width: 80
                    },
                    {
                        xtype: 'gridcolumn',
                        header: 'Total',
                        sortable: true,
                        width: 25,
                        dataIndex: 'total'
                    },
                    {
                        xtype: 'gridcolumn',
                        header: 'Successful',
                        sortable: true,
                        width: 25,
                        dataIndex: 'succesful'
                    },
                    {
                        xtype: 'gridcolumn',
                        header: 'Refused',
                        sortable: true,
                        width: 25,
                        dataIndex: 'refused'
                    },
                     {
                         xtype: 'gridcolumn',
                         header: 'No Contact',
                         sortable: true,
                         width: 25,
                         dataIndex: 'noContact'
                     },
                      {
                          xtype: 'gridcolumn',
                          header: 'Ineligible',
                          sortable: true,
                          width: 25,
                          dataIndex: 'ineligible'
                      }, {
                          xtype: 'gridcolumn',
                          header: 'Other',
                          sortable: true,
                          width: 25,
                          dataIndex: 'other'
                      },
                      {
                          xtype: 'gridcolumn',
                          header: 'Response Rate',
                          sortable: true,
                          width: 25,
                          dataIndex: 'responseRate'
                      },
                      {
                          xtype: 'gridcolumn',
                          header: 'Adjsuted RR',
                          sortable: true,
                          width: 25,
                          dataIndex: 'adjustedRRate'
                      }
                ]
            }
        ];
        this.bbar = {
            xtype: 'paging',
            store: this.ResponseStore,
            pageSize: 30,
            displayInfo: true,
            displayMsg: 'Displaying Questions {0} -{1} of {2}'
        };
        GeoPortal.Windows.Responses.superclass.initComponent.call(this);
    }
});
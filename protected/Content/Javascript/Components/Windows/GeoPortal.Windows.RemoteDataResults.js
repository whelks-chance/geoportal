/**
 * Created with JetBrains PhpStorm.
 * User: wiserd
 * Date: 03/07/13
 * Time: 17:54
 * To change this template use File | Settings | File Templates.
 */
GeoPortal.Windows.RemoteDataResults = Ext.extend(Ext.Window, {
    width: 800,
    height: Ext.getBody().getViewSize().height * 0.8,
    apiName : null,
    DatasetID : null,
    BoundaryID : null,
    MeasuresID : null,
    layout : 'fit',
//    resizable: false,
    initComponent: function () {
        this.RemoteDataStore = new Ext.data.JsonStore({
            fields: [{ name: 'description', type: 'string' },
                { name: 'value', type: 'int' }
                ],
            baseParams: {
                apiName : this.apiName,
                DatasetID : this.DatasetID,
                BoundaryID : this.BoundaryID,
                MeasuresID : this.MeasuresID,
                start: 0,
                limit: 30 },
            root: 'data',
            id: 'questions',
            url: remoteGetDataURL,
            totalProperty: 'totalCount'
        });
        this.RemoteDataStore.on('load', function(store, recs, opt){
            console.log('response resize here');
            this.doLayout();
            //update your display here
        }, this);
        this.RemoteDataStore.load();
        this.items = [
            {
                xtype: 'grid',
//                height: (Ext.getBody().getViewSize().height * 0.8) - 50,
                id: 'grdResponses',
                store: this.RemoteDataStore,
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
                        dataIndex: 'description',
                        header: 'Description',
                        sortable: true,
                        width: 80
                    },
                    {
                        xtype: 'gridcolumn',
                        header: 'Value',
                        sortable: true,
                        width: 25,
                        dataIndex: 'value'
                    }
                ]
            }
        ];
//        this.bbar = {
//            xtype: 'paging',
//            store: this.ResponseStore,
//            pageSize: 30,
//            displayInfo: true,
//            displayMsg: 'Displaying Questions {0} -{1} of {2}'
//        };
        GeoPortal.Windows.RemoteDataResults.superclass.initComponent.call(this);
    }
});
Ext.Loader.setConfig({
    enabled: true
});
Ext.Loader.setPath('Ext.ux', '../../Content/Javascript/Libraries/Ext4/ux');
Ext.Loader.setPath('Ext', '../../Content/Javascript/Libraries/Ext4');

Ext.require([
    'Ext.grid.*',
    'Ext.data.*',
    'Ext.ux.RowExpander',
    'Ext.selection.CheckboxModel',
    'Ext.panel.*',
    'Ext.layout.container.Border'
]);



Ext.onReady(function () {
    // wrapped in closure to prevent global vars.
    Ext.define('Results', {
        extend: 'Ext.data.Model',
        fields: ['RecordID', 'SurveyName', 'SurveyCollectionFrequency', 'QuestionThematicTag', 'QuestionNumber', 'QuestionText', 'QuestionType', 'QuestionRoot', 'QuestionNotes', 'QuestionID', 'DataSource', 'QuestionThematicGroup'],
        idProperty: 'RecordID'
    });


    var results = new Ext.data.Store({
        storeId: 'resStore',
        pageSize: 5,
        remoteSort: true,
        model: 'Results',
        sorters: ['DataSource', 'QuestionID'],
        groupField: 'QuestionType',
        proxy: {
            type: 'jsonp',
            url: simpleSearchURL + "?callbackKey=",
            reader: {
              root: 'questions',
              totalCount: 'totalCount'
            }

        }

    });

    results.loadPage(1);




    var groupingFeature = Ext.create('Ext.grid.feature.Grouping', {
        groupHeaderTpl: '{QuestionType} : ({rows.length} Item{[values.rows.length > 1 ? "s" : ""]})'
    });




    var grid1 = new Ext.grid.GridPanel({
        store: results,
        id: 'gp',
        stripeRows: true,
        layout: 'fit',
        disableSelection: true,
        columns: [
            { text: "Data Source", dataIndex: 'DataSource' },
            { text: "Survey Name", dataIndex: 'SurveyName', flex: 1 },
            { text: "QuestionNumber", dataIndex: 'QuestionNumber', flex: 2 },
            { text: "Collection Frequency", dataIndex: 'SurveyCollectionFrequency' },
            { text: "Thematic Tags", dataIndex: 'QuestionThematicTag', flex: 3 }

         ],
        viewConfig: {
            forceFit: true
        },
        width: 1000,
        frame: true,
        height: 800,
        features: [groupingFeature],
        plugins: [{
            ptype: 'rowexpander',
            pluginId: 'expander',
            rowBodyTpl: [
                '<p><b>Question Type:</b> {QuestionType}</p>',
                '<p><b>Question Root:</b> {QuestionRoot}</p>',
                '<p><b>Question Text:</b> {QuestionText}</p>',
                '<p><b>Question No:</b> {QuestionNumber}</p>',
                '<p><b>Question ID:</b>{QuestionID} </p>',
                '<p><b>Question Thematic Group:</b> {QuestionThematicGroup}</p>',
                '<input type="button" value="View Full MetaData"/>',
                '<input type="button" value="View Full Question"/>',
                '<input type="button" value="Add to Map"/>'
            ]
        }],
        collapsible: true,
        animCollapse: false,
        title: 'Results',
        iconCls: 'icon-grid',
        renderTo: 'simple',
        // paging bar on the bottom
        bbar: new Ext.PagingToolbar({
            pageSize: 5,
            store: results,
            displayInfo: true,
            items: [
                '-', {
                    text: 'Details',
                    pressed: true,
                    enableToggle: true,
                    toggleHandler: function (btn, pressed) {
                        var gridpanel = Ext.getCmp('gp');
                        var sm = gridpanel.getSelectionModel();
                        var preview = gridpanel.getPlugin('expander');
                        for (i = 0; i <= 5; i++) {
                            preview.toggleRow(i);
                        }
                        btn.pressed = true;
                    }
                }]
        })
    });



    //    Ext.regModel('Results2', {
    //        fields: ['DataSource', 'QuestionID', 'QuestionNumber', 'QuestionNotes', 'QuestionText']
    //    });

    //    // create the Data Store
    //    var results2 = new Ext.data.Store({
    //        storeId: 'resStore',
    //        model: 'Results2',
    //        sorters: ['DataSource', 'QuestionID'],
    //        //groupField: 'QuestionID',
    //        proxy: {
    //            type: 'ajax',
    //            url: '../../Content/Javascript/Libraries/Ext4/test.json'
    //        }

    //    });

    //    // create the grid
    //    var grid = Ext.create('Ext.grid.Panel', {
    //        store: results,
    //        columns: [
    //            { text: "DataSource", flex: 1, dataIndex: 'DataSource' },
    //            { text: "QuestionID", dataIndex: 'QuestionID' },
    //            { text: "QuestionNumber", dataIndex: 'QuestionNumber' }
    //         ],
    //        viewConfig: {
    //            forceFit: true
    //        },
    //        height: 210,
    //        split: true,
    //        region: 'north'
    //    });

    //    // define a template to use for the detail view
    //    var bookTplMarkup = [
    //         '<p><b>Question No:</b> {QuestionNumber}</p><br>',
    //         '<p><b>Question Text:</b> {QuestionText}</p>',
    //         '<p><b>Notes:</b> {QuestionNotes} </p>'
    //    ];
    //    var bookTpl = Ext.create('Ext.Template', bookTplMarkup);

    //    Ext.create('Ext.Panel', {
    //        renderTo: 'advanced',
    //        frame: true,
    //        title: 'Results',
    //        width: 540,
    //        height: 400,
    //        layout: 'border',
    //        items: [
    //            grid, {
    //                id: 'detailPanel',
    //                region: 'center',
    //                bodyPadding: 7,
    //                bodyStyle: "background: #ffffff;",
    //                html: 'Please select a record to see additional details.'
    //            }]
    //    });

    //    // update panel body on selection change
    //    grid.getSelectionModel().on('selectionchange', function (sm, selectedRecord) {
    //        if (selectedRecord.length) {
    //            var detailPanel = Ext.getCmp('detailPanel');
    //            bookTpl.overwrite(detailPanel.body, selectedRecord[0].data);
    //        }
    //    });

    //    results2.load();

    //    var button = Ext.get('show-btn');

});


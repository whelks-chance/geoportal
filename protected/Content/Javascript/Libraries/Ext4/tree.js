Ext.require([
    'Ext.data.*',
    'Ext.grid.*',
    'Ext.tree.*'
]);

Ext.onReady(function () {
    //we want to setup a model and store instead of using dataUrl
    Ext.regModel('Question', {
        fields: [
            { name: 'DataSource', type: 'string' },
            { name: 'QuestionID', type: 'string' },
            { name: 'QuestionNumber', type: 'string' },
            { name: 'QuestionType', type: 'string' },
            { name: 'QuestionText', type: 'string' },
            { name: 'QuestionNotes', type: 'string' },
            { name: 'QuestionThematicGroup', type: 'string' },
            { name: 'QuestionThematicTag', type: 'string' },
            { name: 'SurveyID', type: 'string' },
            { name: 'SurveyName', type: 'string' }

        ]
    });

//    var store = new Ext.data.TreeStore({
//        //model: 'Question',
//        proxy: {
//            type: 'ajax',
//            //the store will get the content from the .json file
//            url: '../../Content/Javascript/Libraries/Ext4/test.json'
//        },
//        root: {
//            dataIndex: 'DataSource',
//            expanded: true
//        },
//        folderSort: true
//    });

     var store = new Ext.data.TreeStore({
        proxy: {
            type: 'ajax',
             url: '../../Content/Javascript/Libraries/Ext4/test.json'
        },
        root: {
            expanded: true
        },
        sorters: [{
            property: 'leaf',
            direction: 'ASC'
        }, {
            property: 'text',
            direction: 'ASC'
        }]
    });



    store.load();
    //Ext.ux.tree.TreeGrid is no longer a Ux. You can simply use a tree.TreePanel
    var tree = new Ext.tree.TreePanel({
        title: 'Results',
        width: 1000,
        height: 800,
        renderTo: Ext.getBody(),
        collapsible: true,
        useArrows: true,
        rootVisible: true,
        store: store,
        multiSelect: false,
        loadMask: true,
        singleExpand: true,
        //the 'columns' property is now 'headers'
//        columns: [
//        {
//            xtype: 'treecolumn', //this is so we know which column will show the tree
//            text: 'Data Source',
//            flex: 1,
//            sortable: true,
//            dataIndex: 'DataSource'
//        },        
//        {
//            //xtype: 'treecolumn', //this is so we know which column will show the tree
//            text: 'QuestionID',
//            flex: 1,
//            sortable: true,
//            dataIndex: 'QuestionID'
//        }, {
//            text: 'Question Number',
//            flex: 1,
//            dataIndex: 'QuestionNumber',
//            sortable: true
//        }, {
//            text: 'Question Text',
//            flex: 1,
//            dataIndex: 'QuestionText',
//            sortable: true
//        }, {
//            text: 'Question Type',
//            flex: 1,
//            dataIndex: 'QuestionType',
//            sortable: true
//        },        
//        
//        {
//            text: 'Notes',
//            flex: 1,
//            dataIndex: 'QuestionNotes',
//            sortable: true
//        }, {
//            text: 'Thematic Groups',
//            flex: 1,
//            dataIndex: 'QuestionThematicGroup',
//            sortable: true
//        }, {
//            text: 'Thematic Tags',
//            flex: 1,
//            dataIndex: 'QuestionThematicTags',
//            sortable: true
//        }, {
//            text: 'Survey ID',
//            flex: 1,
//            dataIndex: 'SurveyID',
//            sortable: true
//        }, {
//            text: 'Survey Title',
//            flex: 1,
//            dataIndex: 'SurveyName',
//            sortable: true
//        }]
    });
});

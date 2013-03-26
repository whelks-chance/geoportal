GeoPortal.Windows.Questions = Ext.extend(Ext.Window, {
    width: 800,
    height: Ext.getBody().getViewSize().height * 0.8,
    SID: null,
    resizable: false,
    initComponent: function () {
        this.questionStore = new Ext.data.JsonStore({
            fields: ['qid', 'questionNumber', 'questionText', 'group', 'tag'],
            baseParams: { SID: this.SID, start: 0, limit: 30 },
            root: 'questions',
            loadMask: { msg: "Loading Data.."},
            id: 'questions',
            url: QuestionsURL,
             totalProperty: 'totalCount'
        });
        this.questionStore.load();
        this.items = [
            {
                xtype: 'grid',
                height: 770,
                id: 'grdQuestions',
                store: this.questionStore,
                frame: true,
                loadMask: { msg: "Loading Data" },
                stripeRows: true,
                totalProperty: 'totalCount',
                viewConfig: {
                    forceFit: true
                },
                columns: [
                    {
                        xtype: 'gridcolumn',
                        dataIndex: 'questionNumber',
                        header: 'Question Number',
                        sortable: true,
                        width: 150
                    },
                    {
                        xtype: 'gridcolumn',
                        header: 'Question Text',
                        sortable: true,
                        width: 400,
                        dataIndex: 'questionText'
                    },
                    {
                        xtype: 'gridcolumn',
                        header: 'Thematic Group',
                        sortable: true,
                        width: 125,
                        dataIndex: 'group'
                    },
                    {
                        xtype: 'gridcolumn',
                        header: 'Thematic Tag',
                        sortable: true,
                        width: 105,
                        dataIndex: 'tag'
                    }
                ]
            }
        ];
        this.bbar = {
            xtype: 'paging',
            store: this.questionStore,
            pageSize: 30,
            displayInfo: true,
            displayMsg: 'Displaying Questions {0} -{1} of {2}'
        };
        GeoPortal.Windows.Questions.superclass.initComponent.call(this);
    }
});
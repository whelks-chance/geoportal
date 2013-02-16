MyAccountUi = Ext.extend(Ext.Window, {
    title: 'My Account',
    width: 800,
    height: 800,
    modal: true,
    resizable: false,
    initComponent: function () {
        this.items = [
            {
                xtype: 'tabpanel',
                activeTab: 0,
                items: [
                    {
                        xtype: 'panel',
                        title: 'My Account',
                        id: 'tabMyAcc'
                    },
                    {
                        xtype: 'panel',
                        title: 'My Saved Searches',
                        items: [
                            {
                                xtype: 'grid',
                                title: 'My Grid',
                                store: 'gdSearchesStore',
                                height: 800,
                                headerAsText: false,
                                stripeRows: true,
                                id: 'gdSearches',
                                columns: [
                                    {
                                        xtype: 'gridcolumn',
                                        dataIndex: 'string',
                                        header: 'Name',
                                        sortable: true,
                                        width: 100
                                    },
                                    {
                                        xtype: 'numbercolumn',
                                        dataIndex: 'number',
                                        header: 'Description',
                                        sortable: true,
                                        width: 500,
                                        align: 'center'
                                    },
                                    {
                                        xtype: 'datecolumn',
                                        dataIndex: 'date',
                                        header: 'Date',
                                        sortable: true,
                                        width: 100
                                    },
                                    {
                                        xtype: 'booleancolumn',
                                        dataIndex: 'bool',
                                        header: 'Select',
                                        sortable: true,
                                        width: 100
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        xtype: 'panel',
                        title: 'My Saved Maps',
                        height:800,
                        items: [
                            {
                                xtype: 'panel',
                                title: 'Choose A Map:',
                                layout: 'border',
                                height: 800,
                                items: [
                                    {
                                        xtype: 'dataview',
                                        itemSelector: 'div',
                                        tpl: '<tpl for="."><div>Template</div></tpl>',
                                        region: 'center',
                                        autoScroll: true,
                                        width: 600,
                                        id: 'DVMaps'
                                    },
                                    {
                                        xtype: 'panel',
                                        title: 'Details',
                                        region: 'east',
                                        width: 200
                                    }
                                ],
                                tbar: {
                                    xtype: 'toolbar',
                                    id: 'tbfilter',
                                    items: [
                                        {
                                            xtype: 'tbtext',
                                            text: 'Filter:'
                                        },
                                        {
                                            xtype: 'textfield',
                                            selectOnFocus: true,
                                            id: 'tbFilterMaps'
                                        },
                                        {
                                            xtype: 'tbseparator'
                                        },
                                        {
                                            xtype: 'tbtext',
                                            text: 'Sort By:'
                                        },
                                        {
                                            xtype: 'combo',
                                            editable: false,
                                            mode: 'local',
                                            triggerAction: 'all',
                                            lazyInit: false,
                                            displayField: 'desc',
                                            valueField: 'name',
                                            value: 'name',
                                            id: 'cmboSortMaps'
                                        }
                                    ]
                                }
                            }
                        ]
                    },
                    {
                        xtype: 'panel',
                        title: 'My Messages',
                        items: [
                            {
                                xtype: 'listview',
                                columnResize: false,
                                id: 'LVMessages',
                                columns: [
                                    {
                                        xtype: 'lvdatecolumn',
                                        header: 'Date',
                                        format: 'd/y/Y'
                                    },
                                    {
                                        xtype: 'lvcolumn',
                                        header: 'From',
                                        width: 0.25
                                    },
                                    {
                                        xtype: 'lvcolumn',
                                        header: 'Title'
                                    },
                                    {
                                        xtype: 'lvbooleancolumn',
                                        header: 'Read'
                                    }
                                ]
                            }
                        ]
                    }
                ]
            }
        ];
        MyAccountUi.superclass.initComponent.call(this);
    }
});
//var testData = "{"results": [{""ID"": ""1"",""geography"": ""LSOA"", ""sName"": ""WISERD TEST DATA"", ""sYear"": ""2007"", ""selected"": ""false"",""description"": ""Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque nec augue eu ipsum pellentesque pretium at ac augue. Quisque cursus, nibh id lobortis scelerisque, magna lacus cursus turpis, vel ultricies massa lorem ac elit. Sed vitae metus vel est dictum sodales. Maecenas et metus erat. Nullam ullamcorper purus eget orci adipiscing ac pulvinar velit eleifend. Ut sodales, nisl semper ultrices vestibulum, nulla nunc euismod lacus, eu aliquet elit nunc nec nisi. Integer ullamcorper velit est, sit amet pharetra urna. Mauris sit amet lorem sem, eu iaculis nisi. Cras a lectus diam, sit amet ultricies felis. Vestibulum accumsan commodo libero a gravida. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis laoreet enim eget libero mollis non varius lectus vehicula.""}]}";

GeoPortal.Windows.SpatialResults = Ext.extend(Ext.Window, {
    height: Ext.getBody().getViewSize().height * 0.8,
    width: Ext.getBody().getViewSize().width * 0.8,
    frame: true,
    id: 'spatResWin',
    title: 'Spatial Search Results',
    resizable: false,
    maximizable: true,
    minimizable: true,
    animCollapse: true,
    constrainHeader: true,
    listeners: {
        'resize': function () {
            Ext.getCmp('spatResWin').doLayout();
        }
    },
    min: function () {
        this.hide();
        Ext.getCmp('minSS').show();
        Ext.getCmp('minSS').el.frame("#9000A1", 3, { duration: 1 });
    },
    showAnimDuration: 0.25,
    hideAnimDuration: 0.25,
    initComponent: function () {
        var qualRecord = Ext.data.Record.create([{ name: 'sName', mapping: 'sName' }, { sYear: 'sYear', mapping: 'sYear' }, { gName: 'gName', mapping: 'gName'}]);

        this.on({
            'minimize': {
                fn: this.min
            }
        });


        var colorRender = function (value, metaData, record, rowIndex, colIndex, store) {
            var c = value;
            if (c.length > 7) {
                c = '#' + c.substring(3);
            }
            metaData.attr = 'style="background-color:' + c + '" ';
            return '';
        };

        var treeNodes = [
            { text: 'Survey Data',
                leaf: false,
                id: 'Survey Data',
                icon: "images/silk/table_multiple.png",
                expanded: true,
                children: []

            },
            {
                text: 'Qualitative Data',
                leaf: false,
                id: 'Qualitative Data',
                icon: "images/silk/group.png",
                expanded: true,
                children: []
            },
            {
                text: 'Admin Data',
                leaf: false,
                icon: "images/silk/pencil.png",
                expanded: true,
                children: []
            },
            {
                text: 'Grey Data',
                leaf: false,
                icon: "images/silk/page_white_stack.png",
                expanded: true,
                children: []
            }];



        var rMenu = new Ext.menu.Menu({ LayerNode: null, items: [
            new Ext.menu.Item({ text: 'MetaData', icon: 'images/silk/layout.png',
                handler: function (node, e) {
                    var selNode = rMenu.LayerNode;
                    if (selNode.parentNode.id == 'Survey Data') {

                        var SID = selNode.attributes.SID;
                        var metaWindow = new GeoPortal.Windows.MetaData({ SID: SID });
                        var tabPs = Ext.getCmp("tabMeta");
                        tabPs.remove('tabQuestion', true);
                        tabPs.remove('tabResponse', true);
                        metaWindow.doLayout();
                        Ext.getCmp("frmSurvey").getForm().load({ url: SmetaURL, waitMsg: 'Loading.......', method: 'POST', params: { SID: SID} });
                        //Ext.getCmp("frmResponse").getForm().load({ url: RmetaURL, waitMsg: 'Loading.......', method: 'POST', params: { QID: QID} });
                        Ext.getCmp("frmDC").getForm().load(
                            {
                                url: DCmetaURL,
                                waitMsg: 'Loading.......',
                                method: 'POST',
                                sucess: metaWindow.show(),
                                params: {
                                    SID: SID
                                }
                            });

                    } else {

                        var SID = selNode.attributes.SID;
                        var metaWindow = new GeoPortal.Windows.QualMetaData({ SID: SID, title: 'Metadata for ' + selNode.attributes.text });
                        Ext.getCmp("frmQDC").getForm().load({ url: QDCmetaURL, waitMsg: 'Loading.......', method: 'POST', sucess: metaWindow.show(), params: { SID: SID} });


                    };

                }



            }),
            new Ext.menu.Item({ text: 'Colour', icon: 'images/silk/color_wheel.png',

                handler: function (node, e) {
                    var lnode = rMenu.LayerNode;
                    var ui = lnode.getUI();

                    var t = lnode.getOwnerTree();

                    var node = t.getNodeById('x_sid_liw2007_aefa_');

                    var win = new Ext.Window({
                        id: 'colourWindow',
                        title: "Choose layer colour",
                        width: 250,
                        height: 150,
                        layout: 'border',
                        resizable: false,
                        buttons: [{
                            text: 'OK',
                            handler: function (e, f) {

                                var lnode = rMenu.LayerNode;

                                var colour = Ext.getCmp('fieldColour1').getValue();

                                lnode.attributes.colourName = colour;
                                lnode.attributes.colour = '<div style="background-color:' + colour + '; text-align: center; color: white;" >' + colour + '</div>';

                                //lnode.reload();

                                lnode.refresh({ allowChildren: true, allowDrop: true, allowDrag: true, leaf: true, singleClickExpand: true });
                                win.close();
                            }
                        }],
                        layoutConfig: {
                            // layout-specific configs go here
                            titleCollapse: false,
                            animate: true,
                            activeOnTop: true
                        },
                        items: [{
                            xtype: 'form',
                            region: 'center',
                            border: true,
                            labelWidth: 60,
                            labelAlign: 'left',
                            bodyStyle: 'padding:15px;background-color:transparent',
                            defaults: {
                                width: 120,
                                xtype: 'colorpickerfield'
                            },
                            items: [{
                                fieldLabel: 'Colour',
                                name: 'fieldColour1',
                                value: '#F60434',
                                id: 'fieldColour1'
                            }]
                        }
                        ]
                    });

                    win.show();
                }

            }),
            new Ext.menu.Item({
                text: 'Rename',
                icon: 'images/silk/textfield_rename.png',
                handler: function (node, e) {

                    var treeEditor = new Ext.tree.TreeEditor(Ext.getCmp('layers2add'), {}, {
                        cancelOnEsc: true,
                        completeOnEnter: true,
                        selectOnFocus: true,
                        allowBlank: false,
                        listeners: {
                            complete: onTreeEditComplete
                        }
                    });

                    rMenu.hide();

                    var lnode = rMenu.LayerNode;
                    treeEditor.editNode = lnode;
                    treeEditor.startEdit(lnode.ui.textNode);

                }
            })]
        });

        function onTreeEditComplete(treeEditor, n, o) {
            treeEditor.editNode.attributes.text = n;

            //o - oldValue
            //n - newValue
        }

        this.resProxy = new Ext.data.HttpProxy({ api: {
            read: SpatialSearch
        }
        });
        this.qualStore = new Ext.data.GroupingStore({
            reader: new Ext.data.JsonReader({
                fields: ['sName', 'name', 'thematic', 'creator', 'recorddate', 'gName', 'Added'],
                root: 'qualData',
                id: 'dsQualData',
                totalProperty: 'qualTotalCount'
            }),
            baseParams: {
                type: 'Qual'
            },
            proxy: this.resProxy,
            groupField: 'thematic'
        });
        this.resStore = new Ext.data.GroupingStore({
            reader: new Ext.data.JsonReader({
                root: 'quantData',
                totalProperty: 'totalCount',
                remoteSort: false,
                //idProperty: 'sID',
                fields: [{ name: 'sID', type: 'string' }, { name: 'geography' }, { name: 'sName', type: 'string' }, { name: 'sYear', type: 'string' }, { name: 'Added', type: 'bool' }, { name: 'Details', type: 'string' }, { name: 'tName', type: 'string' }, { name: 'gName', type: 'string' }, 'min', 'max']
                // load using script tags for cross domain, if the data in on the same domain as
                // this page, an HttpProxy would be better
            }),
            baseParams: {
                type: 'Quant'
            },
            proxy: this.resProxy,
            groupField: 'geography'
        });

        this.qualgroup = new Ext.grid.GroupingView({ forceFit: true, groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})' });
        this.group = new Ext.grid.GroupingView({ forceFit: true, groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})' });

        Ext.applyIf(this, {
            items: [
                {
                    xtype: 'container',
                    id: 'ResultsContainer',
                    layout: 'column',
                    resizable: true,
                    items: [
                        {
                            xtype: 'container',
                            columnWidth: 0.6,
                            items: [
                                {
                                    xtype: 'tabpanel',
                                    height: this.height * 0.6,
                                    activeTab: 0,
                                    border: false,
                                    tabPosition: 'bottom',
                                    items: [
                                        {
//                                            xtype: 'panel',
//                                            title: 'Survey',
//                                            items: [
//                                                {
                                            title: 'Survey',
                                            xtype: 'grid',
//                                                    height: (this.height * 0.6) - 23,
                                            stripeRows: true,
                                            id: 'grdSurvey',
                                            frame: false,
                                            loadMask: true,
                                            store: this.resStore,
                                            viewConfig: {
                                                forceFit: true
                                            },
                                            view: this.group,
                                            sm: new Ext.grid.RowSelectionModel({
                                                singleSelect: true,
                                                listeners: {
                                                    rowselect: function (sm, row, rec) {
                                                        Ext.getCmp("details").getForm().loadRecord(rec);
                                                    }
                                                }
                                            }),
                                            columns: [
                                                {
                                                    xtype: 'gridcolumn',
                                                    dataIndex: 'sID',
                                                    header: 'ID',
                                                    sortable: true,
                                                    width: 50
                                                }, {
                                                    xtype: 'gridcolumn',
                                                    dataIndex: 'geography',
                                                    header: 'Geography',
                                                    sortable: true,
                                                    width: 50
                                                },
                                                {
                                                    xtype: 'gridcolumn',
                                                    dataIndex: 'sName',
                                                    header: 'SurveyName',
                                                    sortable: true,
                                                    width: 200
                                                },
                                                {
                                                    xtype: 'gridcolumn',
                                                    dataIndex: 'sYear',
                                                    header: 'Date',
                                                    sortable: true,
                                                    width: 100
                                                },
//                                                {
//                                                    xtype: 'actioncolumn',
//                                                    dataIndex: 'showMeta',
//                                                    header: 'Show MetaData?',
//                                                    items: [{
//                                                        icon: 'images/silk/application_form.png',
//                                                        tooltip: 'Show Metadata',
//                                                        handler: function (grid, rowIndex, colIndex) {
//
//
//
//                                                            var ID = record.data.id;
//                                                            var metaWindow = new GeoPortal.Windows.QualMetaData({ title: 'MetaData for :' + ID, SID: ID });
//                                                            Ext.getCmp("frmQDC").getForm().load({ url: QDCmetaURL, waitMsg: 'Loading.......', method: 'POST', sucess: metaWindow.show(), params: { SID: ID} });
//                                                        }
//                                                    }]
//                                                },
                                                {
                                                    xtype: 'actioncolumn',
                                                    items: [
                                                        {
                                                            icon: 'images/silk/cross.png',                // Use a URL in the icon config
                                                            tooltip: 'Add to Map',
                                                            handler: function (grid, rowIndex, colIndex) {
                                                                var store = grid.getStore();
                                                                var rec = store.getAt(rowIndex);
                                                                if (rec.data.Added == false) {
                                                                    rec.set("Added", true);
                                                                    rec.commit();
                                                                    var node = Ext.getCmp('layers2add').root.childNodes[0];
                                                                    Ext.getCmp("details").getForm().loadRecord(rec);



                                                                    var new_node = new Ext.tree.AsyncTreeNode({
                                                                        text: rec.data.tName,
                                                                        leaf: true,
                                                                        icon: "images/silk/table.png",
                                                                        allowDrag: false,
                                                                        children: [],
                                                                        tName: rec.data.tName,
                                                                        unit: rec.data.geography,
                                                                        geographies: rec.data.gName,
                                                                        min: rec.data.min,
                                                                        max: rec.data.max,
                                                                        colourName: '#F70A45',
                                                                        SID: rec.data.sID,
                                                                        colour: '<div style="background-color:#F70A45; text-align: center; color: white"> #F70A45 </div>',
                                                                        uiProvider: Ext.tree.ColumnNodeUI

                                                                    });

                                                                    var id = "";
                                                                    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

                                                                    for (var z = 0; z < 5; z++)
                                                                        id += possible.charAt(Math.floor(Math.random() * possible.length));

                                                                    new_node.setId(id);
                                                                    node.appendChild(new_node);

                                                                }
                                                                else {
                                                                    var survey_node = Ext.getCmp('layers2add').root.childNodes[0];
                                                                    var node_to_remove;
                                                                    for (var i = 0; i < survey_node.childNodes.length; i++) {
                                                                        if (survey_node.childNodes[i].attributes.tName == rec.data.tName) {

                                                                            node_to_remove = survey_node.childNodes[i];
                                                                            survey_node.removeChild(node_to_remove, true);
                                                                            rec.set("Added", false);
                                                                            rec.commit();
                                                                        }

                                                                    }

                                                                }

                                                            },
                                                            getClass: function (value, metaData, record) {

                                                                if (record.data.Added == false) {
                                                                    this.items[0].icon = 'images/silk/cross.png';
                                                                    this.items[0].tooltip = 'Add to Map';

                                                                }
                                                                if (record.data.Added == true) {
                                                                    this.items[0].icon = 'images/silk/tick.png';
                                                                    this.items[0].tooltip = 'Remove from Map';

                                                                }


                                                            }
                                                        }],
                                                    dataIndex: 'Added',
                                                    header: 'Add to Map?',
                                                    sortable: true,
                                                    editable: true,
                                                    width: 80
                                                }
                                            ],
                                            bbar: {
                                                xtype: 'paging',
                                                store: this.resStore,
                                                pageSize: 15,
                                                displayInfo: false,
                                                displayMsg: 'Displaying Results {0} -{1} of {2}',
                                                items: [
                                                    {
                                                        xtype: 'tbseparator'
                                                    },
                                                    {
                                                        xtype: 'button',
                                                        text: 'Save Search',
                                                        icon: 'images/silk/disk.png',
                                                        handler: function () { }

                                                    },
                                                    {
                                                        xtype: 'button',
                                                        text: 'Print Page',
                                                        icon: 'images/silk/printer.png',
                                                        handler: function () {
                                                            var grid = Ext.getCmp('resultsGrid');
                                                            Ext.ux.Printer.print(grid);


                                                        }
                                                    }
                                                ]
                                            }
//                                                }
//                                            ]
                                        },
                                        { title: 'Qual Data',
                                            xtype: 'grid',
//                                            width: 780,
                                            id: 'grdQual',
//                                            frame: true,
                                            stripeRows: true,
                                            view: this.qualgroup,
                                            store: this.qualStore,
                                            sm: new Ext.grid.RowSelectionModel({
                                                singleSelect: true,
                                                listeners: {
                                                    rowselect: function (sm, row, rec) {
                                                        var geogs = "";

                                                        for (var i = 0; i < rec.data.gName.length; i++) {
                                                            geogs += rec.data.gName[i].name + '; ';
                                                        }

                                                        var new_rec = new qualRecord({
                                                            sName: rec.data.name,
                                                            sYear: rec.data.recorddate,
                                                            gName: geogs
                                                        });



                                                        Ext.getCmp("details").getForm().loadRecord(new_rec);
                                                    }
                                                }
                                            }),
                                            viewConfig: {
                                                forceFit: true
                                            },
                                            loadMask: true,
                                            columns: [
                                                {
                                                    xtype: 'gridcolumn',
                                                    dataIndex: 'sName',
                                                    header: 'ID',
                                                    sortable: true,
                                                    width: 100,
                                                    editable: false,
                                                    groupable: false
                                                },
                                                {
                                                    xtype: 'gridcolumn',
                                                    header: 'Title',
                                                    sortable: true,
                                                    width: 300,
                                                    dataIndex: 'name',
                                                    editable: false,
                                                    groupable: false
                                                },
                                                {
                                                    xtype: 'gridcolumn',
                                                    header: 'Thematic Group',
                                                    sortable: true,
                                                    width: 200,
                                                    editable: false,
                                                    dataIndex: 'thematic'
                                                },
                                                {
                                                    xtype: 'gridcolumn',
                                                    header: 'Date of Collection',
                                                    sortable: true,
                                                    width: 130,
                                                    editable: false,
                                                    dataIndex: 'recorddate'
                                                }, {
                                                    xtype: 'actioncolumn',
                                                    items: [
                                                        {
                                                            icon: 'images/silk/cross.png',                // Use a URL in the icon config
                                                            tooltip: 'Add to Map',
                                                            handler: function (grid, rowIndex, colIndex) {
                                                                var store = grid.getStore();
                                                                var rec = store.getAt(rowIndex);
                                                                if (rec.data.Added == false) {
                                                                    rec.set("Added", true);
                                                                    rec.commit();
                                                                    var qualnode = Ext.getCmp('layers2add').root.childNodes[1];

                                                                    var geogs = "";

                                                                    for (var i = 0; i < rec.data.gName.length; i++) {
                                                                        geogs += rec.data.gName[i].name + '; ';
                                                                    }


                                                                    var new_rec = new qualRecord({
                                                                        sName: rec.data.name,
                                                                        sYear: rec.data.recorddate,
                                                                        gName: geogs
                                                                    });

                                                                    Ext.getCmp("details").getForm().loadRecord(new_rec);

                                                                    var new_node = new Ext.tree.AsyncTreeNode({
                                                                        text: rec.data.name,
                                                                        leaf: true,
                                                                        icon: "images/silk/group.png",
                                                                        allowDrag: true,
                                                                        unit: 'Qual',
                                                                        geographies: rec.data.gName,
                                                                        selectable: true,
                                                                        colourName: '#F70A45',
                                                                        SID: rec.data.sName,
                                                                        colour: '<div style="background-color:#F70A45; text-align: center; color: white"> #F70A45 </div>',
                                                                        uiProvider: Ext.tree.ColumnNodeUI

                                                                    });


                                                                    var id = "";
                                                                    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

                                                                    for (var z = 0; z < 5; z++)
                                                                        id += possible.charAt(Math.floor(Math.random() * possible.length));


                                                                    new_node.setId(id);
                                                                    qualnode.appendChild(new_node);

                                                                }
                                                                else {
                                                                    var qual_node = Ext.getCmp('layers2add').root.childNodes[1];
                                                                    var node_to_remove;
                                                                    for (var i = 0; i < qual_node.childNodes.length; i++) {
                                                                        if (qual_node.childNodes[i].attributes.SID == rec.data.sName) {

                                                                            node_to_remove = qual_node.childNodes[i];
                                                                            qual_node.removeChild(node_to_remove, true);
                                                                            rec.set("Added", false);
                                                                            rec.commit();
                                                                        }

                                                                    }

                                                                }

                                                            },
                                                            getClass: function (value, metaData, record) {

                                                                if (record.data.Added == false) {
                                                                    this.items[0].icon = 'images/silk/cross.png';
                                                                    this.items[0].tooltip = 'Add to Map';

                                                                };
                                                                if (record.data.Added == true) {
                                                                    this.items[0].icon = 'images/silk/tick.png';
                                                                    this.items[0].tooltip = 'Remove from Map';

                                                                };


                                                            }
                                                        }],
                                                    dataIndex: 'Added',
                                                    header: 'Add to Map?',
                                                    sortable: true,
                                                    editable: true,
                                                    width: 80
                                                }
                                            ]
                                            , bbar: {
                                            xtype: 'paging',
                                            store: this.qualStore,
                                            pageSize: 15,
                                            displayInfo: true,
                                            displayMsg: 'Displaying Results {0} -{1} of {2}',
                                            items: [
                                                {
                                                    xtype: 'tbseparator'
                                                },
                                                {
                                                    xtype: 'button',
                                                    text: 'Save Search',
                                                    icon: 'images/silk/disk.png',
                                                    handler: function () { }

                                                },
                                                {
                                                    xtype: 'button',
                                                    text: 'Print Page',
                                                    icon: 'images/silk/printer.png',
                                                    handler: function () {
                                                        var grid = Ext.getCmp('qualResults');
                                                        Ext.ux.Printer.print(grid);


                                                    }
                                                }
                                            ]
                                        }
                                        },
                                        {
                                            xtype: 'panel',
                                            title: 'Admin',
                                            disabled: true,
                                            items: [
                                                {
                                                    xtype: 'grid',
                                                    height: 540,
                                                    store: this.resStore,
                                                    columns: [
                                                        {
                                                            xtype: 'gridcolumn',
                                                            dataIndex: 'string',
                                                            header: 'String',
                                                            sortable: true,
                                                            width: 100
                                                        },
                                                        {
                                                            xtype: 'numbercolumn',
                                                            align: 'right',
                                                            dataIndex: 'number',
                                                            header: 'Number',
                                                            sortable: true,
                                                            width: 100
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
                                                            header: 'Boolean',
                                                            sortable: true,
                                                            width: 100
                                                        }
                                                    ]
                                                }
                                            ]
                                        },
                                        {
                                            xtype: 'panel',
                                            title: 'Grey',
                                            store: this.resStore,
                                            disabled: true,
                                            items: [
                                                {
                                                    xtype: 'grid',
                                                    height: 540,
                                                    columns: [
                                                        {
                                                            xtype: 'gridcolumn',
                                                            dataIndex: 'string',
                                                            header: 'String',
                                                            sortable: true,
                                                            width: 100
                                                        },
                                                        {
                                                            xtype: 'numbercolumn',
                                                            align: 'right',
                                                            dataIndex: 'number',
                                                            header: 'Number',
                                                            sortable: true,
                                                            width: 100
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
                                                            header: 'Boolean',
                                                            sortable: true,
                                                            width: 100
                                                        }
                                                    ]
                                                }
                                            ]
                                        }
                                    ]
                                },
                                {
                                    xtype: 'form',
                                    height: this.height * 0.4,
                                    id: 'details',
                                    padding: '2px',
//                                    layout: 'fit',
                                    hideBorders: false,
                                    autoScroll: true,
                                    items: [
//                                        {
//                                            xtype: 'form',

//                                            items: [
                                        {
                                            xtype: 'textfield',
                                            id: 'sName',
                                            anchor: '95%',
                                            fieldLabel: 'Data Name'
                                        },
                                        {
                                            xtype: 'textfield',
                                            anchor: '95%',
                                            id: 'sYear',
                                            fieldLabel: 'Year'
                                        },
                                        {
                                            xtype: 'textarea',
                                            id: 'gName',
//                                            height: 150,
                                            anchor: '95%',
                                            fieldLabel: 'Geographies',
                                            autoScroll: true
                                        }]
//                                        }]
                                }
                            ]
                        },
                        {
                            xtype: 'container',
                            height: this.height * .98,
                            layout: 'column',
                            columnWidth: 0.4,
                            items: [
                                new Ext.ux.tree.ColumnTree({
                                    width: 320,
                                    clicksToEdit: 1,
                                    editable: true,
                                    fields: ['text', 'colour'],
                                    listeners: {
                                        "contextmenu": {
                                            fn: function (node, e) {
                                                var xy = e.getXY();
                                                rMenu.LayerNode = node;
                                                if (node.attributes.leaf == true) {
                                                    rMenu.showAt(xy);

                                                }
                                            }
                                        },
                                        "click" : {
                                            fn: function (node, e) {
                                                var xy = e.getXY();
                                                rMenu.LayerNode = node;
                                                if (node.attributes.leaf == true) {
                                                    rMenu.showAt(xy);

                                                }
                                            }
                                        }

                                    },
                                    columns: [{
                                        xtype: 'treecolumn',
                                        header: 'Name',
                                        width: 230,
                                        dataIndex: 'text'
                                    }, {
                                        xtype: 'treecolumn',
                                        header: 'Colour',
                                        width: 100,
                                        dataIndex: 'colour'
                                    }],
                                    title: 'Data to be Mapped',
                                    headerAsText: false,
                                    id: 'layers2add',
                                    height: this.height * 0.98,
                                    rootVisible: true,
                                    columnWidth: 2,
                                    preloadChildren: true,
                                    loader: new Ext.tree.TreeLoader({
                                        uiProviders: {
                                            'col': Ext.tree.ColumnNodeUI
                                        }
                                    }),
                                    root: new Ext.tree.AsyncTreeNode({
                                        text: 'Data to Map',
                                        expanded: true,
                                        leaf: false,
                                        allowChildren: true,
                                        icon: 'images/silk/world.png',
                                        children: treeNodes
                                    })

                                })

                            ]
                        }
                    ]
                }
            ],
            bbar: {
                xtype: 'toolbar',
                items: [
                    {
                        xtype: 'button',
                        icon: 'images/silk/arrow_rotate_clockwise.png',
                        text: 'Reset',
                        handler: function () {
                            var root = Ext.getCmp('layers2add').getRootNode();

                            for (var i = 0; i < root.childNodes.length; i++) {
                                var node = root.childNodes[i];

                                node.removeAll(true);
                            }

                            var grid = Ext.getCmp('grdSurvey');
                            grid.store.reload();
                            grid.getView().refresh();


                        }
                    },
                    {
                        xtype: 'tbfill'
                    },
                    {
                        xtype: 'button',
                        icon: 'images/silk/add.png',
                        text: 'Add to Map',
                        handler: function () {

                            var rootCMP = Ext.getCmp('layers2add');

                            var root = rootCMP.getRootNode();

                            var LayerType = "Proportional";

                            for (var i = 0; i < root.childNodes.length; i++) {
                                var node = root.childNodes[i];

                                if (node.attributes.text == 'Survey Data') {
                                    for (var q = 0; q < node.childNodes.length; q++) {
                                        var layer = node.childNodes[q];

                                        var min = layer.attributes.min;
                                        var max = layer.attributes.max;

                                        var fromColour = '#FFFFFF';

                                        showResults(layer.attributes.geographies, layer.attributes.text, fromColour, layer.attributes.colourName, false, layer.attributes.unit, 'Survey Data', layer.attributes.SID, layer.attributes.id, layer.attributes.tName, min, max);


                                    }

                                } else if (node.attributes.text == 'Qualitative Data') {

                                    for (var p = 0; p < node.childNodes.length; p++) {
                                        var quallayer = node.childNodes[p];

                                        var id = buildQualMapLayer(quallayer.attributes.text, "Qualitative Data", "Transcripts", quallayer.attributes.SID, quallayer.attributes.text, LayerType, quallayer.attributes.colourName);

                                        for (var geogs = 0; geogs < quallayer.attributes.geographies.length; geogs++) {
                                            var lat = quallayer.attributes.geographies[geogs].lat;
                                            var lon = quallayer.attributes.geographies[geogs].lon


                                            var feature = new Object();
                                            feature.lat = lat;
                                            feature.lon = lon;
                                            feature.colour = quallayer.attributes.colourName;
                                            feature.counts = (quallayer.attributes.geographies[geogs].counts * 2);
                                            feature.title = quallayer.attributes.geographies[geogs].name;
                                            feature.QID = quallayer.attributes.SID;

                                            addQualFeature(feature, id, LayerType)


                                        }

                                    }
                                } else {
                                    console.log('else')

                                    var id = buildQualMapLayer(quallayer.attributes.text, "Qualitative Data", "Transcripts", quallayer.attributes.SID, quallayer.attributes.text, LayerType);

                                    for (var geo = 0; geo < quallayer.attributes.geographies.length; geo++) {
                                        var lat = quallayer.attributes.geographies[geo].lat;
                                        var lon = quallayer.attributes.geographies[geo].lon


                                        var feature = new Object();
                                        feature.lat = lat;
                                        feature.lon = lon;
                                        feature.colour = quallayer.attributes.colourName;
                                        feature.counts = (quallayer.attributes.geographies[geo].counts * 2);
                                        feature.title = quallayer.attributes.geographies[geo].name;
                                        feature.QID = quallayer.attributes.SID;
                                        addQualFeature(feature, id, LayerType)

                                    }

                                }

                                Ext.getCmp('spatResWin').minimize();

                            }

////                            //TODO remove me!!
//                            Ext.MessageBox.confirm('Confirm', 'Do you want to any qualitative data to the map as a Heatmap?',
//
//                                function (btn, text) {
//
//
//                                    if (btn == 'yes') {
//                                        LayerType = "HeatMap";
//                                    };

//
//                                }
//                            );

                        }
                    }
                ]
            }
        });


        GeoPortal.Windows.SpatialResults.superclass.initComponent.call(this);
    }
});




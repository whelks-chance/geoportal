GeoPortal.Windows.Results = Ext.extend(Ext.Window, {
	frame: true,
	width: Ext.getBody().getViewSize().width * 0.6,
	height: Ext.getBody().getViewSize().height * 0.8,
	layout: 'fit',
	resizable: true,
    maximizable: true,
    minimizable: true,
    id: 'resWin',
//	autoScroll: true,
	// plugins: rowExpander,
	initComponent: function () {
        this.on({
            'minimize': {
                fn: this.min
            }
        });
		this.resProxy = new Ext.data.HttpProxy({ api: {
			read: simpleSearchURL
		}
		});
		this.qualProxy = new Ext.data.HttpProxy({ api: {
			read: qualSearchURL
		}
		});
		this.resStore = new Ext.data.GroupingStore({
			reader: new Ext.data.JsonReader({
				root: 'results',
				totalProperty: 'totalCount',
				id: 'RecordID',
				remoteSort: true,
				idProperty: 'RecordID',
				fields: ['RecordID', 'SurveyName', 'SurveyCollectionFrequency', 'SurveyID', 'QuestionThematicTag', 'QuestionNumber', 'QuestionText', 'QuestionType', 'QuestionRoot', 'QuestionNotes', 'QuestionID', 'DataSource', 'QuestionThematicGroup', 'spatial']
				// load using script tags for cross domain, if the data in on the same domain as
				// this page, an HttpProxy would be better
			}),
			proxy: this.resProxy,
			groupField: 'SurveyName',
			baseParams: {
				mappable: false
			}
		});
		this.qualStore = new Ext.data.GroupingStore({
			reader: new Ext.data.JsonReader({
				fields: ['id', 'title', 'thematicgroup', 'creator', 'qdate', 'pages'],
				root: 'results',
				id: 'dsQualData',
				totalProperty: 'totalCount'
			}),
			proxy: this.qualProxy,
			groupField: 'thematicgroup'
		});
		this.group = new Ext.grid.GroupingView({ forceFit: true, groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})' });
		this.qualgroup = new Ext.grid.GroupingView({ forceFit: true, groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})' });
		//		this.rowExpander = new Ext.ux.grid.RowExpander({
		//			tpl: new Ext.Template(
		//			  '<div style="padding: 5px; border-style: dotted; border-width: 1px; border-color: gray>"',
		//			  '<p><b>Question Type:</b> {QuestionType}</p>',
		//				'<p><b>Question Root:</b> {QuestionRoot}</p>',
		//				'<p><b>Question Text:</b> {QuestionText}</p>',
		//				'<p><b>Question No:</b> {QuestionNumber}</p>',
		//				'<p><b>Question ID:</b>{QuestionID} </p>',
		//				'<p><b>Question Thematic Group:</b> {QuestionThematicGroup}</p>',
		//				'</div>'
		//		)
		//		});
		this.saction = new Ext.ux.grid.RowActions({
			header: 'Options'
			// ,autoWidth:false
			// ,hideMode:'display'
            , width : 50
			, keepSelection: true
			, actions: [{
				iconCls: 'icon-meta',
				tooltip: 'View MetaData'
//                icon: 'images/silk/application_side_list.png'
			}, {
			    	iconCls: 'icon-map',
//            icon:'images/silk/map_app.png',
				tooltip: 'Map Data'
			}]
			, callbacks: {
				'icon-plus': function (grid, record, action, row, col) {
					Ext.Msg.alert('Callback: icon-plus', 'You have clicked row: <b>{0}</b>, action: <b>{0}</b>', row, action);
				}
			}
		});
		this.saction.on({
			action: function (grid, record, action, row, col) {
				var SID = record.data.SurveyID;
				var QID = record.data.QuestionID;
				if (action == 'icon-map') {
					if (record.data.spatial == true) {
						var SpatialWin = new GeoPortal.Windows.AddSpatial({ SurveyID: SID });
						SpatialWin.UnitStore.load({ params: { SurveyID: SID} });
						SpatialWin.show();
					}else {

					Ext.Msg.alert('Add Spatial Data', 'There is currently no spatial data linked with this data - please chose a different dataset to map.')

					}
				} else {
					var metaWindow = new GeoPortal.Windows.MetaData({ SID: SID });
					Ext.getCmp("frmQuestion").getForm().load({ url: QmetaURL, waitMsg: 'Loading.......', method: 'POST', params: { ID: QID} });
					Ext.getCmp("frmSurvey").getForm().load({ url: SmetaURL, waitMsg: 'Loading.......', method: 'POST', params: { SID: SID} });
					Ext.getCmp("frmResponse").getForm().load({ url: RmetaURL, waitMsg: 'Loading.......', method: 'POST', params: { QID: QID} });
					Ext.getCmp("frmDC").getForm().load({ url: DCmetaURL, waitMsg: 'Loading.......', method: 'POST', sucess: metaWindow.show(), params: { SID: SID} });

				}



				//Ext.Msg.alert('Event: action', 'You have clicked row: <b>{0}</b>, action: <b>{1}</b>', row, action);
			}
		});
		this.qaction = new Ext.ux.grid.RowActions({
			header: 'Options'
			// ,autoWidth:false
			// ,hideMode:'display'
            , width : 50
            , paddingRight: 50
			, keepSelection: true
			, actions: [{
				iconCls: 'icon-meta'
				, tooltip: 'View MetaData'
			}, {
				iconCls: 'icon-map'
				, tooltip: 'Map Data'
			}]
			, callbacks: {
				'icon-plus': function (grid, record, action, row, col) {
					Ext.Msg.alert('Callback: icon-plus', 'You have clicked row: <b>{0}</b>, action: <b>{0}</b>', row, action);
				}
			}
		});
		this.qaction.on({
			action: function (grid, record, action, row, col) {
				var ID = record.data.id;

				if (action == 'icon-map') {
					var SpatialWin = new GeoPortal.Windows.SpatialQual({ QID: ID });
					SpatialWin.show();
				} else {
					var metaWindow = new GeoPortal.Windows.QualMetaData({ title: 'MetaData for :' + ID, SID: ID });
					Ext.getCmp("frmQDC").getForm().load({ url: QDCmetaURL, waitMsg: 'Loading.......', method: 'POST', sucess: metaWindow.show(), params: { SID: ID} });

				}



				//Ext.Msg.alert('Event: action', 'You have clicked row: <b>{0}</b>, action: <b>{1}</b>', row, action);
			}
		});
		this.items = [
		{ xtype: 'tabpanel',
			tabPosition: 'bottom',
			border: false,
			activeTab: 0,
			items: [
			{
				title: 'Survey Data',
				xtype: 'grid',
				disableSelection: true,
				id: 'resultsGrid',
				store: this.resStore,
				frame: false,
				loadMask: true,
				stripeRows: true,
				totalProperty: 'totalCount',
				viewConfig: {
					forceFit: true
				},
				view: this.group,
				flex: 1,
//				id: 'resultsGrid',
				columns: [//this.rowExpander,
				{header: "Data Source", dataIndex: 'DataSource' },
				{ header: "Survey Name", dataIndex: 'SurveyName' },
				{ header: "QuestionNumber", dataIndex: 'QuestionNumber' },
				{ header: "Collection Frequency", dataIndex: 'SurveyCollectionFrequency' },
				{ header: "Thematic Tags", dataIndex: 'QuestionThematicTag' },
				{header: 'Spatial', dataIndex: 'spatial', type: 'Boolean'},
				this.saction
				],
				plugins: [this.saction], //this.rowExpander,
				bbar: {
					xtype: 'paging',
					store: this.resStore,
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
								var grid = Ext.getCmp('resultsGrid');
								Ext.ux.Printer.print(grid);


							}
						}
					]
				}
			}, { title: 'Qual Data',
				xtype: 'grid',
//				width: 780,
                    width: '100%',
				id: 'qualResults',
				frame: false,
				flex: 1,
				disableSelection: true,
				stripeRows: true,
				view: this.qualgroup,
				store: this.qualStore,
				viewConfig: {
					forceFit: true
				},
				plugins: [this.qaction],
				loadMask: true,
				columns: [
					{
						xtype: 'gridcolumn',
						dataIndex: 'id',
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
						dataIndex: 'title',
						editable: false,
						groupable: false
					},
					{
						xtype: 'gridcolumn',
						header: 'Thematic Group',
						sortable: true,
						width: 200,
						editable: false,
						dataIndex: 'thematicgroup'
					},
					{
						xtype: 'gridcolumn',
						header: 'Pages',
						sortable: true,
						width: 50,
						editable: false,
						dataIndex: 'pages'
					},
					{
						xtype: 'gridcolumn',
						header: 'Date of Collection',
						sortable: true,
						width: 130,
						editable: false,
						dataIndex: 'qdate'
					}, this.qaction
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
			}, { title: 'Grey Data', disabled: true }, { title: 'Administrative Data', disabled: true}]
		}
		];
		GeoPortal.Windows.Results.superclass.initComponent.call(this);
	}
});
var SearchPanel = new Ext.Panel({
    title: "Quick Search",
    collapsible: true,
    items: [{
        xtype: 'form',
        id: 'frmSimpleSearch',
        frame: true,
        monitorValid: true,
        labelWidth: 60,
        buttons: [{
            xtype: 'button',
            icon: '../images/silk/magnifier.png',
            id: 'btnSimpleSearch',
            iconAlign: 'right',
            text: 'Search',
            handler: function () {
                var resWin = Ext.getCmp('resWin');
                if (resWin != null) {
                    resWin.destroy();
                };

                var frmSimpleSearch = Ext.getCmp('frmSimpleSearch');
                if (frmSimpleSearch.form.isValid()) {
                    frmSimpleSearch.getForm().submit({
                        url: VerifySearchURL,
                        params: { start: 0, limit: 15 },
                        //method: 'post',
                        waitMsg: 'Retrieving Search Results....',
                        success: function () {
                            var txtfld = Ext.getCmp('txtSearch');
                            var val;
                            if (val == null) { val = txtfld.getValue() };
                            var resWin = new GeoPortal.Windows.Results({ title: 'Results - Search terms "' + val + '"' });

                            resWin.resStore.load({ url: simpleSearchURL, params: { keywords: val, start: 0, limit: 15} });

                            resWin.show();
                        },
                        failure: function () {

                            Ext.Msg.alert('No Results Found', 'Sorry no results were found matching your search terms');

                        }
                    })
                };
            }
        }],
        items: [{
            xtype: 'textfield',
            id: 'txtSearch',
            anchor: '100%',
            name: 'Keywords',
            fieldLabel: 'Keywords',
            allowBlank: false,
            emptyText: 'Comma seperated keywords....'
        }]

    }],
    bbar: new Ext.Toolbar({
        items: ["->", new Ext.Button({
            text: 'Advanced Search',
            icon: '../images/silk/add.png',
            iconAlign: 'right',
            tooltip: "Advanced Search",
            handler: this.OnSearchPanelExpand
        })]
    })

})
/**
 * Created with JetBrains PhpStorm.
 * User: wiserd
 * Date: 03/07/13
 * Time: 17:54
 * To change this template use File | Settings | File Templates.
 */
/**
 * @class Ext.LinkButton
 * @extends Ext.Button
 * A Button which encapsulates an &lt;a> element to enable navigation, or downloading of files.
 * @constructor
 * Creates a new LinkButton
 */
Ext.LinkButton = Ext.extend(Ext.Button, {
//    template: new Ext.Template(
//        '<table cellspacing="0" class="x-btn {3}"><tbody class="{1}">',
//        '<tr>',
//        '<td class="x-btn-tl"><i>&#160;</i></td>',
//        '<td class="x-btn-tc"></td>',
//        '<td class="x-btn-tr"><i>&#160;</i></td>',
//        '</tr>',
//        '<tr>',
//        '<td class="x-btn-ml"><i>&#160;</i></td>',
//        '<td class="x-btn-mc">',
//        '<em class="{2}" unselectable="on">',
//        '<a href="{4}" style="display:block" target="{5}" class="x-btn-text">{0}</a>',
//        '</em>',
//        '</td>',
//        '<td class="x-btn-mr"><i>&#160;</i></td>',
//        '</tr>',
//        '<tr>',
//        '<td class="x-btn-bl"><i>&#160;</i></td>',
//        '<td class="x-btn-bc"></td>',
//        '<td class="x-btn-br"><i>&#160;</i></td>',
//        '</tr>',
//        '</tbody></table>'
//    ).compile(),
    template: new Ext.Template(
        '<table id="{4}" cellspacing="0" class="x-btn {3}"><tbody class="{1}">',
        '<tr><td class="x-btn-tl"><i> </i></td><td class="x-btn-tc"></td><td class="x-btn-tr"><i> </i></td></tr>',
        '<tr>',
        '<td class="x-btn-ml"><i> </i></td>',
        '<td class="x-btn-mc">',
        '<em class="{2}" unselectable="on">',
        '<a href="{5}" style="display:block" target="{6}" class="x-btn-text">{0}</a>',
        '</em>',
        '</td>',
        '<td class="x-btn-mr"><i> </i></td>',
        '</tr>',
        '<tr><td class="x-btn-bl"><i> </i></td><td class="x-btn-bc"></td><td class="x-btn-br"><i> </i></td></tr>',
        '</tbody></table>'
    ).compile(),
    buttonSelector : 'a:first',

    /**
     * @cfg String href
     * The URL to create a link for.
     */
    /**
     * @cfg String target
     * The target for the &lt;a> element.
     */
    /**
     * @cfg Object
     * A set of parameters which are always passed to the URL specified in the href
     */
    baseParams: {},

//  private
    params: {},

    getTemplateArgs: function() {
        return Ext.Button.prototype.getTemplateArgs.apply(this).concat([this.getHref(), this.target]);
    },

    onClick : function(e){
        if(e.button != 0){
            return;
        }
        if(this.disabled){
            this.stopEvent(e);
        } else {
            if (this.fireEvent("click", this, e) !== false) {
                if(this.handler){
                    this.handler.call(this.scope || this, this, e);
                }
            }
        }
    },

    // private
    getHref: function() {
        var result = this.href;
        var p = Ext.urlEncode(Ext.apply(Ext.apply({}, this.baseParams), this.params));
        if (p.length) {
            result += ((this.href.indexOf('?') == -1) ? '?' : '&') + p;
        }
        return result;
    },

    /**
     * Sets the href of the link dynamically according to the params passed, and any {@link #baseParams} configured.
     * @param {Object} Parameters to use in the href URL.
     */
    setParams: function(p) {
        this.params = p;
        this.el.child(this.buttonSelector, true).href = this.getHref();
    }
});
Ext.reg('linkbutton', Ext.LinkButton);

GeoPortal.Windows.RemoteDataResults = Ext.extend(Ext.Window, {
    width: 800,
    height: Ext.getBody().getViewSize().height * 0.8,
    apiName : null,
    DatasetID : null,
    BoundaryID : null,
    MeasuresID : null,
    CSVurl : "",
    layout : 'fit',
//    resizable: false,
    initComponent: function () {
        console.log(this.CSVurl);

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
                id: 'grdRemoteResults',
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
        this.bbar = {
            xtype: 'paging',
            store: this.RemoteDataStore,
            pageSize: 30,
            displayInfo: true,
            displayMsg: 'Displaying Questions {0} -{1} of {2}',
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
                },
                {
                    xtype:'linkbutton',
                    text: 'Download CSV',
                    icon: 'images/silk/page_excel.png',
                    href: this.CSVurl
                }
            ]
        };
        GeoPortal.Windows.RemoteDataResults.superclass.initComponent.call(this);
    }
});
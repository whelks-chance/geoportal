<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 23/04/13
 * Time: 19:14
 * To change this template use File | Settings | File Templates.
 */

/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - About';
$this->breadcrumbs=array(
    'About',
);
//phpinfo();

?>

<h1>textgui testing</h1>
<!--    <div id="editor-grid"/>-->

<!--    This page is not designed at all, and should not be used as an example for anything-->

<script type="text/javascript">

function formatDate(value){
    return value ? value.dateFormat('M d, Y') : '';
}

Ext.Ajax.timeout = 120000; // 60 seconds

Ext.ns('Portal');
Ext.onReady(function(){

//
//
//
//
//
//    var fm = Ext.form;
//
//    var cm = new Ext.grid.ColumnModel({
//        // specify any defaults for each column
//        defaults: {
//            sortable: true // columns are not sortable by default
//        },
//        columns: [
////        {
////        id: 'name',
////        header: 'Common Name',
////        dataIndex: 'name',
////        width: 220,
////        // use shorthand alias defined above
////        editor: new fm.TextField({
////            allowBlank: false
////        })
////    },
//            {
//                id: 'name',
//                header: 'Common Name',
//                dataIndex: 'name',
//                width: 220,
//                // use shorthand alias defined above
//                editor: new fm.TextField({
////                allowBlank: false
//                })
//            },
//            {
//                id: 'type',
//                header: 'type',
//                dataIndex: 'type',
//                width: 220,
//                // use shorthand alias defined above
//                editor: new fm.TextField({
////                allowBlank: false
//                })
//            },
//            {
//                id: 'latitude',
//                header: 'latitude',
//                dataIndex: 'latitude',
//                width: 220,
//                // use shorthand alias defined above
//                editor: new fm.TextField({
////                allowBlank: false
//                })
//            },
//            {
//                id: 'longitude',
//                header: 'longitude',
//                dataIndex: 'longitude',
//                width: 220,
//                // use shorthand alias defined above
//                editor: new fm.TextField({
////                allowBlank: false
//                })
//            },
////        {
////        header: 'Light',
////        dataIndex: 'light',
////        width: 130,
////        editor: new fm.ComboBox({
////            typeAhead: true,
////            triggerAction: 'all',
////            // transform the data already specified in html
////            transform: 'light',
////            lazyRender: true,
////            listClass: 'x-combo-list-small'
////        })
////    }, {
////        header: 'Price',
////        dataIndex: 'price',
////        width: 70,
////        align: 'right',
////        renderer: 'usMoney',
////        editor: new fm.NumberField({
////            allowBlank: false,
////            allowNegative: false,
////            maxValue: 100000
////        })
////    }, {
////        header: 'Available',
////        dataIndex: 'availDate',
////        width: 95,
////        renderer: formatDate,
////        editor: new fm.DateField({
////            format: 'm/d/y',
////            minValue: '01/01/06',
////            disabledDays: [0, 6],
////            disabledDaysText: 'Plants are not available on the weekends'
////        })
////    },
//            {
//                xtype: 'checkcolumn',
//                header: 'save',
//                dataIndex: 'save',
//                width: 55
//            }
//        ]
//    });
//
//// create the Data Store
//    var store = new Ext.data.Store({
//        // destroy the store if the grid is destroyed
//        autoDestroy: true,
//
//        // load remote data using HTTP
//        url: saveTaggingMetadata,
//
//        // specify a XmlReader (coincides with the XML format of the returned data)
//        reader: new Ext.data.JsonReader({
//            // records will have a 'plant' tag
////            record: 'plant',
//            root: 'tags',
//            // use an Array of field definition objects to implicitly create a Record constructor
//            fields: [
//                // the 'name' below matches the tag name to read, except 'availDate'
//                // which is mapped to the tag 'availability'
////                {name: 'common', type: 'string'},
////                {name: 'botanical', type: 'string'},
////                {name: 'light'},
////                {name: 'price', type: 'float'},
////                // dates can be automatically converted by specifying dateFormat
////                {name: 'availDate', mapping: 'availability', type: 'date', dateFormat: 'm/d/Y'},
////                {name: 'indoor', type: 'bool'}
//
//                { name: 'name', type: 'string' },
//                { name: 'type', type: 'string' },
//                { name: 'latitude', type: 'string'},
//                { name: 'longitude', type: 'string'},
//                {name: 'save', type: 'boolean'}
//            ]
//        }),
//
//        sortInfo: {field:'name', direction:'ASC'}
//    });
//
//// create the editor grid
//    var grid = new Ext.grid.EditorGridPanel({
//        store: store,
//        cm: cm,
////        renderTo: 'editor-grid',
//        width: 600,
//        height: 300,
//        autoExpandColumn: 'name', // column with this id will be expanded
//        title: 'Edit Plants?',
//        frame: true,
//        clicksToEdit: 1
////        tbar: [{
////            text: 'Add Plant',
////            handler : function(){
////                // access the Record constructor through the grid's store
////                var Word = grid.getStore().recordType;
////                var w = new Word({
////                    name: 'name',
////                    type: 'type',
////                    latitude: '0',
////                    longitude: '0',
////                    save: true
////                });
////                grid.stopEditing();
////                store.insert(0, p);
////                grid.startEditing(0, 0);
////            }
////        }]
//    });
//
//// manually trigger the data store load
//    store.load({
//        // store loading is asynchronous, use a load listener or callback to handle results
//        callback: function(){
//            Ext.Msg.show({
//                title: 'Store Load Callback',
//                msg: 'store was loaded, data available for processing',
//                modal: false,
//                icon: Ext.Msg.INFO,
//                buttons: Ext.Msg.OK
//            });
//        }
//    });












    Portal.Innerpanel = Ext.extend(Ext.form.FormPanel, {
//            width: 500,
//            height: '100%',
//        closable: true,
//            padding: 10,
//            autoScroll: true,
//        loadMask: true,
//        loadMsg: 'loading.....',
        title: 'Advanced Search',
        layout: 'fit',
        id: 'advPanel',

        initComponent: function () {

            Ext.Ajax.request({
                url: SpatialSearch,
                method : 'POST',
                params : {
                    geography:'POLYGON((280917.80738979 286378.21637517,304553.93338979 277911.54437517,272803.91338979 253922.64037517,280917.80738979 286378.21637517))',
                    start:0,
                    limit:15,
                    type:'Qual'
                },
                success: function(resp) {
                    console.log('success!');
                    var responseData = Ext.decode(resp.responseText);
                    console.log(responseData);
                },
                failure: function(resp) {
                    console.log('failure!');
                }
            });

            var surveyStore = new Ext.data.JsonStore ({
                fields: [
                    {name: 'SurveyName', mapping: 'SurveyName'},
                    {name: 'SurveyID',  mapping: 'SurveyID'}],
                id: "surveyStore",
                root : "surveyData"
            });

            var thematicStore = new Ext.data.JsonStore ({
                fields: [
                    {name: 'theme', mapping: 'grouptitle'},
                    {name: 'ID',  mapping: 'tgroupid'}],
                id: "thematicStore",
                root : "thematicData"
            });

            Ext.Ajax.request({
                url: advancedSearchMetaURL,
                method : 'POST',
                success: function(resp) {
                    console.log('success!');
                    var responseData = Ext.decode(resp.responseText);
                    surveyStore.loadData(responseData);
                    thematicStore.loadData(responseData);
                },
                failure: function(resp) {
                    console.log('failure!');
                }
            });

            Ext.Ajax.request({
                url: dataOptionLists,
                method : 'POST',
                params : {
                    visibilities: true,
                    roles: true,
                    surveys: true,
                    users: true
                },
                success: function(resp) {
                    console.log('success!');
                    var responseData = Ext.decode(resp.responseText);
                    surveyStore.loadData(responseData);
                    thematicStore.loadData(responseData);
                },
                failure: function(resp) {
                    console.log('failure!');
                }
            });


            this.items = [
                {

                    xtype: 'fieldset',
                    title: 'Keywords',
                    items: [
                        {
                            xtype: 'button',
                            id: 'btnCookie',
                            text: 'Cookie Policy',
                            icon: 'images/silk/database_add.png',
                            handler: function () {

                                var cookieForm = new GeoPortal.Windows.Cookies();
                                cookieForm.show()

                            }
                        },
                        {
                            xtype: 'textfield',
                            id: 'txtAdvKeyword',
                            emptyText: 'Comma separated keywords....',
                            anchor: '100%',
                            fieldLabel: 'Keywords',
                            name: 'Keywords'
                        },
                        {
                            xtype: 'combo',
                            id: 'cmboSurvey',
                            anchor: '100%',
                            fieldLabel: 'Select Survey',
                            name: 'Survey',
                            triggerAction: 'all',
                            displayField: 'SurveyName',
                            hiddenName: 'SurveysId',
                            valueField: 'SurveyID',
                            mode: 'local',
                            store : surveyStore
                        },
                        {
                            xtype: 'combo',
                            tpl: '<tpl for="."><div ext:qtip="hovertext - {theme} - {grouptitle}" class="x-combo-list-item">{theme}</div></tpl>',
                            id: 'cmboThematic',
                            anchor: '100%',
                            fieldLabel: 'Thematic Group',
                            name: 'Thematic',
                            hiddenName: 'ThematicID',
                            displayField: 'theme',
                            valueField: 'ID',
                            mode: 'local',
                            triggerAction: 'all',
                            store : thematicStore
                        },
                        {
                            xtype: 'button',
                            id: 'btnAdvSearchFrm',
                            icon: 'images/silk/magnifier.png',
                            text: 'Search',
                            tooltip: 'Submit Search',
                            handler: this.submitAll
                        },
                        {
                            xtype: 'box',
                            autoEl: {
                                tag: 'a',
                                href: 'http://www.google.com/',
                                target: '_blank',   //  for open in new window
                                cn: 'Google'
                            }
                        }
                    ]
                }
            ];
            Portal.Innerpanel.superclass.initComponent.call(this);
        },
        submitAll : function() {
            var advPanel = Ext.getCmp('advPanel');

            advPanel.getForm().submit({
                url: advancedSearchURL,
                method: 'post'
            });
        }
    });

    var inner = new Portal.Innerpanel();

    var advSearch = new Ext.Window({
        title: 'Advanced Search',
        id: 'advSearch',
        width: 800,
        height: 600,
        layout: 'fit',
        maximizable: true,
        items: [
            {
                xtype: 'tabpanel',
                tabPosition: 'top',
//                        border: false,
                activeTab: 0,
                items: [
                        new GeoPortal.Forms.ONSAPI(),
                    new GeoPortal.Forms.ProjectManagement(),
                    new GeoPortal.Forms.AdvancedSearch(),
//                    new GeoPortal.Forms.Tagging(),
                    new GeoPortal.Forms.QuestionMatching(),
                    inner,
                    new GeoPortal.Forms.RemoteData(),
                    {
                        xtype: 'panel',
                        title: 'My Messages'
                    },
//                    {
//                        xtype: 'panel',
//                        title: 'Data Entry',
//                        layout: 'border',
//                        items : [
//                            {
//                                xtype: 'button',
//                                region: 'center',
//                                height: '10%',
//                                width: '20%',
//                                id: 'btnDataEntry',
//                                icon: 'images/silk/application_get.png',
//                                text: 'DataEntry',
//                                type: 'reset',
//                                handler : function() {
//                                    var dataEntryWin = new GeoPortal.Windows.DataEntry();
//                                    dataEntryWin.show();
//                                }
//                            }
//
//                        ]
//                    }
//                    new GeoPortal.Windows.DataEntry()
                    {
                        xtype: 'panel',
                        title: 'Data Entry',
                        layout: 'border',
                        items : [
                            {
                                xtype: 'field',
                                region: 'north',
                                id: 'breadcrumb',
                                readOnly: 'true',
                                value: 'DC -> Survey -> Question -> Response',
                                width: '100%',
//                                    anchor: '97%',
                                name: 'breadcrumb',
                                updateBreadcrumb : function(wid, sid, qid, rid){
                                    var surveyField = Ext.getCmp('breadcrumb');

                                    var previous = surveyField.getValue().split("  =>  ")

                                    if(wid != null) {
                                        previous[0] = "DC " + wid;
                                    }
                                    if(sid != null) {
                                        previous[1] = "Survey " + sid;
                                    }
                                    if(qid != null) {
                                        previous[2] = "Question " + qid;
                                    }
                                    if(rid != null) {
                                        previous[3] = "Response " + rid;
                                    }
                                    surveyField.setValue(previous[0] + '  =>  ' + previous[1] + '  =>  ' + previous[2] + '  =>  ' + previous[3]);
                                }
                            },
                            {
                                xtype: 'tabpanel',
                                region: 'center',
//                                    title: 'Data Entry',
//                                    tabPosition: 'top',
                                activeTab: 0,
//                                    height: '100%',
//                                    layout: 'fit',
                                items: [
                                    new GeoPortal.Forms.DataEntry.DublinCore(),
                                    new GeoPortal.Forms.DataEntry.Survey(),
                                    new GeoPortal.Forms.DataEntry.Questions(),
                                    new GeoPortal.Forms.DataEntry.Response(),
                                    new GeoPortal.Forms.DataEntry.ResponseTable()
//                                    new GeoPortal.Forms.DataEntry.DBlines()
                                ]
                            }
                        ]
                    },
                    new GeoPortal.Forms.Tagging()

                ]
            }
        ]
    });
//    var sid = 'sid_bes2005scqw';
//    Ext.getCmp("frmEntryDC").getForm().load({url: DCmetaURL,waitMsg: 'Loading.......',method: 'POST', params: {SID: sid}});
//    Ext.getCmp("frmEntrySurvey").getForm().load({ url: SmetaURL, waitMsg: 'Loading.......', method: 'POST', params: { SID: sid} });
//    Ext.getCmp("frmEntryQuestion").getForm().load({ url: QDCmetaURL, waitMsg: 'Loading.......', method: 'POST', params: { SID: sid} });
    advSearch.show();

    var frmLogin = new Ext.Window({ title: 'Fake log-in',
        items: new GeoPortal.Forms.UsabilityLogin(),
        id: 'WinLogin',
        resizable: false,
        closable: false
    });
    frmLogin.show();

//    var welcomeWin = new Geoportal.Windows.WelcomeWin();
//    welcomeWin.show();

});



</script>


<p>This is a "static" page. You may change the content of this page
    by updating the file <code><?php echo __FILE__; ?></code>.</p>

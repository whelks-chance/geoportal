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
?>
<h1>textgui testing</h1>

<script type="text/javascript">
Ext.ns('Portal');
Ext.onReady(function(){

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
//                        console.log( resp );
                    var responseData = Ext.decode(resp.responseText);
//                        console.log(responseData);
                    surveyStore.loadData(responseData);
                    thematicStore.loadData(responseData);
//                        console.log(surveyStore);
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
        items: [
            {
                xtype: 'tabpanel',
                tabPosition: 'top',
//                        border: false,
                activeTab: 0,
                items: [
                    new GeoPortal.Forms.AdvancedSearch(),
                    new GeoPortal.Forms.QuestionMatching(),
                    inner,
                    new GeoPortal.Forms.RemoteData(),
                    {
                        xtype: 'panel',
                        title: 'My Messages'
                    }
                ]
            }
        ]
    });
    advSearch.show();

    // tabs for the center
    //        var tabs = new Ext.TabPanel({
    //            region: 'center',
    //            margins:'3 3 3 0',
    //            activeTab: 0,
    //            defaults:{autoScroll:true},
    //
    //            items:[{
    //                title: 'Bogus Tab',
    //                html: ""
    //            },{
    //                title: 'Another Tab',
    //                html: ""
    //            },{
    //                title: 'Closable Tab',
    //                html: "",
    //                closable:true
    //            }]
    //        });

    // Panel for the west
    //        var nav = new Ext.Panel({
    //            title: 'Navigation',
    //            region: 'west',
    //            split: true,
    //            width: 200,
    //            collapsible: true,
    //            margins:'3 0 3 3',
    //            cmargins:'3 3 3 3'
    //        });

    //        var win = new Ext.Window({
    //            title: 'Layout Window',
    //            closable:true,
    //            width:600,
    //            height:350,
    //            //border:false,
    //            plain:true,
    //            layout: 'border',
    //
    //            items: [nav, tabs]
    //        });

    //        win.show(this);
});



</script>


<p>This is a "static" page. You may change the content of this page
    by updating the file <code><?php echo __FILE__; ?></code>.</p>

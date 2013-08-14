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

<!--    This page is not designed at all, and should not be used as an example for anything-->

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
                    {
                        xtype: 'tabpanel',
                        title: 'Data Entry',
                        tabPosition: 'top',
                        activeTab: 0,
                        items: [
                            new GeoPortal.Forms.DataEntry.DublinCore(),
                            new GeoPortal.Forms.DataEntry.Survey(),
                            new GeoPortal.Forms.DataEntry.Questions(),
                                new GeoPortal.Forms.DataEntry.Response()
                        ]
                    },
                    new GeoPortal.Forms.AdvancedSearch(),
                    new GeoPortal.Forms.Tagging(),
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
//    var sid = 'sid_bes2005scqw';
//    Ext.getCmp("frmEntryDC").getForm().load({url: DCmetaURL,waitMsg: 'Loading.......',method: 'POST', params: {SID: sid}});
//    Ext.getCmp("frmEntrySurvey").getForm().load({ url: SmetaURL, waitMsg: 'Loading.......', method: 'POST', params: { SID: sid} });
//    Ext.getCmp("frmEntryQuestion").getForm().load({ url: QDCmetaURL, waitMsg: 'Loading.......', method: 'POST', params: { SID: sid} });
    advSearch.show();

});



</script>


<p>This is a "static" page. You may change the content of this page
    by updating the file <code><?php echo __FILE__; ?></code>.</p>

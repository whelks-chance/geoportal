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

//    var data = '{"success":true,"data":[{"SurveyID":"sid_eibselfea1992","SurveyName":"Employment in Britain Survey 1992: Self-completion Employees Questionnaire A"},{"SurveyID":"sid_lfs2010","SurveyName":"Labour Force Survey: 2010 Questionnaire"},{"SurveyID":"sid_sceli86as","SurveyName":"Social Change and Economic Life Survey- Work Attitudes\/Histories Aberdeen Survey 1986"},{"SurveyID":"sid_sceli87ks","SurveyName":"Social Change and Economic Life Survey- Household and Community Kirkcaldy Survey 1987"},{"SurveyID":"sid_wers04psmq","SurveyName":"Workplace Employment Relations Survey 2004 Panel Survey Management Questionnaire"},{"SurveyID":"sid_sceli86es","SurveyName":"Social Change and Economic Life Survey- Employers Survery 1986-1987"},{"SurveyID":"sid_sceli86ms","SurveyName":"Social Change and Economic Life Survey- Work Attitudes\/Histories Main Survey 1986"},{"SurveyID":"sid_eibretq1992","SurveyName":"Employment in Britain Survey 1992: Returners Questionnaire"},{"SurveyID":"sid_sceli87rs","SurveyName":"Social Change and Economic Life Survey- Household and Community Rochdale Survey 1987"},{"SurveyID":"sid_wrohhs2004","SurveyName":"Wales Rural Observatory 2004: Household Survey"},{"SurveyID":"sid_eibuneq1992","SurveyName":"Employment in Britain Survey 1992: Unemployed Questionnaire"},{"SurveyID":"sid_ss06","SurveyName":"The Skills Survey (Main): Final Questionnaire 2006"},{"SurveyID":"sid_wibeq9902","SurveyName":"Working in Britain Employee Questionnaire 1999-2002"},{"SurveyID":"sid_wib9902self3","SurveyName":"Working in Britain Self Employed- Self-Completion Questionnaire 3 1999-2002"},{"SurveyID":"sid_eibselfret1992","SurveyName":"Employment in Britain Survey 1992: Self-completion Returners Questionnaire"},{"SurveyID":"sid_nswhq0910","SurveyName":"National Survey for Wales Household Questionnaire 2009-2010"},{"SurveyID":"sid_liwps2008","SurveyName":"Living in Wales 2008 Property Survey"},{"SurveyID":"sid_wrorss2004","SurveyName":"Wales Rural Observatory 2004: Rural Services Survey"},{"SurveyID":"sid_sceli86ns","SurveyName":"Social Change and Economic Life Survey- Work Attitudes\/Histories Northampton 1986"},{"SurveyID":"sid_ss01","SurveyName":"Second Skills Survey of the Employed British Workforce 2001"},{"SurveyID":"sid_wrorss2007","SurveyName":"Wales Rural Observatory 2007: Rural Services Survey"},{"SurveyID":"sid_wers11fpq","SurveyName":"Workplace Employment Relations Study 2011: Financial Performance Questionnaire"},{"SurveyID":"sid_sceli87ss","SurveyName":"Social Change and Economic Life Survey- Household and Community Swindon Survey 1987"},{"SurveyID":"sid_wers98mq","SurveyName":"Workplace Employee Relations Survery 1998 Cross-section questionnaires Management questionnaire"},{"SurveyID":"sid_wrorbs2010","SurveyName":"Wales Rural Observatory 2010: Rural Business Survey"},{"SurveyID":"sid_sceli86rs","SurveyName":"Social Change and Economic Life Survey- Work Attitudes\/Histories Rochdale 1986"},{"SurveyID":"sid_wers11mq","SurveyName":"Sixth Workplace Employment Relations Study (WERS6) Management Questionnaire (MQ) 2011"},{"SurveyID":"sid_whs2011aq","SurveyName":"Welsh Health Survey 2011 Questionnaire For Adults"},{"SurveyID":"sid_wibseq9902","SurveyName":"Working In Britain In The Year 2000- Self Employed Interview"},{"SurveyID":"sid_hacstatistics","SurveyName":"Shelter HAC Statistics Sheet"},{"SurveyID":"sid_wers98v4pq","SurveyName":"Workplace Employee Relations Survery 1998 Panel questionnaires"},{"SurveyID":"sid_whs2010_03","SurveyName":"Welsh Health Survey 2010 Questionnaire For Parents Of 0-3 Year Olds"},{"SurveyID":"sid_wes1979","SurveyName":"Welsh Election Study 1979"},{"SurveyID":"sid_wib9902self2","SurveyName":"Working in Britain Non-Managerial and Professional Self - Self-Completion Questionnaire 2 1999-2002"},{"SurveyID":"sid_liwtwlts2007scq","SurveyName":"Life In Wales Today (Welsh Life & Times Study) 2007 Self-Completion Questionnaire"},{"SurveyID":"sid_bsabq2006","SurveyName":"British Social Attitudes 2006 Documentation of The Blaise Questionnaire"},{"SurveyID":"sid_wrs1997selfq","SurveyName":"Welsh Referendum Study 1997: Self-completion Questionnaire"},{"SurveyID":"sid_fsnw98es","SurveyName":"Future Skills Needs in Wales 1998 Employers Survey- Final Version"},{"SurveyID":"sid_wrs1997faceq","SurveyName":"Welsh Referendum Study 1997: Face-to-face Questionnaire"},{"SurveyID":"sid_fswgss03eq","SurveyName":"Future Skills Wales Generic Skills Survey 2003 Employer Questionnaire"},{"SurveyID":"sid_fswsss05eq","SurveyName":"Future Skills Wales Sector Skills Survey 2005 Employer Questionnaire"},{"SurveyID":"sid_wrohhs2007","SurveyName":"Wales Rural Observatory 2007: Household Survey"},{"SurveyID":"sid_nsfwiq0910","SurveyName":"National Survey Wales, Individual Questionnaire"},{"SurveyID":"sid_fswgss98hs","SurveyName":"Future Skills Wales Generic Skills Survey 1998 Household Survey"},{"SurveyID":"sid_wrofhs2010","SurveyName":"Wales Rural Observatory 2010: Farming Household Survey"},{"SurveyID":"sid_eibselfeb1992","SurveyName":"Employment in Britain Survey 1992: Self-completion Employees Questionnaire B"},{"SurveyID":"sid_wss92prq","SurveyName":"Wales Social Survery 1992 Private Renters Questionnaire"},{"SurveyID":"sid_liwtwlts2007","SurveyName":"Life In Wales Today (Welsh Life & Times Study) 2007 The Blaise Questionnaire"},{"SurveyID":"sid_fswgss03hs","SurveyName":"Future Skills Wales Generic Skills Survey 2003 Household Survey"},{"SurveyID":"sid_c91hfw","SurveyName":"1991 Census Wales, W form for Private Households"},{"SurveyID":"sid_whs2011_412","SurveyName":"Welsh Health Survey 2011 Questionnaire For Parents Of 4-12 Year Olds"},{"SurveyID":"sid_nswaq0910","SurveyName":"National Survey for Wales Adult Questionnaire"},{"SurveyID":"sid_nsweg0910","SurveyName":"National Survey for Wales Enumeration Grid 2010-2010"},{"SurveyID":"sid_eibselfune1992","SurveyName":"Employment in Britain Survey 1992: Self-completion Unemployed Questionnaire"},{"SurveyID":"sid_c01cef","SurveyName":"Census 2001 England and Wales Communal Establishment Form CE1"},{"SurveyID":"sid_sceli86cs","SurveyName":"Social Change and Economic Life Survey- Work Attitudes\/Histories Coventry 1986"},{"SurveyID":"sid_wers98cqepq","SurveyName":"Workplace Employee Relations Survey 1998  Cross-section questionnaires: Employee profile questionnaire"},{"SurveyID":"sid_wers04epq","SurveyName":"Workplace Employment Relations Survey 2004: Employee Profile Questionnaire"},{"SurveyID":"sid_wers11epq","SurveyName":"Workplace Employment Relations Study 2011: Employee Profile Questionnaire"},{"SurveyID":"sid_wrorbs2007","SurveyName":"Wales Rural Observatory 2007: Rural Business Survey"},{"SurveyID":"sid_c01whf","SurveyName":"Census 2001 Wales Household Form H2"},{"SurveyID":"sid_wers98v4pqbw","SurveyName":"Workplace Employee Relations Survey 1998  Panel questionnaires: Basic workforce data sheet"},{"SurveyID":"sid_sceli86ss","SurveyName":"Social Change and Economic Life Survey- Work Attitudes\/Histories Swindon 1986"},{"SurveyID":"sid_bsabq2006a","SurveyName":"BRITISH SOCIAL ATTITUDES 2006 SELF-COMPLETION QUESTIONNAIRE A"},{"SurveyID":"sid_bsabq2006b","SurveyName":"BRITISH SOCIAL ATTITUDES 2006 SELF-COMPLETION QUESTIONNAIRE B"},{"SurveyID":"sid_bes2005post","SurveyName":"BRITISH ELECTION STUDY 2005 POST-ELECTION WAVE QUESTIONNAIRE"},{"SurveyID":"sid_bsabq2006c","SurveyName":"BRITISH SOCIAL ATTITUDES 2006 SELF-COMPLETION QUESTIONNAIRE C"},{"SurveyID":"sid_bes2005posttw","SurveyName":"BRITISH ELECTION STUDY POST ELECTION WAVE TELEPHONE WAVE"},{"SurveyID":"sid_bsabq2006d","SurveyName":"BRITISH SOCIAL ATTITUDES 2006 SELF-COMPLETION QUESTIONNAIRE D"},{"SurveyID":"sid_bes2005oyo","SurveyName":"2005-06 BRITISH ELECTION STUDY - ONE YEAR OUT (INTERNET) QUESTIONNAIRE"},{"SurveyID":"sid_bes2005scqe","SurveyName":"BRITISH ELECTION STUDY 2005 SELF-COMPLETION QUESTIONNAIRE (ENGLAND)"},{"SurveyID":"sid_bes2005scqw","SurveyName":"BRITISH ELECTION STUDY 2005 SELF-COMPLETION QUESTIONNAIRE (WALES)"},{"SurveyID":"sid_wss92etscq","SurveyName":"Wales Social Survey 1992 Education and Training Self-Completion Questionnaire"},{"SurveyID":"sid_wael99bq","SurveyName":"Welsh Assembly Election Study 1999 The Blaise Questionnaire"},{"SurveyID":"sid_bes2005scqs","SurveyName":"BRITISH ELECTION STUDY 2005 SELF-COMPLETION QUESTIONNAIRE (SCOTLAND)"},{"SurveyID":"sid_bes2005pre","SurveyName":"BRITISH ELECTION STUDY 2005 PRE-ELECTION WAVE QUESTIONNAIRE"},{"SurveyID":"sid_wss92cq","SurveyName":"Wales Social Survey 1992 Continuation Questionnaire"},{"SurveyID":"sid_eibseq1992","SurveyName":"Employment in Britain Survey 1992: Self-employed Questionnaire"},{"SurveyID":"sid_wers11seq","SurveyName":"Workplace Employment Relations Study 2011: Survey of Employees"},{"SurveyID":"sid_whs10hq","SurveyName":"Welsh Health Survey 2010 Household Questionnaire"},{"SurveyID":"sid_whs2010aq","SurveyName":"Welsh Health Survey 2010 Questionnaire For Adults"},{"SurveyID":"sid_wers98eq","SurveyName":"Workplace Employee Relations Survery 1998 Cross-section questionnaires: The 1997 Workplace Employee Relations Survey - Employee Questionnaire"},{"SurveyID":"sid_c11hq","SurveyName":"Census 2011 Household Questionnaire"},{"SurveyID":"sid_wers98wrq","SurveyName":"Workplace Employee Relations Survery 1998 Cross-section questionnaires Part B: Worker Representative Questionnaire"},{"SurveyID":"sid_whs2008aq","SurveyName":"Welsh Health Survey 2008 Questionnaire for Adults"},{"SurveyID":"sid_whs2007aq","SurveyName":"Welsh Health Survey 2007 Questionnaire For Adults"},{"SurveyID":"sid_liwps2004","SurveyName":"Living In Wales 2004 Property Survey"},{"SurveyID":"sid_liwhh2006","SurveyName":"Living in Wales 2006 Household Questionnaire"},{"SurveyID":"sid_liwhh2004","SurveyName":"Living in Wales 2004 Household Questionnaire"},{"SurveyID":"sid_whshh2009","SurveyName":"Welsh Health Survey 2009 Household Questionnaire"},{"SurveyID":"sid_whs2007_03","SurveyName":"Welsh Health Survey 2007 Questionnaire For Parents Of 0-3 Year Olds"},{"SurveyID":"sid_liwhh2005","SurveyName":"Living in Wales 2005 Household Questionnaire"},{"SurveyID":"sid_whs2008_412","SurveyName":"Welsh Health Survey 2008 Questionnaire For Parents Of 4-12 Year Olds"},{"SurveyID":"sid_whshh0306","SurveyName":"Welsh Health Survey 2003-2006 Household Questionnaire"},{"SurveyID":"sid_wersmq2004","SurveyName":"Workplace Employment Relations Survey 2004 Cross-Section Management Questionnaire"},{"SurveyID":"sid_whs2009_412","SurveyName":"Welsh Health Survey 2009 Questionnaire For Parents Of 4-12 Year Olds"},{"SurveyID":"sid_whs2007_412","SurveyName":"Welsh Health Survey 2007 Questionnaire For Parents Of 4-12 Year Olds"},{"SurveyID":"sid_whshh2007","SurveyName":"Welsh Health Survey 2007 Household Questionnaire"},{"SurveyID":"sid_whs2007_1315","SurveyName":"Welsh Health Survey 2007 Questionnaire For 13-15 Year Olds"},{"SurveyID":"sid_whs2011_03","SurveyName":"Welsh Health Survey 2011 Questionnaire For Parents Of 0-3 Year Olds"},{"SurveyID":"sid_liw2007","SurveyName":"Living in Wales 2007 Household Questionnaire"},{"SurveyID":"sid_whs0306cq","SurveyName":"Welsh Health Survey 2003-2006 Child Questionnaire"},{"SurveyID":"sid_whs0306aq","SurveyName":"Welsh Health Survey 2003-2006 Questionnaire For Adults"},{"SurveyID":"sid_whs2008_1315","SurveyName":"Welsh Health Survey 2008 Questionnaire For 13-15 Year Olds"},{"SurveyID":"sid_whs2009_03","SurveyName":"Welsh Health Survey 2009 Questionnaire For Parents Of 0-3 Year Olds"},{"SurveyID":"sid_wers11wrq","SurveyName":"Workplace Employment Relations Study 2011: Worker Representative Questionnaire"},{"SurveyID":"sid_sceli86ks","SurveyName":"Social Change and Economic Life Survey- Work Attitudes\/Histories Kirkcaldy 1986"},{"SurveyID":"sid_whs2010_412","SurveyName":"Welsh Health Survey 2010 Questionnaire For Parents Of 4-12 Year Olds"},{"SurveyID":"sid_sceli87ms","SurveyName":"Social Change and Economic Life Survey- Household and Community Main Survey 1987"},{"SurveyID":"sid_eibeq1992","SurveyName":"Employment in Britain Survey 1992: Employed Questionnaire"},{"SurveyID":"sid_whs2009_1315","SurveyName":"Welsh Health Survey 2009 Questionnaire For 13-15 Year Olds"},{"SurveyID":"sid_whs2008_03","SurveyName":"Welsh Health Survey 2008 Questionnaire For Parents Of 0-3 Year Olds"},{"SurveyID":"sid_whs2009aq","SurveyName":"Welsh Health Survey 2009 Questionnaire For Adults"},{"SurveyID":"sid_wers04fpq","SurveyName":"Workplace Employment Relations Survey 2004: Financial Performance Questionnaire 2004"},{"SurveyID":"sid_whshh2008","SurveyName":"Welsh Health Survey 2008 Household Questionnaire"},{"SurveyID":"sid_wers04eq","SurveyName":"Workplace Employment Relations Survey 2004: Employee Questionnaire"},{"SurveyID":"sid_sceli87cs","SurveyName":"Social Change and Economic Life Survey- Household and Community Coventry Survey 1987"},{"SurveyID":"sid_sceli87ns","SurveyName":"Social Change and Economic Life Survey- Household and Community Northampton Survey 1987"},{"SurveyID":"sid_wib9902self1","SurveyName":"Working in Britain Managerial and Professional Employees - Self-Completion Questionnaire1 1999-2002"},{"SurveyID":"sid_wers04bwds","SurveyName":"Workplace Employment Relations Survey 2004: Basic Workforce Data Sheet"},{"SurveyID":"sid_wrohhs2010","SurveyName":"Wales Rural Observatory 2010: Household Survey"},{"SurveyID":"sid_wrorss2010","SurveyName":"Wales Rural Observatory 2010: Rural Services Survey"},{"SurveyID":"sid_wrorbs2004","SurveyName":"Wales Rural Observatory 2004: Rural Business Survey"},{"SurveyID":"sid_sceli87as","SurveyName":"Social Change and Economic Life Survey- Household and Community Aberdeen Survey 1987"},{"SurveyID":"sid_wers04wrq","SurveyName":"Workplace Employment Relations Survey 2004: Cross Section Worker Rep Questionnaire"},{"SurveyID":"sid_liw2008","SurveyName":"Living in Wales 2008 Household Questionnaire"},{"SurveyID":"sid_whs2011_1315","SurveyName":"Welsh Health Survey 2011 Questionnaire For 13-15 Year Olds"},{"SurveyID":"sid_ss97","SurveyName":"Skills Survey of the Employed British Workforce 1997 with Social and Community Planning Research"},{"SurveyID":"sid_whs2010_1315","SurveyName":"Welsh Health Survey 2010 Questionnaire For 13-15 Year Olds"}]}';

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
            width: 400,
            height: 300,
            layout: 'fit',
            items: [
                {
                    xtype: 'tabpanel',
                    tabPosition: 'top',
//                        border: false,
                    activeTab: 0,
                    items: [
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

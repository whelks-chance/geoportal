Ext.ns('Geoportal.Windows');
Geoportal.Windows.WelcomeWin = Ext.extend(Ext.Window, {
    title: 'Welcome to the WISERD DataPortal',
    width: Ext.getBody().getViewSize().width * 0.5,
    height: Ext.getBody().getViewSize().height * 0.8,
    resizable: true,
    maximizable: true,
//    modal: true,
//    autoScroll: 'true',
    draggable: true,
    initComponent: function () {
        this.items = [
            {
                xtype: 'tabpanel',
                id: 'welcometabs',
                activeTab: 0,
//                layout:'fit',
//                width: 600,
//                height: Ext.getBody().getViewSize().height * 0.78,
//                autoScroll: 'true',
//                padding: 20,
                items: [
                    {
                        xtype: 'panel',
                        title: 'Introduction',
                        layout: 'fit',
                        id: 'tabIntro',
                        autoScroll: 'true',
                        autoLoad: 'protected/Content/html/intro.htm'


                    },
                    {
                        xtype: 'panel',
                        title: 'User Guide',
//                        layout: 'fit',
                        id: 'tabUserGuide',
                        autoScroll: 'true',
                        autoWidth: true,
                        autoHeight: true,
                        layoutOnTabChange: true,
//                        listeners : {
//                            activate: function() {
//                                console.log('listened to an active');
//                                var tabs = Ext.getCmp('welcometabs');
//                                var tab = tabs.getActiveTab();
//                                var updater = tab.getUpdater();
//                                updater.refresh();
//                            }
//                        },
                        autoLoad: 'protected/Content/html/data_entry_project_management.html'
                    },
//                    {
//                        xtype: 'panel',
//                        title: 'Blog',
//                        id: 'tabBlog',
//                        autoScroll: 'true',
//                        autoLoad: {
//                            url : blogRSSUrl,
//                            scripts: true
//                        }
//                    },
//                    {
//                        xtype: 'panel',
//                        title: 'Tutorials',
//                        id: 'tabTutorials',
//                        autoScroll: 'true',
//                        autoLoad: 'protected/Content/html/tutorials.htm'
//                    },
//                    {
//                        xtype: 'panel',
//                        title: 'FAQs',
//                        id: 'tabFAQ',
//                        autoScroll: 'true',
//                        autoLoad: 'protected/Content/html/FAQs.htm'
//                    },
                    {
                        xtype: 'panel',
                        title: 'About',
                        id: 'tabAbout',
                        autoScroll: 'true',
                        autoLoad: 'protected/Content/html/about.htm'
                    }
                ]
            }
        ];
        Geoportal.Windows.WelcomeWin.superclass.initComponent.call(this);
    }
});
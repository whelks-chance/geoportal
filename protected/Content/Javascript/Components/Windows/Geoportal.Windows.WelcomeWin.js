Ext.ns('Geoportal.Windows');
Geoportal.Windows.WelcomeWin = Ext.extend(Ext.Window, {
    title: 'Welcome to the WISERD DataPortal',
    width: Ext.getBody().getViewSize().width * 0.5,
    height: Ext.getBody().getViewSize().height * 0.8,
    resizable: true,
    modal: true,
//    autoScroll: 'true',
    draggable: true,
    initComponent: function () {
        this.items = [
            {
                xtype: 'tabpanel',
                activeTab: 0,
//                layout:'fit',
//                width: 600,
//                height: Ext.getBody().getViewSize().height * 0.78,
                autoScroll: 'true',
                padding: 20,
                items: [
                    {
                        xtype: 'panel',
                        title: 'Introduction',
                        id: 'tabIntro',
                        autoScroll: 'true',
                        autoLoad: 'protected/Content/html/intro.htm'


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
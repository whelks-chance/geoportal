/**
 * Created with JetBrains PhpStorm.
 * User: wiserd
 * Date: 13/08/13
 * Time: 17:17
 * To change this template use File | Settings | File Templates.
 */

var SurveyMainEntry = new Ext.Panel({
    title: 'Survey Entry - Main',
    id: 'panelSurveyEntryMain',
    width: 800,
    height: 600,
    layout: 'fit',
    items : [
        {
            xtype: 'tabpanel',
            tabPosition: 'top',
//                        border: false,
            activeTab: 0,
            items: [
                new GeoPortal.Forms.DataEntry.DublinCore()
            ]
        }
    ]

});
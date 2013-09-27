<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 13/02/13
 * Time: 12:40
 * To change this template use File | Settings | File Templates.
 */


$this->pageTitle=Yii::app()->name . ' | DataPortal';
$this->breadcrumbs=array(
    'Portal',
);


?>

<script type="text/javascript">

    Ext.Ajax.timeout = 60000; // 60 seconds

    //wait until ext/DOM has loaded before executing JavaScript
    Ext.onReady(function () {
        //enable QuickTips (Ext tooltips)
        Ext.QuickTips.init();
        Ext.QuickTips.enable();

        var google = google;
        //var latlng = new google.maps.LatLng(-34.397, 150.644);
        var layers = GetLayers();

//        var myAC = new GeoPortal.Windows.MyAccount();
        // myAC.show();

        //initiate EXT viewport - the foundation class on which EXT application is built
        new Ext.Viewport({
            id: 'mainView',
            layout: "border",
            //the items that comrpise the layout of the viewport
            items: [{
                region: "center",
                //main map
                id: "mappanel",
                //title: "Map",
                xtype: "gx_mappanel",
                map: map,
                //top and bottom toolbars
                tbar: new GeoPortal.Toolbars.MapToolbar(),
                bbar: new GeoPortal.Toolbars.MapStatus(),
                //bottom toolbar items
                items: [{
                    xtype: "gx_zoomslider",
                    id: 'zoomSlider',
                    vertical: true,
                    height: 150,
                    plugins: new GeoExt.ZoomSliderTip({ template: "<div>Zoom Level: {zoom}</div><div>Scale: 1 : {scale}</div>" })
                }],
                layers: layers,
                split: true
            }]
        });

        //set map location udsih
        var centPoint = new OpenLayers.LonLat(-3.76622, 52.33);
        centPoint.transform(geographic, OSGB);
        map.setCenter(centPoint, 1);

        //add loading panel
        var loadingpanel = new OpenLayers.Control.LoadingPanel();
        map.addControl(loadingpanel);


        //create TOC window
        var FindTB = new GeoPortal.Toolbars.FindTools({ id: 'findTB', closable: false, x: 280, y: 36, animateTarget: Ext.getCmp('btnFind').el, iconCls: 'findTools' });
        //FindTB.show();
        var TOC = new Ext.Window({
            title: 'Map Layers',
            width: 250,
            x: 10,
            y: 36,
            id: 'TOC',
            iconCls: 'tocWindow',
            constrain: true,
            closable: false,
            resizable: false,
            animateTarget: Ext.getCmp('btnTOC').el,
            items: new GeoPortal.Panels.TreePanel()
        });
        TOC.show();



        map.addControl(new OpenLayers.Control.MousePosition({ div: document.getElementById('txtSBLatLon'), displayProjection: geographic }));
        //GetMyLocation();

        map.addControl(new OpenLayers.Control.Navigation({ 'zoomWheelEnabled': true }));

        // layers[0].mapObject.mapTypes.set("greyscale", styledMap);

        //draw irregular polygon


        polyfinished = function (feature) {
            var poly = new OpenLayers.Feature.Vector(feature, { 'name': 'Search Polygon', 'type': 'place' });
            SearchLayer.addFeatures(poly);

            // alert('Poly Done');
        }



        drawPoly = new OpenLayers.Control.DrawFeature(SearchLayer, OpenLayers.Handler.Polygon, { callbacks: { 'done': polyfinished} });


        map.addControl(drawPoly);


        var welcomeWin = new Geoportal.Windows.WelcomeWin();
        welcomeWin.show();


        var frmLogin = new Ext.Window({ title: 'Welcome to the WISERD DataPortal  - Please log-in',
            items: new GeoPortal.Forms.UsabilityLogin(),
            id: 'WinLogin',
            resizable: false,
            //remove 'x' close button
            closable: false,
            modal: true,
            draggable: false
        });

        //show the login form
        frmLogin.show();



        //when correct user details have been entered, remove mask (F in GeoLocate.js) to allow users to proceed
        RemoveMask();

    });



</script>

<form id="form1" runat="server">
    <div>
        <div id="loading-mask"></div>
        <div id="loading">
            <div class="loading-indicator">
                loading....
            </div>
        </div>
        <div id="toc"></div>
    </div>

<!--    <div id="logon" >-->
<!--        <table border="0" cellpadding="2" cellspacing="2">-->
<!--            <tr>-->
<!--                <td>-->
<!--                    <asp:Label ID="Label1" runat="server" Text="Username:" />-->
<!--                </td>-->
<!--                <td>-->
<!--                    <asp:TextBox runat="server" ID="uname" />-->
<!--                </td>-->
<!--            </tr>-->
<!--            <tr></tr>-->
<!--            <tr>-->
<!--                <td>-->
<!--                    <asp:Label ID="Label2" runat="server" Text="Password:" />-->
<!--                </td>-->
<!--                <td>-->
<!--                    <asp:TextBox runat="server" ID="pword" TextMode="Password" />-->
<!--                </td>-->
<!--            </tr>-->
<!--        </table>-->
<!--    </div>-->

    <div id="mapLoader" style="padding: 5px; background-color: white; text-align: justify; font-style:italic; font-family:Sans-Serif; font-variant:small-caps" >
        <img src=" <? echo Yii::app()->baseUrl?>/images/loading_small.gif" style="padding-right: 2px; padding-left: 2px" /><a>Loading Map.....</a>
    </div>
</form>
